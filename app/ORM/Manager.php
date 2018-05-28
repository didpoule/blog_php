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
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function fetch( $params = [] ) {
		$request   = sprintf( "SELECT * FROM %s %s LIMIT 0,1", self::$entity::getMeta()['name'], $this->where( $params ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );

		$result = $statement->fetch( \PDO::FETCH_ASSOC );

		$entity = new self::$entity();

		try {
			$entity->hydrate();
		} catch ( ORMException $e ) {
			die ( $e->getMessage() );
		}


		return $entity;
	}


	/**
	 * @param array $params
	 */
	public function fetchAll( $params = [], $offset = null, $limit = null, $sort = [] ) {
		$request   = sprintf( "SELECT * FROM %s %s %s %s", self::$entity::getMeta()['name'], $this->where( $params ), $this->order( $sort ), $this->limit( $offset, $limit ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );

		$results = $statement->fetchAll( \PDO::FETCH_ASSOC );
		try {

			foreach ( $results as $result ) {
				$entity = new self::$entity();
				$entity->hydrate( $result );
				$entities[] = $entity;
			}

			return $entities;
		} catch ( ORMException $e ) {
			die( $e->getMessage() );
		}
	}

	/**
	 * @param $params
	 *
	 * @return string
	 */
	private function where( $params ) {
		if ( ! empty( $params ) ) {
			foreach ( $params as $property => $value ) {
				$conditions[] = sprintf( "%s = :%s", key( self::$entity::getMeta()['columns'] ), $property );
			}

			return sprintf( "WHERE %s", implode( ' AND ', $conditions ) );

		}

		return "";
	}

	private function limit( $offset, $limit ) {
		$limit = sprintf( "LIMIT %s,%s", $offset, $limit );

		//$offset = sprintf( "OFFSET %s", $offset );

		return sprintf( "%s ", $limit );

	}

	private function order( $sort ) {
		if ( ! is_null( $sort ) ) {
			$order = [];
			foreach ( $sort as $property => $value ) {
				$order[] = sprintf( "%s %s", $property, $value );

				return sprintf( "ORDER BY %s", implode( ',', $order ) );
			}
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

	public function find( $id ) {
		return $this->fetch( [ 'id' => $id ] );
	}

	public function findAll() {
		return $this->fetchAll();
	}
}