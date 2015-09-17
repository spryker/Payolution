<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Unit\SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountry;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper\Invoice;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class InvoiceTest extends Test
{

    public function testMapToPreCheck()
    {
        $paymentEntityMock = $this->getPaymentEntityMock();

        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(10000);

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setGender('Male')
            ->setSalutation('Mr')
            ->setBirthdate('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($paymentEntityMock->getSpySalesOrder()->getIdSalesOrder())
            ->setPayolutionPayment($paymentTransfer)
            ->setBillingAddress($addressTransfer)
            ->setTotals($totalsTransfer);

        $methodMapper = new Invoice($this->getBundleConfigMock());
        $requestTransfer = $methodMapper->mapToPreCheck($orderTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_PRE_CHECK, $requestTransfer->getPaymentCode());

        $criteria = $requestTransfer->getAnalysisCriteria();
        $this->assertCount(1, $criteria);
        $this->assertSame($criteria[0]->getName(), Constants::CRITERION_PRE_CHECK);
        $this->assertSame($criteria[0]->getValue(), 'TRUE');
    }

    public function testMapToPreAuthorization()
    {
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToPreAuthorization($paymentEntityMock);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame($paymentEntityMock->getEmail(), $requestTransfer->getContactEmail());
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestTransfer->getPaymentCode());
        $this->assertCount(2, $requestTransfer->getAnalysisCriteria());
    }

    public function testMapToReAuthorization()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToReAuthorization($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_RE_AUTHORIZATION, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->assertNull($requestTransfer->getNameGiven());
        $this->assertNull($requestTransfer->getNameFamily());
        $this->assertNull($requestTransfer->getNameTitle());
        $this->assertNull($requestTransfer->getNameSex());
        $this->assertNull($requestTransfer->getNameBirthdate());
        $this->assertNull($requestTransfer->getAddressCity());
        $this->assertNull($requestTransfer->getAddressCountry());
        $this->assertNull($requestTransfer->getAddressStreet());
        $this->assertNull($requestTransfer->getAddressZip());
        $this->assertNull($requestTransfer->getContactEmail());
        $this->assertNull($requestTransfer->getContactIp());
        $this->assertNull($requestTransfer->getIdentificationShopperid());
    }

    public function testMapToCapture()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToCapture($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_CAPTURE, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->assertNull($requestTransfer->getNameGiven());
        $this->assertNull($requestTransfer->getNameFamily());
        $this->assertNull($requestTransfer->getNameTitle());
        $this->assertNull($requestTransfer->getNameSex());
        $this->assertNull($requestTransfer->getNameBirthdate());
        $this->assertNull($requestTransfer->getAddressCity());
        $this->assertNull($requestTransfer->getAddressCountry());
        $this->assertNull($requestTransfer->getAddressStreet());
        $this->assertNull($requestTransfer->getAddressZip());
        $this->assertNull($requestTransfer->getContactEmail());
        $this->assertNull($requestTransfer->getContactIp());
        $this->assertNull($requestTransfer->getIdentificationShopperid());
    }

    /**
     * @return PayolutionConfig
     */
    private function getBundleConfigMock()
    {
        return $this->getMock(
            'SprykerFeature\Zed\Payolution\PayolutionConfig',
            $methods = [],
            $arguments = [],
            $mockClassName = '',
            $callOriginalConstructor = false
        );
    }

    /**
     * @return SpyPaymentPayolution
     */
    private function getPaymentEntityMock()
    {
        $countryEntity = (new SpyCountry())
            ->setIso2Code('de');

        $addressEntity = (new SpySalesOrderAddress())
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setSalutation('Mrs')
            ->setEmail('jane@doe.com')
            ->setCountry($countryEntity)
            ->setCity('Berlin')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $orderEntityMock = $this->getMock(
            'SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder',
            $methods = [
                'getBillingAddress',
            ]
        );
        $orderEntityMock
            ->expects($this->any())
            ->method('getBillingAddress')
            ->will($this->returnValue($addressEntity));

        $paymentEntityMock = $this->getMock(
            'SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution',
            $methods = [
                'getSpySalesOrder',
            ]
        );
        $paymentEntityMock
            ->expects($this->any())
            ->method('getSpySalesOrder')
            ->will($this->returnValue($orderEntityMock));

        $paymentEntityMock
            ->setIdPaymentPayolution(1)
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setSalutation('Mr')
            ->setBirthdate('1970-01-01')
            ->setGender(SpyCustomerTableMap::COL_GENDER_FEMALE);

        return $paymentEntityMock;
    }

}
