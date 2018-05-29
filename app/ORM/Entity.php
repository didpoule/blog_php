<?php

namespace App\Orm;

/**
 * Class Entity
 * @package App\Orm
 */
abstract class Entity {

	/**
	 * @var array
	 */
	protected static $meta;

	/**
	 * @param $datas
	 */

	public static function setMeta( $meta ) {
		static::$meta = $meta;
	}

	public function hydrate( $datas ) {
		foreach ( $datas as $column => $value ) {
			$this->hydrateProperty( $column, $value );
		}

		return $this;
	}

	/**
	 * @param $column
	 * @param $value
	 */
	private function hydrateProperty( $column, $value ) {

		$property = $column;
		$setter   = sprintf( "set%s", ucfirst( $property ) );

		switch ( static::$meta['columns'][ $column ]['type'] ) {
			case "int":
				$this->$setter( (int) $value );
				break;
			case "string":
				$this->$setter( (string) $value );
				break;
			case "datetime":

				$date = \DateTime::createFromFormat( "Y-m-d H:i:s", $value );

				$this->$setter( $date );
				break;
			case "boolean":
				$this->$setter( (boolean) $value );
				break;
		}
	}

	/**
	 * Vérifie si les propriétés requises ne sont pas null
	 *
	 * @return bool
	 */
	public function validate() {

		foreach ( static::$meta['columns'] as $property => $params ) {
			$getter = sprintf( "get%s", ucfirst( $property ) );

			if ( $this->$getter() === null && $params['required'] === true ) {
				return false;
			}
		}

		return true;

	}

}