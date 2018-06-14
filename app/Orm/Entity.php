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
	protected $meta;

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
	public function __construct( $meta ) {
		$this->meta = $meta;
	}

	/**
	 * Hydrate une entité avec les paramètres reçus
	 *
	 * @param $column
	 * @param $value
	 */
	private function hydrateProperty( $column, $value ) {

		$property = $column;
		$setter   = sprintf( "set%s", ucfirst( $property ) );

		if ( ! is_null( $value ) || $this->meta['columns'][ $column ]['type'] === 'datetime' ||
		     $this->meta['columns'][ $column ]['type'] === "boolean" ) {

			switch ( $this->meta['columns'][ $column ]['type'] ) {
				case "int":
					$this->$setter( (int) $value );
					break;
				case "string":
					$this->$setter( (string) $value );
					break;
				case "datetime":
					$date = new \DateTime( $value );
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
		foreach ( $this->meta['columns'] as $property => $params ) {
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