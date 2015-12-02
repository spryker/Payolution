<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication;

use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormDelete;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryCommunication;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainerInterface;
use SprykerFeature\Zed\Cms\Business\CmsFacade; //TODO: https://spryker.atlassian.net/browse/CD-540
use SprykerFeature\Zed\ProductCategory\Communication\Table\ProductCategoryTable;
use SprykerFeature\Zed\ProductCategory\Communication\Table\ProductTable;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategory\ProductCategoryDependencyProvider;
use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormAdd;
use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * @method ProductCategoryCommunication getFactory()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return LocaleTransfer
     */
    public function createCurrentLocale()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale();
    }

    /**
     * @throws \ErrorException
     *
     * @return ProductFacade
     */
    public function createProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @throws \ErrorException
     *
     * @return CategoryFacade
     */
    public function createCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @throws \ErrorException
     *
     * @return ProductCategoryFacade
     */
    public function createProductCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * TODO: https://spryker.atlassian.net/browse/CD-540
     *
     * @throws \ErrorException
     *
     * @return CmsFacade
     */
    public function createCmsFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CMS);
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function createProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return CategoryQueryContainerInterface
     */
    public function createCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return ProductQueryContainerInterface
     */
    public function createProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @param int $idParentNode
     *
     * @return CategoryFormAdd
     */
    public function createCategoryFormAdd($idParentNode)
    {
        return new CategoryFormAdd(
            $this->createCategoryQueryContainer(),
            $this->createProductCategoryQueryContainer(),
            $this->createCurrentLocale(),
            null,
            $idParentNode
        );
    }

    /**
     * @param int $idCategory
     *
     * @return CategoryFormEdit
     */
    public function createCategoryFormEdit($idCategory)
    {
        return new CategoryFormEdit(
            $this->createCategoryQueryContainer(),
            $this->createProductCategoryQueryContainer(),
            $this->createCurrentLocale(),
            $idCategory,
            null
        );
    }

    /**
     * @param int $idCategory
     *
     * @return CategoryFormEdit
     */
    public function createCategoryFormDelete($idCategory)
    {
        return new CategoryFormDelete(
            $this->createCategoryQueryContainer(),
            $this->createProductCategoryQueryContainer(),
            $this->createCurrentLocale(),
            $idCategory,
            null
        );
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return ProductCategoryTable
     */
    public function createProductCategoryTable(LocaleTransfer $locale, $idCategory)
    {
        $productCategoryQueryContainer = $this->createProductCategoryQueryContainer();

        return new ProductCategoryTable($productCategoryQueryContainer, $locale, $idCategory);
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return ProductTable
     */
    public function createProductTable(LocaleTransfer $locale, $idCategory)
    {
        $productCategoryQueryContainer = $this->createProductCategoryQueryContainer();

        return new ProductTable($productCategoryQueryContainer, $locale, $idCategory);
    }

    /**
     * @throws \ErrorException
     *
     * @return ConnectionInterface
     */
    public function createPropelConnection()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION);
    }

}
