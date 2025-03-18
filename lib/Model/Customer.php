<?php

namespace Auropay\Model;

/**
 * Customer Class
 *
 * Represents a customer with personal contact details.
 */
class Customer {
	private string $firstName;
	private string $lastName;
	private string $phone;
	private string $email;

	/**
	 * Initializes a new instance of the Customer class.
	 *
	 * @param string $firstName Customer's first name.
	 * @param string $lastName Customer's last name.
	 * @param string $phone Customer's phone number.
	 * @param string $email Customer's email address.
	 */
	public function __construct( string $firstName, string $lastName, string $phone, string $email ) {
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->phone = $phone;
		$this->email = $email;
	}

	/**
	 * Converts the Customer instance to an associative array.
	 *
	 * @return array Array representation of the customer.
	 */
	public function toArray() {
		return [
			'firstName' => trim($this->firstName),
			'lastName' => trim($this->lastName),
			'phone' => trim($this->phone),
			'email' => trim($this->email),
		];
	}
}
