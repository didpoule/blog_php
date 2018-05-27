<?php

namespace App\Orm;

/**
 * Class Entity
 * @package App\Orm
 */
abstract class Entity {

	/**
	 * @return mixed
	 */
	public abstract static function getMeta();

	/**
	 * @param $datas
	 */
	public function hydrate( $datas ) {
		foreach ( $datas as $column => $value ) {
			try {
				$this->hydrateProperty( $column, $value );
			} catch (ORMException $e) {
				throw $e;
				return false;
			}
		}
		return $this;
	}

	/**
	 * @param $column
	 * @param $value
	 */
	private function hydrateProperty( $column, $value ) {

		$property = $column;
		$setter = sprintf("set%s", ucfirst($property));


		if (method_exists($this, $setter)) {
			switch ( $this::getMeta()['columns'][ $column ]['type'] ) {
				case "int":
					$this->$setter((int) $value);
					break;
				case "string":
					$this->$setter((string) $value);
					break;
				case "datetime":

					$date = \DateTime::createFromFormat("Y-m-d H:i:s", $value);

					$this->$setter($date);
					break;
				case "boolean":
					$this->$setter((boolean) $value);
					break;

			}
		} else {
			throw new ORMException( sprintf("Erreur: La propriété '%s' n'existe pas dans l'entité '%s'", $property, get_class($this)));
			return false;
		}
	}
}