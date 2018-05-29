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


		if ( method_exists( $this, $setter ) ) {

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
		} else {
			throw new ORMException( sprintf( "Erreur: La methode '%s' n'existe pas dans l'entité '%s'", $setter, get_class( $this ) ) );
		}
	}

}