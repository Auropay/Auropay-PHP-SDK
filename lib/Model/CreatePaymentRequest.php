<?php

/**
 * CreatePaymentRequest
 *
 * @category Class
 * @package  Auropay
 * @author   Aurionpro
 * @link     https://auropay.net/
 */

namespace Auropay\Model;

use Auropay\Utils\ErrorCodeMapper;
use Auropay\Utils\ValidationFunctions;

class CreatePaymentRequest
{
    /**
     * Container for storing property values
     *
     * @var array
     */
    private $container = [];
    // Define regular expression constants
    const SPECIAL_CHAR_REGEX = '/[^a-zA-Z0-9 ]/'; // Regular expression for special characters
    const NAME_REGEX        = '/^[a-zA-Z\s]{1,70}$/';
    const PHONE_REGEX        = '/^\d{10,15}$/';
    const EMAIL_REGEX        = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    const CALLBACK_API_URL_REGEX = '/^(https?:\/\/)(www\.)?[a-zA-Z0-9@:%._\+~#?&\/=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%._\+~#?&\/=]*)$/';
    /**
     * Sets the title
     *
     * @param string $title The title of the payment link.
     *
     * @return self
     * @throws \InvalidArgumentException if validation fails
     */
    public function setTitle($title)
    {
        ValidationFunctions::emptyCheck($title, ErrorCodeMapper::getMessage('SE0003'), 400);
        ValidationFunctions::validateSpecialChar(
            $title,
            self::SPECIAL_CHAR_REGEX,
            ErrorCodeMapper::getMessage("SE0020")
        );

        ValidationFunctions::checkMaxCharLength(
            $title,
            50,
            ErrorCodeMapper::getMessage("SE0019")
        );
        $this->container['title'] = trim($title);
        return $this;
    }

    /**
     * Gets the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->container['title'] ?? null;
    }

    /**
     * Sets the amount
     *
     * @param float $amount The payment amount.
     *
     * @return self
     * @throws \InvalidArgumentException if validation fails
     */
    public function setAmount($amount)
    {

        ValidationFunctions::emptyCheck($amount, ErrorCodeMapper::getMessage('SE0018'), 400);
        if (! is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0002'), 400);
        }

        $this->container['amount'] = $amount;
        return $this;
    }

    /**
     * Gets the amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'] ?? null;
    }

    /**
     * Sets the short description
     *
     * @param string|null $shortDescription Short description for the payment link.
     *
     * @return self
     */
    public function setShortDescription($shortDescription)
    {
        ValidationFunctions::checkMaxCharLength(
            $shortDescription,
            1000,
            ErrorCodeMapper::getMessage("SE0021")
        );
        ValidationFunctions::validateSpecialChar(
            $shortDescription,
            self::SPECIAL_CHAR_REGEX,
            ErrorCodeMapper::getMessage("SE0022")
        );
        $this->container['shortDescription'] = trim($shortDescription);
        return $this;
    }

    /**
     * Gets the short description
     *
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->container['shortDescription'] ?? null;
    }

    /**
     * Sets the payment description
     *
     * @param string|null $paymentDescription Description for the payment.
     *
     * @return self
     */
    public function setPaymentDescription($paymentDescription)
    {
        ValidationFunctions::checkMaxCharLength(
            $paymentDescription,
            1000,
            ErrorCodeMapper::getMessage("SE0004")
        );
        ValidationFunctions::validateSpecialChar(
            $paymentDescription,
            self::SPECIAL_CHAR_REGEX,
            ErrorCodeMapper::getMessage("SE0023")
        );
        $this->container['paymentDescription'] = trim($paymentDescription);
        return $this;
    }

    /**
     * Gets the payment description
     *
     * @return string|null
     */
    public function getPaymentDescription()
    {
        return $this->container['paymentDescription'] ?? null;
    }

    /**
     * Sets enablePartialPayment
     *
     * @param bool $enablePartialPayment Enable or disable partial payment.
     *
     * @return self
     */
    public function setEnablePartialPayment(bool $enablePartialPayment)
    {
        $this->container['enablePartialPayment'] = $enablePartialPayment;
        return $this;
    }

    /**
     * Gets enablePartialPayment
     *
     * @return bool
     */
    public function getEnablePartialPayment()
    {
        return $this->container['enablePartialPayment'] ?? false;
    }

    /**
     * Sets enableMultiplePayment
     *
     * @param bool $enableMultiplePayment Enable or disable multiple payment options.
     *
     * @return self
     */
    public function setEnableMultiplePayment(bool $enableMultiplePayment)
    {
        $this->container['enableMultiplePayment'] = $enableMultiplePayment;
        return $this;
    }

    /**
     * Gets enableMultiplePayment
     *
     * @return bool
     */
    public function getEnableMultiplePayment()
    {
        return $this->container['enableMultiplePayment'] ?? false;
    }

    /**
     * Sets displayReceipt
     *
     * @param bool $displayReceipt Enable or disable receipt display.
     *
     * @return self
     */
    public function setDisplayReceipt(bool $displayReceipt)
    {
        $this->container['displayReceipt'] = $displayReceipt;
        return $this;
    }

    /**
     * Gets displayReceipt
     *
     * @return bool
     */
    public function getDisplayReceipt()
    {
        return $this->container['displayReceipt'] ?? false;
    }

