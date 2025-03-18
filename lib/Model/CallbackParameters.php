<?php

namespace Auropay\Model;

/**
 * CallbackParameters Class
 *
 * Represents the parameters required for API callbacks, including the API URL and reference number.
 */
class CallbackParameters {
	private string $callbackApiUrl;
	private string $referenceNumber;

	/**
	 * Initializes a new instance of the CallbackParameters class.
	 *
	 * @param string $callbackApiUrl The URL to which the callback should be sent.
	 * @param string $referenceNumber A unique reference number associated with the callback.
	 */
	public function __construct( string $callbackApiUrl, string $referenceNumber ) {
		$this->callbackApiUrl = $callbackApiUrl;
		$this->referenceNumber = $referenceNumber;
	}

	/**
	 * Converts the CallbackParameters instance to an associative array.
	 *
	 * @return array toArray representation of the callback parameters.
	 */
	public function toArray(): array {
		return [
			'callbackApiUrl' => trim($this->callbackApiUrl),
			'referenceNo' => trim($this->referenceNumber),
		];
	}
}
