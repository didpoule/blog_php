<?php

namespace App\Orm;

/**
 * Class Manager
 * @package App\Orm
 */
class Manager {

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var Entity
	 */
	private static $entity;

	/**
	 * @var Manager
	 */
	private static $manager;

	/**
	 * @var string
	 */
	protected static $file;

	/**
	 * Manager constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file ) {
		self::$file = $file;

		$database = Database::getInstance( $file );

		$this->pdo = $database->getPdo();
	}

	/**
	 * Récupère une ligne dans la base de donnée
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function fetch( $params = [] ) {
		$request   = sprintf( "SELECT * FROM %s %s LIMIT 0,1", self::$entity::getMeta()['name'], $this->where( $params ) );
		$statement = $this->pdo->prepare( $request );


		$statement->execute( $params );

		$result = $statement->fetch( \PDO::FETCH_ASSOC );


		return $result;
	}

	/**
	 * @param $params
	 *
	 * @return string
	 */
	private function where( $params ) {
		if ( ! empty( $params ) ) {
			foreach ( $params as $property => $value ) {
				$conditions[] = sprintf( "%s = :%s", key(self::$entity::getMeta()['columns']), $property );
			}

			return sprintf( "WHERE %s", implode( ' AND ', $conditions ) );

		}

		return "";
	}

	/**
	 * @param $entity
	 */
	public static function setEntity( $entity ) {
		self::$entity = $entity;
	}

	/**
	 * @return Manager
	 */
	public static function getManager() {
		if ( ! self::$manager ) {
			$manager       = self::$entity::getManager();
			self::$manager = new $manager( self::$file );
		}

		return self::$manager;
	}
}