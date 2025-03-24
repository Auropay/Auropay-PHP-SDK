# Auropay SDK

The Auropay SDK provides a streamlined integration for merchants to interact with Auropay's payment APIs. This SDK abstracts complex API interactions, enabling merchants to focus on building great user experiences. It includes support for creating payment links, refunds, querying payment status, and generating payment QR codes.

---

## Features

- **Create Payment Link**: Simplify generating payment links with extensive customization options.
- **Create Refund**: Enable seamless refund processing for orders.
- **Get Payment Status by Transaction ID or Reference ID**: Retrieve transaction details easily.
- **Generate Payment QR Code**: Support for dynamic QR code generation for payments.
- **Secure and User-friendly**: Built with security and ease of use in mind.
- **Comprehensive Documentation**: Includes detailed examples and configuration guides for easy onboarding.

---

## Getting Started

### Requirements

**API Keys**: Access your API access key and secret key from the Auropay merchant dashboard.

```properties
API_ACCESS_KEY 	: "FF************************E3A8"
API_SECRET_KEY 	: "wqr0i2O******************************ANtO1TA="
```

---

### Installation

Add the Auropay SDK to your project by using following command

```php
composer require auropay/auropay
```
---

## Usage Examples

### 1. Initialize the SDK

```php
$auropay = new Auropay();
Auropay::$clientAccessKey = API_ACCESS_KEY;
Auropay::$clientSecretKey = API_SECRET_KEY;
Auropay::$clientEnvironment = = 'DEV';  // Set to 'DEV', 'UAT', or 'PROD'
```

---

### 2. Create Payment Link

**Method:** `createPaymentLink`

This method utilizes the settings provided in the `CreatePaymentRequest` to generate a payment link. The returned JSONObject contains details such as the payment link URL, expiration time, and other relevant metadata. Following methods can be utilized to create `CreatePaymentRequest` object and pass on to `createPaymentLink` method.

| **Methods**                        | **Data Types**             | **Details**                                                             |
| ---------------------------------- | -------------------------- | ----------------------------------------------------------------------- |
| `setExpireOn`                      | String                     | Time when the generated link will expire (e.g., "31-01-2025 23:59:59"). |
| `setAmount`                        | double                     | Transaction amount for the payment link.                                |
| `setCustomers`                     | Object<Customer>           | Requires Object of Customer details.                                    |
| `setInvoiceNumber` (optional)      | String                     | Custom invoice number to show on payment form details.                  |
| `setCallbackParameters`            | Object<CallbackParameters> | Requires callback object<br />;`                                        |
| `setShortDescription` (optional)   | String                     | Brief description of the payment.                                       |
| `setTitle`                         | String                     | Title for the payment link.                                             |
| `setSettings`                      | Object<Settings>           | Create a `Settings` object                                 |
| `setPaymentDescription` (optional) | String                     | Detailed description of the payment.                                    |

**Code Snippet:**

```php
try
{
	$customer_details = new \Auropay\Model\Customer( "John","Doe","9999999999","joh@asa.in");
	$callbackParams = new \Auropay\Model\CallbackParameters("https://www.google.com/","payBytestdata");
	$settings = new \Auropay\Model\Settings(false);
	$create_payment_link_request = new \Auropay\Model\CreatePaymentRequest();
	$create_payment_link_request->setTitle( "payBytestdata" );
	$create_payment_link_request->setAmount( 10.00 );
	$create_payment_link_request->setExpireOn( "26-11-2024 18:00:00" );
	$create_payment_link_request->setShortDescription( "Quality product" );
	$create_payment_link_request->setPaymentDescription( "Quality product" );
	$create_payment_link_request->setEnablePartialPayment( false );
	$create_payment_link_request->setEnableMultiplePayment( false );
	$create_payment_link_request->setDisplayReceipt( false );
	$create_payment_link_request->setCustomers( $customer_details );
	$create_payment_link_request->setCallbackParameters( $callbackParams );
	$create_payment_link_request->setSettings( $settings );
	$result = $auropay->createPaymentLink($create_payment_link_request );
	echo $result;
} catch ( \Exception $e ) {
	echo $e->getMessage();
}

```

### 3. Create Refund

**Method:** `createRefund`

| **Input Parameters** | **Data Types** | **Details**                                     |
| -------------------- | -------------- | ----------------------------------------------- |
| `setOrderId`         | String         | Unique identifier for the order to be refunded. |
| `setRefundAmount`    | decimal        | Amount to refund. Should be more then 1`        |
| `setRefundRemark` (optional)  | String         | Reason for the refund.                          |

