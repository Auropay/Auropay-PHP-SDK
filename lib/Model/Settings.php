<?php

namespace Auropay\Model;

/**
 * Settings Class
 *
 * Represents configuration settings related to display, currency, and commission.
 */
class Settings {
	private bool $displaySummary;

	/**
	 * Initializes a new instance of the Settings class.
	 *
	 * @param bool $displaySummary Determines whether to display the summary.
	 */
	public function __construct( bool $displaySummary ) {
		$this->displaySummary = $displaySummary;
	}

	/**
	 * Converts the Settings instance to an associative array.
	 *
	 * @return array Array representation of the settings.
	 */
	public function toArray(): array {
		return [
			'displaySummary' => $this->displaySummary
		];
	}
}