    /**
     * Sets the expiry date and time
     *
     * @param string $expireOn Expiry date and time in "d-m-Y H:i:s" format.
     *
     * @return self
     * @throws \InvalidArgumentException if validation fails
     */
    public function setExpireOn($expireOn)
    {
        ValidationFunctions::regexValidator(
            $expireOn,
            '/^(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2}):(\d{2})$/',
            ErrorCodeMapper::getMessage("SE0001")
        );
        $expireTimestamp = strtotime($expireOn);

        // Get current timestamp
        $currentTimestamp = time();
        // Check if the date is in the past
        if ($expireTimestamp < $currentTimestamp) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage("SE0024"), 400); // Past date not allowed
        }

        $this->container['expireOn'] = $expireOn;
        return $this;
    }

    /**
     * Gets the expiry date and time
     *
     * @return string
     */
    public function getExpireOn()
    {
        return $this->container['expireOn'] ?? null;
    }

    /**
     * Sets customers
     *
     * @param array $customers List of customers with email and phone.
     *
     * @return self
     * @throws \InvalidArgumentException if validation fails
     */
    public function setCustomers($customerDetails)
    {
        // Check if customerDetails is empty
        if (empty($customerDetails)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage("SE0032"), 400);
        }

        if ($customerDetails instanceof \Auropay\Model\Customer) {
            // Convert to array
            $customerDetails = [$customerDetails->toArray()];
        }

        // Check if it's an array
        if (! is_array($customerDetails)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage("SE0032"), 400);
        }

        // Loop through each customer and validate fields
        foreach ($customerDetails as $customer) {
            ValidationFunctions::emptyCheck(
                $customer['firstName'] ?? null,
                ErrorCodeMapper::getMessage("SE0010"),
                400
            );

            ValidationFunctions::regexValidator(
                $customer['firstName'],
                self::NAME_REGEX,
                ErrorCodeMapper::getMessage("SE0034")
            );

            ValidationFunctions::emptyCheck(
                $customer['lastName'] ?? null,
                ErrorCodeMapper::getMessage("SE0011"),
                400
            );

            ValidationFunctions::regexValidator(
                $customer['lastName'],
                self::NAME_REGEX,
                ErrorCodeMapper::getMessage("SE0035")
            );

            ValidationFunctions::emptyCheck(
                $customer['phone'] ?? null,
                ErrorCodeMapper::getMessage("SE0028"),
                400
            );

            ValidationFunctions::regexValidator(
                $customer['phone'],
                self::PHONE_REGEX,
                ErrorCodeMapper::getMessage("SE0012"),
                400
            );

            ValidationFunctions::emptyCheck(
                $customer['email'] ?? null,
                ErrorCodeMapper::getMessage("SE0027"),
                400
            );

            ValidationFunctions::checkMaxCharLength(
                $customer['email'],
                320,
                ErrorCodeMapper::getMessage("SE0026"),
                400
            );

            ValidationFunctions::regexValidator(
                $customer['email'],
                self::EMAIL_REGEX,
                ErrorCodeMapper::getMessage("SE0013"),
                400
            );
        }

        $this->container['Customers'] = $customerDetails;
        return $this;
    }

    /**
     * Gets customers
     *
     * @return array
     */
    public function getCustomers()
    {
        return $this->container['Customers'] ?? [];
    }

    /**
     * Sets callback parameters
     *
     * @param array $callbackParameters Callback details including reference and API URL.
     *
     * @return self
     */
    public function setCallbackParameters($callbackParameters)
    {
        // Check if callbackParameters is empty
        if (empty($callbackParameters)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage("SE0005"), 400);
        }

        // Convert object to array if it's an instance of CallbackParameters
        if ($callbackParameters instanceof \Auropay\Model\CallbackParameters) {
            $callbackParameters = $callbackParameters->toArray(); // Assuming `toArray()` method exists
        }

        // Validate callbackApiUrl
        ValidationFunctions::emptyCheck(
            $callbackParameters['callbackApiUrl'] ?? null,
            ErrorCodeMapper::getMessage("SE0031"),
            400
        );

        ValidationFunctions::regexValidator(
            $callbackParameters['callbackApiUrl'],
            self::CALLBACK_API_URL_REGEX,
            ErrorCodeMapper::getMessage("SE0014"),
            400
        );

        ValidationFunctions::checkMaxCharLength(
            $callbackParameters['callbackApiUrl'],
            2048,
            ErrorCodeMapper::getMessage("SE0030"),
            400
        );

        // Validate referenceNumber
        ValidationFunctions::checkMaxCharLength(
            $callbackParameters['referenceNo'] ?? null,
            50,
            ErrorCodeMapper::getMessage("SE0029"),
            400
        );
        $this->container['CallbackParameters'] = $callbackParameters;
        return $this;
    }

    /**
     * Gets callback parameters
     *
     * @return array
     */
    public function getCallbackParameters()
    {
        return $this->container['CallbackParameters'] ?? [];
    }

    /**
     * Sets settings
     *
     * @param array $settings Settings for the QR code generation.
     *
     * @return self
     */
    public function setSettings($settings)
    {
        if (empty($settings)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage("SE0033"), 400);
        }

        if ($settings instanceof \Auropay\Model\Settings) {
            // Convert to array
            $settings = $settings->toArray();
        }

        if (! is_array($settings)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0019'), 400);
        }
        $this->container['Settings'] = $settings;
        return $this;
    }

    /**
     * Gets settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->container['Settings'] ?? [];
    }

    /**
     * Converts the object to an associative array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->container;
    }
}
