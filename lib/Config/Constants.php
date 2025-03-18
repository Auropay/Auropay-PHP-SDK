<?php

namespace Auropay\Config;

class Constants {
	public const SANDBOX_URL = 'https://cdgw048sli.execute-api.ap-south-1.amazonaws.com/dev/';
	public const PRODUCTION_URL = 'https://secure-api.auropay.net/';
	public const UAT_URL = 'https://api.uat.auropay.net/';
	public const PAYMENTLINK_API = 'api/paymentlinks';
	public const REFUND_API = 'api/refunds';
	public const PAYMENTQRCODE_API = 'api/paymentqrcodes';
	public const PAYMENT_BY_REFNO = 'api/payments/refno/';
	public const PAYMENT_BY_TRANSACTION_ID = 'api/payments/';
	public const APP_DEBUG = true;
}
