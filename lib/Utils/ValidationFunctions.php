<?php

namespace Auropay\Utils;

/**
 * ValidationFunctions Class
 *
 * Provides static methods for common validation checks such as empty checks,
 * special character validation, maximum character length checks, and regex validation.
 */
class ValidationFunctions {

	/**
	 * Checks if a value is empty.
	 *
	 * @param string $value The value to be checked.
	 * @param string $errorMessage The error message to throw if the value is empty.
	 * @param int $serverErrorCode The server error code.
	 * @throws \InvalidArgumentException Will throw an exception if the value is empty.
	 */
	public static function emptyCheck( $value, $errorMessage, $serverErrorCode ) {
		if ( is_null( $value ) || empty( trim($value) ) ) {
			throw new \InvalidArgumentException( $errorMessage, $serverErrorCode );
		}
	}

	/**
	 * Validates if the value contains any special characters as per the provided regex.
	 *
	 * @param string $value The value to be validated.
	 * @param string $regex The regular expression to test against the value.
	 * @param string $errorMessage The error message to throw if validation fails.
	 * @throws \InvalidArgumentException Will throw an exception if the value contains special characters.
	 */
	public static function validateSpecialChar( $value, $regex, $errorMessage ) {
		if ( preg_match( $regex, $value ) ) {
			throw new \InvalidArgumentException( $errorMessage );
		}
	}

	/**
	 * Checks if the value exceeds the maximum allowed character length.
	 *
	 * @param string $value The value to be checked.
	 * @param int $maxLength The maximum allowed length.
	 * @param string $errorMessage The error message to throw if the value exceeds the limit.
	 * @throws \InvalidArgumentException Will throw an exception if the value exceeds the maximum character length.
	 */
	public static function checkMaxCharLength( $value, $maxLength, $errorMessage ) {
		if ( !empty( $value ) && mb_strlen( $value ) > $maxLength ) {
			throw new \InvalidArgumentException( $errorMessage );
		}
	}

	/**
	 * Validates the value against a given regular expression.
	 *
	 * @param string $value The value to be validated.
	 * @param string $regex The regular expression to validate against.
	 * @param string $errorMessage The error message to throw if validation fails.
	 * @throws \InvalidArgumentException Will throw an exception if the value does not match the regular expression.
	 */
	public static function regexValidator( $value, $regex, $errorMessage ) {
		if ( !preg_match( $regex, $value ) ) {
			throw new \InvalidArgumentException( $errorMessage );
		}
	}
}
