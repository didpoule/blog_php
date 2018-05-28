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
			$entity->hydrate( $result );
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

	/**
	 * @param $offset
	 * @param $limit
	 *
	 * @return string
	 */
	private function limit( $offset, $limit ) {
		if ( ! is_null( $offset ) || ! is_null( $limit ) ) {
			if ( is_null( $limit ) && ! is_null( $offset ) ) {
				$limit = sprintf( "LIMIT %s", $offset );

			} else {
				$limit = sprintf( "LIMIT %s,%s", $offset, $limit );
			}

			//$offset = sprintf( "OFFSET %s", $offset );

			return sprintf( "%s ", $limit );

		}

		return "";
	}

	/**
	 *
	 * @param $sort
	 *
	 * @return string
	 */
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
	 *
	 * @return string
	 */
	private function set( $entity ) {
		foreach ( $entity::getMeta()['columns'] as $property => $value ) {
			if ( $property != "id" ) {
				$getter = sprintf( "get%s", ucfirst( $property ) );
				if ( $entity->$getter() != null ) {
					$set[] = sprintf( "%s = :%s", $property, $property );
				}
			}
		}

		return sprintf( "SET %s", implode( ',', $set ) );
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


	/**
	 * @param $entity Entity
	 */
	public function update( $entity ) {
		$request = sprintf( "UPDATE %s %s WHERE id = :id", self::$entity::getMeta()['name'], $this->set( $entity ) );

		$statement = $this->pdo->prepare( $request );

		$params = $this->setParams( $entity );

		$statement->execute( $params );
	}

	/**
	 * Récupère les valeurs des propriétés de l'entité
	 *
	 * @param $entity
	 *
	 * @return array
	 */
	private function setParams( $entity ) {
		$params = [];

		foreach ( self::$entity::getMeta()['columns'] as $property => $value ) {
			$getter = sprintf( "get%s", ucfirst( $property ) );
			if ( $entity->$getter() != null ) {
				if ( $value['type'] === 'datetime' ) {
					$date                = $entity->$getter()->format( "Y-m-d-H-i-s" );
					$params[ $property ] = $date;
				} else {
					$params[ $property ] = $entity->$getter();

				}
			}
		}

		return $params;
	}

	/**
	 * Supression d'une entité de la base de données
	 *
	 * @param array $params
	 */
	private function remove( $params = [] ) {
		$request = sprintf( "DELETE FROM %s %s", self::$entity::getMeta()['name'], $this->where( $params ) );

		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );

	}

	/**
	 * @param $id
	 */
	public function delete( $id ) {
		$this->remove( [ "id" => $id ] );
	}


	/**
	 * Insertion d'une entité en base de données
	 * @param $entity
	 */
	public function insert( $entity ) {
		$request = sprintf( "INSERT INTO %s %s %s", self::$entity::getMeta()['name'], $this->stringProperties( $entity ), $this->stringProperties( $entity, true ) );

		$statement = $this->pdo->prepare( $request );

		$params = $this->setParams( $entity );

		$statement->execute( $params );
	}

	/**
	 * Retourne la liste des propriétés de l'entité en chaine de caractere
	 *
	 * @param Entity $entity
	 *
	 * @return string
	 */
	private function stringProperties( Entity $entity, $prepare = false ) {
		foreach ( self::$entity::getMeta()['columns'] as $property => $value ) {
			if ( $property != "id" ) {
				$getter = sprintf( "get%s", ucfirst( $property ) );

				if ( $entity->$getter() != null ) {
					if ( $prepare ) {
						$properties[] = sprintf( ":%s", $property );
					} else {
						$properties[] = $property;
					}
				}
			}
		}

		if ( $prepare ) {
			return sprintf( "VALUES (%s)", implode( ",", $properties ) );
		}

		return sprintf( "(%s)", implode( ",", $properties ) );
	}
}