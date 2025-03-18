<?php
/**
 * CreateRefundRequest
 *
 * @category Class
 * @package  Auropay
 * @author   Aurionpro Solutions
 * @link     https://auropay.net/
 */

namespace Auropay\Model;
use Auropay\Utils\ErrorCodeMapper;
use Auropay\Utils\ValidationFunctions;

class CreateRefundRequest {
	/**
	 * Container for storing property values
	 *
	 * @var array
	 */
	private $container = [];

	/**
	 * Gets refund amount
	 *
	 * @return float
	 */
	public function getRefundAmount() {
		return $this->container['Amount'];
	}

	/**
	 * Sets refund amount
	 *
	 * @param float $amount Amount to be refunded. Should be lesser than or equal to the transaction amount.
	 *
	 * @return self
	 * @throws \InvalidArgumentException if validation fails
	 */
	public function setRefundAmount( $amount ) {
		ValidationFunctions::emptyCheck( $amount, ErrorCodeMapper::getMessage( 'SE0018' ), 400 );
		if ( !is_numeric( $amount ) || $amount <= 0 ) {
			throw new \InvalidArgumentException( ErrorCodeMapper::getMessage( 'SE0002' ), 400 );
		}

		$this->container['Amount'] = $amount;
		return $this;
	}

	/**
	 * Gets refund remarks
	 *
	 * @return string|null
	 */
	public function getRefundRemarks() {
		return $this->container['Remarks'];
	}

	/**
	 * Sets refund remarks
	 *
	 * @param string|null $remark Remarks for the refund. Optional field.
	 *
	 * @return self
	 * @throws \InvalidArgumentException if validation fails
	 */
	public function setRefundRemarks( $remark ) {
		if ( is_null( $remark ) ) {
			$this->container['Remarks'] = $remark; // Allow null
			return $this;
		}

		ValidationFunctions::checkMaxCharLength(
			$remark,
			200,
			ErrorCodeMapper::getMessage( "SE0007" ));

		$this->container['Remarks'] = $remark;
		return $this;
	}

	/**
	 * Gets order ID
	 *
	 * @return string
	 */
	public function getOrderId() {
		return $this->container['OrderId'];
	}

	/**
	 * Sets order ID
	 *
	 * @param string $orderId Unique ID to associate the refund with.
	 *
	 * @return self
	 * @throws \InvalidArgumentException if validation fails
	 */
	public function setOrderId( $orderId ) {
		ValidationFunctions::emptyCheck( $orderId, ErrorCodeMapper::getMessage( 'SE0006' ), 400 );

		$this->container['OrderId'] = $orderId;
		return $this;
	}

	/**
	 * Converts the object to an associative array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->container;
	}

	/**
	 * Converts the object to a JSON string.
	 *
	 * @return string
	 */
	public function toJson() {
		return json_encode( $this->container, JSON_PRETTY_PRINT );
	}
}
