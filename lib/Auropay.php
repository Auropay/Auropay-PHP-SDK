<?php

/**
 * Configuration
 *
 * @category Class
 * @package  Auropay
 * @author   Aurionpro
 * @link     https://auropay.net/
 */

namespace Auropay;

require_once __DIR__ . '/../vendor/autoload.php';

use Auropay\Config\Constants;
use Auropay\Utils\ErrorCodeMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class Auropay
{
    private string $apiBaseUrl;
    private Client $client;
    public static $clientAccessKey     = "";
    public static $clientSecretKey     = "";
    public static $clientEnvironment   = "DEV";
    private static $apiVersionsMapping = [
        "paymentlink"     => "1.0",
        "paymentqrcodes"  => "1.0",
        "refunds"         => "1.0",
        "statusbyrefid"   => "1.0",
        "statusbytransid" => "1.0",
    ];

    /**
     * Constructor to initialize the API base URL based on the environment.
     */
    public function __construct()
    {
        // Check if Auropay.clientEnvironment is not set or is not one of the valid values
        if (null === Auropay::$clientEnvironment || ! in_array(Auropay::$clientEnvironment, ['DEV', 'UAT', 'PROD'])) {
            $clientEnvError = [
                "error_code" => "400",
                "message" => "Invalid or missing value for Auropay.clientEnvironment. It should be 'DEV', 'UAT', or 'PROD'."
            ];

            throw new \InvalidArgumentException(json_encode($clientEnvError));
        }

        // Set the apiBaseUrl based on the clientEnvironment value
        switch (Auropay::$clientEnvironment) {
            case 'PROD':
                $this->apiBaseUrl = Constants::PRODUCTION_URL;
                break;
            case 'UAT':
                $this->apiBaseUrl = Constants::UAT_URL;
                break;
            default:
                $this->apiBaseUrl = Constants::SANDBOX_URL; // Default to DEV if it's neither PROD nor UAT
                break;
        }
        $this->client = new Client();
    }

    /**
     * Get the API version for a given method
     *
     * @param string $service Api method:
     *
     * @return string Response from the API Version.
     */
    private function getApiVersionForService($service): string
    {
        return self::$apiVersionsMapping[$service] ?? "1.0";
    }

    /**
     * Create Payment Link
     *
     * @param array $create_paymentlink_request {
     *      @type string $title                Name of item sold. Should be unique for each request.
     *      @type double $amount               Transaction amount.
     *      @type string $shortDescription     (Optional) Short description of the item sold.
     *      @type string $paymentDescription   (Optional) Detailed description of the item sold.
     *      @type string $expireOn             Expiration time in 'DD-MM-YYYY hh:mm:ss' format.
     *      @type array $Customers             Customer details (array of email, phone).
     *      @type array $CallbackParameters    Contains ReferenceNo, ReferenceType, and CallbackApiUrl.
     *      @type bool $displaySummary         (Optional) Show payer details on payment form.
     *      @type array $Settings              Additional custom settings.
     * }
     *
     * @return string Payment Link
     */
    public function createPaymentLink($data): array | string
    {
        if (empty($data)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0030'), 400);
        }
        $apiVersion = $this->getApiVersionForService("paymentlink");
        $requestArr = $data->toArray();
        $this->addDefaultSettings($requestArr);
        return $this->connect($apiVersion, 'POST', Constants::PAYMENTLINK_API, $requestArr);
    }

    /**
     * Create a Refund
     *
     * @param array $data Refund request parameters:
     * - Includes user-defined refund details.
     *
     * @return array|string Response from the API.
     * @throws \InvalidArgumentException if $data is missing or invalid.
     */
    public function createRefund($data): array | string
    {
        if (empty($data)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0029'), 400);
        }
        $apiVersion             = $this->getApiVersionForService("refunds");
        $requestArr             = $data->toArray();
        $requestArr["UserType"] = 1;
        return $this->connect($apiVersion, 'POST', Constants::REFUND_API, $requestArr);
    }

    /**
     * Retrieve Payment Status by Transaction ID
     *
     * @param string $transactionId Transaction ID.
     * @return array|string Response from the API.
     * @throws \InvalidArgumentException if $transactionId is empty.
     */
    public function getPaymentStatusByTransactionId(string $transactionId): array | string
    {
        if (empty(trim($transactionId))) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0008'), 400);
        }
        $apiVersion = $this->getApiVersionForService("statusbytransid");
        return $this->connect($apiVersion, 'GET', Constants::PAYMENT_BY_TRANSACTION_ID . "{$transactionId}");
    }

    /**
     * Retrieve Payment Status by Reference ID
     *
     * @param string $referenceId Reference ID.
     * @return array|string Response from the API.
     * @throws \InvalidArgumentException if $referenceId is empty.
     */
    public function getPaymentStatusByReferenceId(string $referenceId): array | string
    {
        if (empty(trim($referenceId))) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0015'), 400);
        }
        $apiVersion = $this->getApiVersionForService("statusbyrefid");
        return $this->connect($apiVersion, 'GET', Constants::PAYMENT_BY_REFNO . "{$referenceId}");
    }

    /**
     * Generate a Payment QR Code
     *
     * @param array $data {
     *      @type string $title                Name of item sold. Should be unique for each request.
     *      @type double $amount               Transaction amount.
     *      @type string $shortDescription     (Optional) Short description of the item sold.
     *      @type string $paymentDescription   (Optional) Detailed description of the item sold.
     *      @type string $expireOn             Expiration time in 'DD-MM-YYYY hh:mm:ss' format.
     *      @type array $Customers             Customer details (array of email, phone).
     *      @type array $CallbackParameters    Contains ReferenceNo, ReferenceType, and CallbackApiUrl.
     *      @type bool $displaySummary         (Optional) Show payer details on payment form.
     *      @type array $Settings              Additional custom settings.
     * }
     *
     * @return string QR code URL for payment
     */
    public function createPaymentQRCode($data): array | string
    {
        if (empty($data)) {
            throw new \InvalidArgumentException(ErrorCodeMapper::getMessage('SE0017'), 400);
        }
        $apiVersion = $this->getApiVersionForService("paymentqrcodes");
        $requestArr = $data->toArray();
        $this->addDefaultSettings($requestArr);
        return $this->connect($apiVersion, 'POST', Constants::PAYMENTQRCODE_API, $requestArr);
    }

    /**
     * Adds default settings to the request array.
     *
     * @param array $requestArr The request array to modify
     * @return void
     */
    private function addDefaultSettings(array &$requestArr): void
    {
        $requestArr["ResponseType"]     = 1;
        $requestArr["enableProtection"] = false;
    }

    /**
     * General method for making API requests
     * @param string $method - HTTP method (GET, POST)
     * @param string $endpoint - API endpoint to call
     * @param array|null $create_qrcode_request - Optional create_qrcode_request for POST requests
     * @return array|string - API response or error message on failure
     */
    private function connect($apiVersion, string $method, string $endpoint, $data = null): array | string
    {
        $headers = [];
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (null !== Auropay::$clientAccessKey) {
            $headers['x-access-key'] = Auropay::$clientAccessKey;
        }

        if (null !== Auropay::$clientSecretKey) {
            $headers['x-secret-key'] = Auropay::$clientSecretKey;
        }

        $headers['x-version'] = $apiVersion;
        $options              = ['headers' => $headers];

        if ('POST' === $method && $data) {
            $options['json'] = $data;
        } elseif ('GET' === $method && $data) {
            $options['query'] = $data;
        }

        try {
            $apiUrl     = $this->apiBaseUrl . $endpoint;
            $response   = $this->client->request($method, $apiUrl, $options);
            $statusCode = $response->getStatusCode();
            return in_array($statusCode, [200, 201, 204]) ? $response->getBody()->getContents() : $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $response     = json_decode($responseBody, true);
            }

            return json_encode([
                'error_code' => $e->getCode(),
                'message'    => ! empty($response['message']) ? $response['message'] : "Not Found",
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return json_encode([
                'error_code' => $e->getCode(),
                'message'    => $e->getMessage(),
            ], JSON_PRETTY_PRINT);
        }
    }
}
