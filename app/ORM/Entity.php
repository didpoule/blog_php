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

	/**
	 * @param $datas
	 *
	 * @return $this
	 */
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

		if ( ! is_null( $value ) || static::$meta['columns'][ $column ]['type'] === 'datetime' ||
		     static::$meta['columns'][ $column ]['type'] === "boolean" ) {
			switch ( static::$meta['columns'][ $column ]['type'] ) {
				case "int":
					$this->$setter( (int) $value );
					break;
				case "string":
					$this->$setter( (string) $value );
					break;
				case "datetime":
					$date = new \DateTime();
					$this->$setter( $date );
					break;
				case "boolean":
					$this->$setter( (boolean) $value );
					break;
			}
		}
	}

	/**
	 * Vérifie si les propriétés requises ne sont pas null
	 *
	 * @return mixed
	 */
	public function validate() {
		$errors = [];
		foreach ( static::$meta['columns'] as $property => $params ) {
			$getter = sprintf( "get%s", ucfirst( $property ) );

			if ( empty( $this->$getter() ) && $params['required'] === true ) {
				$errors[] = $property;
			}
		}

		if ( ! empty( $errors ) ) {
			return $errors;
		}

		return true;

	}

}