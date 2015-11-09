<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Method;

interface ApiConstants
{
    
    const PAYMENT_CODE_PRE_AUTHORIZATION = 'VA.PA';
    const PAYMENT_CODE_RE_AUTHORIZATION = 'VA.PA';
    const PAYMENT_CODE_CAPTURE = 'VA.CP';
    const PAYMENT_CODE_REVERSAL = 'VA.RV';
    const PAYMENT_CODE_REFUND = 'VA.RF';

    const ACCOUNT_BRAND = 'ACCOUNT.BRAND';
    const BRAND_INVOICE = 'PAYOLUTION_INVOICE';
    const BRAND_INSTALLMENT = 'PAYOLUTION_INS';

    const TRANSACTION_MODE = 'TRANSACTION.MODE';
    const TRANSACTION_MODE_TEST = 'CONNECTOR_TEST';
    const TRANSACTION_MODE_LIVE = 'LIVE';
    const TRANSACTION_CHANNEL = 'TRANSACTION.CHANNEL';

    const SECURITY_SENDER = 'SECURITY.SENDER';
    const USER_LOGIN = 'USER.LOGIN';
    const USER_PWD = 'USER.PWD';

    const PRESENTATION_AMOUNT = 'PRESENTATION.AMOUNT';
    const PRESENTATION_USAGE = 'PRESENTATION.USAGE';
    const PRESENTATION_CURRENCY = 'PRESENTATION.CURRENCY';

    const IDENTIFICATION_TRANSACTIONID = 'IDENTIFICATION.TRANSACTIONID';
    const IDENTIFICATION_SHOPPERID = 'IDENTIFICATION.SHOPPERID';
    const IDENTIFICATION_REFERENCEID = 'IDENTIFICATION.REFERENCEID';

    const NAME_GIVEN = 'NAME.GIVEN';
    const NAME_FAMILY = 'NAME.FAMILY';
    const NAME_TITLE = 'NAME.TITLE';
    const NAME_SEX = 'NAME.SEX';
    const NAME_BIRTHDATE = 'NAME.BIRTHDATE';

    const SEX_MALE = 'M';
    const SEX_FEMALE = 'F';

    const ADDRESS_STREET = 'ADDRESS.STREET';
    const ADDRESS_ZIP = 'ADDRESS.ZIP';
    const ADDRESS_CITY = 'ADDRESS.CITY';
    const ADDRESS_COUNTRY = 'ADDRESS.COUNTRY';

    const CONTACT_EMAIL = 'CONTACT.EMAIL';
    const CONTACT_PHONE = 'CONTACT.PHONE';
    const CONTACT_MOBILE = 'CONTACT.MOBILE';
    const CONTACT_IP = 'CONTACT.IP';

    const PAYMENT_CODE = 'PAYMENT.CODE';

    const PAYMENT_CODE_PRE_CHECK = 'VA.PA';
    const PAYMENT_CODE_PRE_AUTHORIZATION = 'VA.PA';
    const PAYMENT_CODE_RE_AUTHORIZATION = 'VA.PA';
    const PAYMENT_CODE_CAPTURE = 'VA.CP';
    const PAYMENT_CODE_REVERSAL = 'VA.RV';
    const PAYMENT_CODE_REFUND = 'VA.RF';

    const TRANSACTION_REQUEST_CONTENT_TYPE = 'FORM';
    const CALCULATION_REQUEST_CONTENT_TYPE = 'XML';

    const STATUS_CODE_SUCCESS = '90';
    const REASON_CODE_SUCCESS = '00';
    const STATUS_REASON_CODE_SUCCESS = self::STATUS_CODE_SUCCESS . '.' . self::REASON_CODE_SUCCESS;

