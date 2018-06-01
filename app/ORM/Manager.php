<?php

namespace App\Orm;

/**
 * Class Manager
 * @package App\Orm
 */
abstract class Manager {

	/**
	 * @var Database
	 */
	protected $database;

	/**
	 * @var \PDO
	 */
	protected $pdo;

	/**
	 * @var string
	 */
	protected static $entity;

	/**
	 * @var array
	 */
	protected static $meta;

	/**
	 * Manager constructor.
	 *
	 * @param $entity
	 * @param $meta
	 */
	public function __construct( Database $database, $entity, $meta ) {
		$this->database = $database;
		$this->pdo = $database->getPdo();
		static::$entity = $entity;
		static::$meta   = $meta;
	}

	/**
	 * Récupère une ligne dans la base de donnée
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function fetch( $params = [] ) {

		$request = sprintf( "SELECT * FROM  %s %s LIMIT 0,1", static::$meta['name'], $this->where( $params ) );

		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );


		$result = $statement->fetch( \PDO::FETCH_ASSOC );


		if ( $result ) {
			$entity = new static::$entity();
			$entity->hydrate( $result );

			return $entity;
		}

		return false;
	}


	/**
	 * @param array $params
	 */
	public function fetchAll( $params = [], $offset = null, $limit = null, $sort = [] ) {

		$request   = sprintf( "SELECT * FROM %s %s %s %s", static::$meta['name'], $this->limit( $offset, $limit ), $this->where( $params ), $this->order( $sort ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );

		$results = $statement->fetchAll( \PDO::FETCH_ASSOC );

		if ( $results ) {


			foreach ( $results as $result ) {
				$entity = new static::$entity();
				try {
					$entity->hydrate( $result );
				} catch ( ORMException $e ) {
					die( $e->getMessage() );
				}
				$entities[] = $entity;
			}

			return $entities;
		}

		return false;
	}


	/**
	 * Génère le WHERE de la requête préparée
	 *
	 * @param $params
	 *
	 * @return string
	 */
	private function where( $params ) {
		if ( ! empty( $params ) ) {

			foreach ( $params as $property => $value ) {
				$conditions[] = sprintf( "%s = :%s", $property, $property );
			}

			return sprintf( "WHERE %s", implode( ' AND ', $conditions ) );

		}

		return "";
	}

	/**
	 * Genre la LIMIT de la requête préparée
	 *
	 * @param $offset
	 * @param $limit
	 *
	 * @return string
	 */
	private function limit( $offset, $limit ) {
		if ( ! is_null( $offset ) || ! is_null( $limit ) ) {
			// Si on a pas défini de limite
			if ( is_null( $limit ) && ! is_null( $offset ) ) {
				$limit = sprintf( "LIMIT %s", $offset );

			} else {
				$limit = sprintf( "LIMIT %s,%s", $offset, $limit );
			}

			return sprintf( "%s ", $limit );

		}

		return "";
	}

	/**
	 * Géneère le ORDER de la requête préparée
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
		foreach ( self::$meta['columns'] as $property => $value ) {
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

		if ( self::$manager ) {
			$manager       = self::$entity::getManager();
			self::$manager = new $manager( self::$file );
		}
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
		if ( $entity->validate() ) {

			$request = sprintf( "UPDATE %s %s WHERE id = :id", self::$meta['name'], $this->set( $entity ) );

			$statement = $this->pdo->prepare( $request );

			$params = $this->setParams( $entity );

			return $statement->execute( $params );

		}

		return false;
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

		foreach ( self::$meta['columns'] as $property => $value ) {
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
		$request = sprintf( "DELETE FROM %s %s", self::$meta['name'], $this->where( $params ) );

		$statement = $this->pdo->prepare( $request );

		$result = $statement->execute( $params );

		$statement->closeCursor();

		return $result;

	}

	/**
	 * @param $id
	 */
	public function delete( $id ) {
		return $this->remove( [ "id" => $id ] );
	}


	/**
	 * Insertion d'une entité en base de données
	 *
	 * @param $entity
	 */
	public function insert( $entity ) {
		if ( $entity->validate() ) {

			$request = sprintf( "INSERT INTO %s %s %s", self::$meta['name'], $this->stringProperties( $entity ), $this->stringProperties( $entity, true ) );

			$statement = $this->pdo->prepare( $request );

			$params = $this->setParams( $entity );

			return $statement->execute( $params );
		}

		return false;
	}

	/**
	 * Retourne la liste des propriétés de l'entité en chaine de caractere
	 *
	 * @param Entity $entity
	 *
	 * @return string
	 */
	private function stringProperties( Entity $entity, $prepare = false ) {
		foreach ( self::$meta['columns'] as $property => $value ) {
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