<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeAbstractForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeSuperForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\PriceForm as ConcretePriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Symfony\Component\Form\FormInterface;

class ProductFormTransferMapper implements ProductFormTransferMapperInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface $utilTextService
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToUtilTextInterface $utilTextService,
        LocaleProvider $localeProvider
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->utilTextService = $utilTextService;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function buildProductAbstractTransfer(FormInterface $form)
    {
        $formData = $form->getData();
        $attributeValues = $this->generateAbstractAttributeArrayFromData($formData);
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $productAbstractTransfer = $this->createProductAbstractTransfer(
            $formData,
            $attributeValues[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE]
        );

        $localizedData = $this->generateLocalizedData($localeCollection, $formData);

        foreach ($localizedData as $localeCode => $data) {
            $localeTransfer = $this->localeFacade->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createAbstractLocalizedAttributesTransfer(
                $form,
                $attributeValues[$localeCode],
                $localeTransfer
            );

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $priceTransfer = $this->buildProductAbstractPriceTransfer($form);
        $productAbstractTransfer->setPrice($priceTransfer);

        $imageSetCollection = $this->buildProductImageSetCollection($form);
        $productAbstractTransfer->setImageSets(
            new ArrayObject($imageSetCollection)
        );

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     * @param array $formData
     *
     * @return array
     */
    protected function generateLocalizedData(array $localeCollection, array $formData)
    {
        $localizedData = [];
        foreach ($localeCollection as $localeTransfer) {
            $generalFormName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
            $seoFormName = ProductFormAdd::getSeoFormName($localeTransfer->getLocaleName());

            $localizedData[$localeTransfer->getLocaleName()] = array_merge(
                $formData[$generalFormName],
                $formData[$seoFormName]
            );
        }

        return $localizedData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProductConcreteTransfer(ProductAbstractTransfer $productAbstractTransfer, FormInterface $form, $idProduct)
    {
        $sku = $form->get(ProductConcreteFormEdit::FIELD_SKU)->getData();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($idProduct);
        $productConcreteTransfer->setAttributes($this->getAttributes($form));
        $productConcreteTransfer->setSku($sku);
        $productConcreteTransfer->setIsActive(false);
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());

            $localizedAttributesTransfer = $this->createConcreteLocalizedAttributesTransfer(
                $form->get($formName),
                [],
                $localeTransfer
            );

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $priceTransfer = $this->buildProductConcretePriceTransfer($form, $productConcreteTransfer->getIdProductConcrete());
        $productConcreteTransfer->setPrice($priceTransfer);

        $stockCollection = $this->buildProductStockCollectionTransfer($form);
        $productConcreteTransfer->setStocks(new ArrayObject($stockCollection));

        $imageSetCollection = $this->buildProductImageSetCollection($form);
        $productConcreteTransfer->setImageSets(
            new ArrayObject($imageSetCollection)
        );

        return $productConcreteTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(array $data, array $attributes)
    {
        $attributes = array_filter($attributes);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($data[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT])
            ->setSku(
                $this->utilTextService->generateSlug($data[ProductFormAdd::FIELD_SKU])
            )
            ->setAttributes($attributes)
            ->setIdTaxSet($data[ProductFormAdd::FORM_PRICE_AND_TAX][PriceForm::FIELD_TAX_RATE]);

        return $productAbstractTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $formObject
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createAbstractLocalizedAttributesTransfer(FormInterface $formObject, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $formName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
        $form = $formObject->get($formName);

        $abstractLocalizedAttributes = array_filter($abstractLocalizedAttributes);
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($form->get(GeneralForm::FIELD_NAME)->getData());
        $localizedAttributesTransfer->setDescription($form->get(GeneralForm::FIELD_DESCRIPTION)->getData());
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);

        $formName = ProductFormAdd::getSeoFormName($localeTransfer->getLocaleName());
        if ($formObject->has($formName)) {
            $form = $formObject->get($formName);

            $localizedAttributesTransfer->setMetaTitle($form->get(SeoForm::FIELD_META_TITLE)->getData());
            $localizedAttributesTransfer->setMetaKeywords($form->get(SeoForm::FIELD_META_KEYWORDS)->getData());
            $localizedAttributesTransfer->setMetaDescription($form->get(SeoForm::FIELD_META_DESCRIPTION)->getData());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createConcreteLocalizedAttributesTransfer(FormInterface $form, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $abstractLocalizedAttributes = array_filter($abstractLocalizedAttributes);
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($form->get(GeneralForm::FIELD_NAME)->getData());
        $localizedAttributesTransfer->setDescription($form->get(GeneralForm::FIELD_DESCRIPTION)->getData());
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);

        return $localizedAttributesTransfer;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function generateAbstractAttributeArrayFromData(array $data)
    {
        $attributes = [];
        $localeCollection = $this->localeProvider->getLocaleCollection(true);

        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getAbstractAttributeFormName($localeTransfer->getLocaleName());
            foreach ($data[$formName] as $type => $values) {
                $attributes[$localeTransfer->getLocaleName()][$type] = $values['value'];
            }
        }

        return $attributes;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $attributeTransferCollection
     *
     * @return array
     */
    public function generateVariantAttributeArrayFromData(array $data, array $attributeTransferCollection)
    {
        $result = [];
        foreach ($data[ProductFormAdd::FORM_ATTRIBUTE_SUPER] as $type => $values) {
            $attributeValues = $this->getVariantValues($values, $attributeTransferCollection[$type]);
            if ($attributeValues) {
                $result[$type] = $attributeValues;
            }
        }

        return $result;
    }

    /**
     * @param array $variantData
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     *
     * @return array|null
     */
    protected function getVariantValues(array $variantData, ProductManagementAttributeTransfer $attributeTransfer)
    {
        $hasValue = $variantData[AttributeSuperForm::FIELD_NAME];
        $values = (array)$variantData[AttributeSuperForm::FIELD_VALUE];

        if (!$hasValue) {
            return null;
        }

        if (empty($hasValue)) {
            return null;
        }

        return $values;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function buildProductAbstractPriceTransfer(FormInterface $form)
    {
        $price = $form->get(ProductFormAdd::FORM_PRICE_AND_TAX)->get(PriceForm::FIELD_PRICE)->getData();
        $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();

        $priceTransfer = (new PriceProductTransfer())
            ->setIdProductAbstract($idProductAbstract)
            ->setPrice($price);

        return $priceTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function buildProductImageSetCollection(FormInterface $form)
    {
        $transferCollection = [];
        $localeCollection = $this->localeProvider->getLocaleCollection(true);

        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getImagesFormName($localeTransfer->getLocaleName());

            $imageSetCollection = $form->get($formName);
            foreach ($imageSetCollection as $imageSet) {
                $imageSetData = array_filter($imageSet->getData());

                $imageSetTransfer = (new ProductImageSetTransfer())
                    ->fromArray($imageSetData, true);

                if ($this->localeFacade->hasLocale($localeTransfer->getLocaleName())) {
                    $imageSetTransfer->setLocale($localeTransfer);
                }

                $productImages = $this->buildProductImageCollection(
                    $imageSet->get(ImageSetForm::PRODUCT_IMAGES)->getData()
                );
                $object = new ArrayObject($productImages);
                $imageSetTransfer->setProductImages($object);

                $transferCollection[] = $imageSetTransfer;
            }
        }

        return $transferCollection;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildProductImageCollection(array $data)
    {
        $result = [];

        foreach ($data as $imageData) {
            $imageTransfer = new ProductImageTransfer();
            $imageData[ImageCollectionForm::FIELD_SORT_ORDER] = (int)$imageData[ImageCollectionForm::FIELD_SORT_ORDER];
            $imageTransfer->fromArray($imageData, true);

            $result[] = $imageTransfer;
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function buildProductConcretePriceTransfer(FormInterface $form, $idProduct)
    {
        $price = $form->get(ProductFormAdd::FORM_PRICE_AND_TAX)->get(ConcretePriceForm::FIELD_PRICE)->getData();

        $priceTransfer = (new PriceProductTransfer())
            ->setIdProduct($idProduct)
            ->setPrice($price);

        return $priceTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function buildProductStockCollectionTransfer(FormInterface $form)
    {
        $result = [];
        $sku = $form->get(ProductFormAdd::FIELD_SKU)->getData();

        foreach ($form->get(ProductFormAdd::FORM_PRICE_AND_STOCK) as $stockForm) {
            $stockData = $stockForm->getData();
            $type = $stockForm->get(StockForm::FIELD_TYPE)->getData();
            $quantity = $stockForm->get(StockForm::FIELD_QUANTITY)->getData();
            $isNeverOutOfStock = $stockForm->get(StockForm::FIELD_IS_NEVER_OUT_OF_STOCK)->getData();

            $stockTransfer = (new StockProductTransfer())
                ->fromArray($stockData, true)
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setStockType($type)
                ->setIsNeverOutOfStock($isNeverOutOfStock);

            $result[] = $stockTransfer;
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    protected function getAttributes(FormInterface $form)
    {
        $attributes = [];

        $defaultName = ProductFormAdd::getLocalizedPrefixName(
            ProductFormAdd::FORM_ATTRIBUTE_ABSTRACT,
            ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE
        );

        foreach ($form->get($defaultName)->getData() as $attributeKey => $attributeData) {
            if ($attributeData[AttributeAbstractForm::FIELD_VALUE] !== null) {
                $attributes[$attributeKey] = $attributeData[AttributeAbstractForm::FIELD_VALUE];
            }
        }

        return $attributes;
    }

}