    /**
     * Analysis/Criteria keys
     */
    const CRITERION_CSS_PATH = 'PAYOLUTION_CSS_PATH';
    const CRITERION_REQUEST_SYSTEM_VENDOR = 'PAYOLUTION_REQUEST_SYSTEM_VENDOR';
    const CRITERION_REQUEST_SYSTEM_VERSION = 'PAYOLUTION_REQUEST_SYSTEM_VERSION';
    const CRITERION_REQUEST_TYPE = 'PAYOLUTION_REQUEST_TYPE';
    const CRITERION_MODULE_NAME = 'PAYOLUTION_MODULE_NAME';
    const CRITERION_MODULE_VERSION = 'PAYOLUTION_MODULE_VERSION';
    const CRITERION_SHIPPING_STREET = 'PAYOLUTION_SHIPPING_STREET';
    const CRITERION_SHIPPING_ZIP = 'PAYOLUTION_SHIPPING_ZIP';
    const CRITERION_SHIPPING_CITY = 'PAYOLUTION_SHIPPING_CITY';
    const CRITERION_SHIPPING_STATE = 'PAYOLUTION_SHIPPING_STATE';
    const CRITERION_SHIPPING_COUNTRY = 'PAYOLUTION_SHIPPING_COUNTRY';
    const CRITERION_SHIPPING_GIVEN = 'PAYOLUTION_SHIPPING_GIVEN';
    const CRITERION_SHIPPING_FAMILY = 'PAYOLUTION_SHIPPING_FAMILY';
    const CRITERION_SHIPPING_COMPANY = 'PAYOLUTION_SHIPPING_COMPANY';
    const CRITERION_SHIPPING_ADDITIONAL = 'PAYOLUTION_SHIPPING_ADDITIONAL';
    const CRITERION_SHIPPING_TYPE = 'PAYOLUTION_SHIPPING_TYPE';
    const CRITERION_TRANSPORTATION_COMPANY = 'PAYOLUTION_TRANSPORTATION_COMPANY';
    const CRITERION_TRANSPORTATION_TRACKING = 'PAYOLUTION_TRANSPORTATION_TRACKING';
    const CRITERION_TRANSPORTATION_RETURN_TRACKING = 'PAYOLUTION_TRANSPORTATION_RETURN_TRACKING';
    const CRITERION_ITEM_DESCR_XX = 'PAYOLUTION_DESCR_XX';
    const CRITERION_ITEM_PRICE_XX = 'PAYOLUTION_PRICE_XX';
    const CRITERION_ITEM_TAX_XX = 'PAYOLUTION_TAX_XX';
    const CRITERION_ITEM_CATEGORY_XX = 'PAYOLUTION_CATEGORY_XX';
    const CRITERION_TAX_AMOUNT = 'PAYOLUTION_TAX_AMOUNT';
    const CRITERION_PRE_CHECK = 'PAYOLUTION_PRE_CHECK';
    const CRITERION_PRE_CHECK_ID = 'PAYOLUTION_PRE_CHECK_ID';
    const CRITERION_TRX_TYPE = 'PAYOLUTION_TRX_TYPE';
    const CRITERION_COMPANY_NAME = 'PAYOLUTION_COMPANY_NAME';
    const CRITERION_COMPANY_UID = 'PAYOLUTION_COMPANY_UID';
    const CRITERION_COMPANY_TRADEREGISTRY_NUMBER = 'PAYOLUTION_COMPANY_TRADEREGISTRY_NUMBER';
    const CRITERION_INSTALLMENT_AMOUNT = 'PAYOLUTION_INSTALLMENT_AMOUNT';
    const CRITERION_DURATION = 'PAYOLUTION_DURATION';
    const CRITERION_ACCOUNT_COUNTRY = 'PAYOLUTION_ACCOUNT_COUNTRY';
    const CRITERION_ACCOUNT_HOLDER = 'PAYOLUTION_ACCOUNT_HOLDER';
    const CRITERION_ACCOUNT_BIC = 'PAYOLUTION_ACCOUNT_BIC';
    const CRITERION_ACCOUNT_IBAN = 'PAYOLUTION_ACCOUNT_IBAN';
    const CRITERION_CUSTOMER_LANGUAGE = 'PAYOLUTION_CUSTOMER_LANGUAGE';
    const CRITERION_CUSTOMER_NUMBER = 'PAYOLUTION_CUSTOMER_NUMBER';
    const CRITERION_CUSTOMER_GROUP = 'PAYOLUTION_CUSTOMER_GROUP';
    const CRITERION_CUSTOMER_CONFIRMED_ORDERS = 'PAYOLUTION_CUSTOMER_CONFIRMED_ORDERS';
    const CRITERION_CUSTOMER_CONFIRMED_AMOUNT = 'PAYOLUTION_CUSTOMER_CONFIRMED_AMOUNT';
    const CRITERION_CUSTOMER_INTERNAL_SCORE = 'PAYOLUTION_CUSTOMER_INTERNAL_SCORE';
    const CRITERION_WEBSHOP_URL = 'PAYOLUTION_WEBSHOP_URL';
    const CRITERION_SHIPPING_TYPE_BRANCH_PICKUP = 'BRANCH_PICKUP';
    const CRITERION_SHIPPING_TYPE_POST_OFFICE_PICKUP = 'POST_OFFICE_PICKUP';
    const CRITERION_SHIPPING_TYPE_PACK_STATION = 'PACK_STATION';

}