**Code Snippet:**

```php
try {
	$create_refund_request = new \Auropay\Model\CreateRefundRequest();
	$create_refund_request->setOrderId( "c1416654-XXXX-XXXX-XXXX-XXXXXXX1333" );
	$create_refund_request->setRefundAmount( 100 );
	$create_refund_request->setRefundAmount( "Refund Reason" );
	$result = $auropay->createRefund($create_refund_request );
	echo $result;
} catch ( \Exception $e ) {
	echo $e->getMessage();
}
```

---

### 4. Get Payment Status by Transaction ID

**Method:** `getPaymentStatusByTransactionId`

| **Input Parameters** | **Data Types** | **Details**                            |
| -------------------- | -------------- | -------------------------------------- |
| `transactionId`      | String         | Unique identifier for the transaction. |

**Code Snippet:**

```php
try {
	$transactionId = "c1416654-XXXX-XXXX-XXXX-XXXXXXX1333";
	$result = $auropay->getPaymentStatusByTransactionId($transactionId );
	echo $result;
} catch ( \Exception $e ) {
	echo $e->getMessage();
}
```

### 4. Get Payment Status by Reference ID

**Method:** `getPaymentStatusByReferenceId`

| **Input Parameters** | **Data Types** | **Details**                            |
| -------------------- | -------------- | -------------------------------------- |
| `referenceId`        | String         | Unique identifier for the transaction. |

**Code Snippet:**

```php
try {
	$referenceId = "c1416654-XXXX-XXXX-XXXX-XXXXXXX1333";
	$result = $auropay->getPaymentStatusByReferenceId($referenceId );
	echo $result;
} catch ( \Exception $e ) {
	echo $e->getMessage();
}
```

---

### 5. Create Payment QR Code

**Method:** `createPaymentQRCode`

This method utilizes the settings provided in the `CreatePaymentRequest` to generate a payment link. The returned JSONObject contains details such as the payment link URL, expiration time, and other relevant metadata. Following methods can be utilized to create `CreatePaymentRequest` object and pass on to `createPaymentQRCode` method.

| **Methods**                        | **Data Types**             | **Details**                                                             |
| ---------------------------------- | -------------------------- | ----------------------------------------------------------------------- |
| `setExpireOn`                      | String                     | Time when the generated link will expire (e.g., "31-01-2025 23:59:59"). |
| `setAmount`                        | double                     | Transaction amount for the payment link.                                |
| `setCustomers`                     | Object<Customer>           | Requires Object of Customer details.                                    |
| `setInvoiceNumber` (optional)      | String                     | Custom invoice number to show on payment form details.                  |
| `setCallbackParameters`            | Object<CallbackParameters> | Requires callback object                                                |
| `setShortDescription` (optional)   | String                     | Brief description of the payment.                                       |
| `setTitle`                         | String                     | Title for the payment link.                                             |
| `setSettings`                      | Object<Settings>           | Create a `Settings` object                                  |
| `setPaymentDescription` (optional) | String                     | Detailed description of the payment.                                    |

**Code Snippet:**

```php
try
{
	$customer_details = new \Auropay\Model\Customer( "John","Doe","9999999999","joh@asa.in");
	$callbackParams = new \Auropay\Model\CallbackParameters("https://www.google.com/","payBytestdata");
	$settings = new \Auropay\Model\Settings(false);
	$create_qrcode_request = new \Auropay\Model\CreatePaymentRequest();
	$create_qrcode_request->setTitle( "payBytestdata11" );
	$create_qrcode_request->setAmount( 100 );
	$create_qrcode_request->setExpireOn( "28-12-2024 18:00:00" );
	$create_qrcode_request->setShortDescription( "Quality product" );
	$create_qrcode_request->setPaymentDescription( "This product is very nice" );
	$create_qrcode_request->setEnablePartialPayment( false );
	$create_qrcode_request->setEnableMultiplePayment( false );
	$create_qrcode_request->setDisplayReceipt( false );
	$create_qrcode_request->setCustomers( $customer_details );
	$create_qrcode_request->setCallbackParameters( $callbackParams );
	$create_qrcode_request->setSettings( $settings );
	$result = $auropay->createPaymentQRCode($create_qrcode_request );
	echo $result;
} catch ( \Exception $e ) {
	echo $e->getMessage();
}
```

---

## License

Distributed under the Unlicense License. See [LICENSE](https://github.com/Auropay/Auropay-PHP-SDK/blob/main/LICENSE) for more information.

<br />
<br />
````
