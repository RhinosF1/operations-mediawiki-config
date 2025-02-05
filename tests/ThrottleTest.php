<?php

class ThrottleTest extends PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider provideRules
	 */
	public function testThrottlingExceptionsKeys( $rule ) {
		$validParameters = self::getThrottlingExceptionsValidParameters();

		foreach ( $rule as $key => $value ) {
			$this->assertContains(
				$key, $validParameters,
				"Invalid parameter in a throttle rule detected: $key"
			);
		}
	}

	/**
	 * @dataProvider provideRules
	 */
	public function testIfThrottlingExceptionsContainTheRequiredParameters( $rule ) {
		$keys = array_keys( $rule );
		$this->assertContains( 'from', $keys, "Throttle rule required parameter missing: from" );
		$this->assertContains( 'to', $keys, "Throttle rule required parameter missing: to" );
	}

	/**
	 * @dataProvider provideRules
	 */
	public function testIfThrottlingExceptionsDontContainBothRangeAndIP( $rule ) {
		$keys = array_keys( $rule );
		$this->assertFalse(
			in_array( 'IP', $keys ) &&
			in_array( 'range', $keys ),
			"Throttle rules can apply to range(s) or IP(s) but not both"
		);
	}

	/**
	 * @dataProvider provideRules
	 * @depends testIfThrottlingExceptionsContainTheRequiredParameters
	 */
	public function testThrottlingExceptionsValues( $rule ) {
		foreach ( $rule as $key => $value ) {
			// Parses date
			// strtotime returns false when the string can't be parsed.
			$this->assertTrue(
				strtotime( $rule['from'] ),
				"Invalid value in a throttle rule detected: from should be a valid date"
			 );
			$this->assertTrue(
				strtotime( $rule['to'] ),
				"Invalid value in a throttle rule detected: to should be a valid date"
			);

			// Parses integer
			// We accept numeric integer and string representation of integers as digits.
			if ( array_key_exists( 'value', $rule ) ) {
				$this->assertTrue(
					is_int( $rule['value'] ) || ctype_digit( $rule['value'] ),
					"Invalid value in a throttle rule detected: range should be integer"
				);
			}

			// Parses IP and range
			// Should be a string or an array
			if ( array_key_exists( 'IP', $rule ) ) {
				$this->assertTrue(
					is_array( $rule['IP'] ) || is_string( $rule['IP'] ),
					"Invalid valid in a throttle rule detected: IP should be a string or an array"
				);
			}
			if ( array_key_exists( 'range', $rule ) ) {
				$this->assertTrue(
					is_array( $rule['range'] ) || is_string( $rule['range'] ),
					"Invalid valid in a throttle rule detected: range should be a string or an array"
				);
			}
		}
	}

	/**
	 * @dataProvider provideRules
	 */
	public function testOnlyExistingWikis( $rule ) {
		if ( array_key_exists( 'dbname', $rule ) ) {
			if ( is_array( $rule['dbname'] ) ) {
				foreach ( $rule['dbname'] as $dbname ) {
					$this->assertTrue(
						DBList::isInDblist( $dbname, "all" ),
						"Invalid value in a throtle rule detected: dbname $dbname doesn't match any existing wiki"
					);
				}
			} else {
				$dbname = $rule['dbname'];
				$this->assertTrue(
					DBList::isInDblist( $dbname, "all" ),
					"Invalid value in a throtle rule detected: dbname $dbname doesn't match any existing wiki"
				);
			}
		} else {
			// To stop jenkins from complaining about "risky tests", this is normal - if dbname isn't there, we have nothing to test
			$this->assertTrue( true );
		}
	}

	protected static function getThrottlingExceptionsValidParameters() {
		return [
			'from',
			'to',
			'IP',
			'range',
			'dbname',
			'value',
		];
	}

	public function provideRules() {
		require __DIR__ . '/../wmf-config/throttle.php';

		$rules = [];

		foreach ( $wmgThrottlingExceptions as $rule ) {
			$rules[] = [ $rule ];
		}

		return $rules;
	}

}
