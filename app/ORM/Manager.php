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
	public function fetch( $params = [], $join = null, $alias = null ) {

		$limit   = is_null( $join ) ? "LIMIT 0,1" : null;
		$request = sprintf( "SELECT %s FROM %s AS %s %s %s %s", $this->select( $alias, $join ), self::$entity::getMeta()['name'], substr( self::$entity::getName(), 0, 1 ), $this->inner( $join ), $this->where( $params, $alias ), $limit );

		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );


		$result = is_null( $join ) ? $statement->fetch( \PDO::FETCH_ASSOC ) : $statement->fetchAll( \PDO::FETCH_ASSOC );


		if ( $result ) {
			if ( ! is_null( $join ) ) {
				// Séparation des résultats par entité
				$i = 0;
				foreach ( $result as $row ) {
					foreach ( $row as $column => $value ) {
						$colAlias = substr( $column, 0, 1 );
						$aliasA   = substr( self::$entity::getName(), 0, 1 );
						$aliasB   = substr( $join::getName(), 0, 1 );


						/**
						 * On enlève le préfixe pour pouvoir hydrater ensuite
						 */

						switch ( $colAlias ) {

							case $aliasA:
								$resultsA[ substr( $column, 2 ) ] = $value;
								break;
							case $aliasB:
								$resultsB[ $i ][ substr( $column, 2 ) ] = $value;
						}
					}
					$i ++;
				}
				/*
				 * Hydratation de l'entité principale
				 */
				if ( $resultsA ) {

					$entity = new self::$entity();
					try {
						$entity->hydrate( $resultsA );
						$entities[ self::$entity::getName() ] = $entity;
					} catch ( ORMException $e ) {
						die( $e->getMessage() );
					}
				}
				/*
				 * Hydratation de l'entité jointe
				 */
				if ( $resultsB ) {
					foreach ( $resultsB as $result ) {
						$entity = new $join();
						try {
							$entity->hydrate( $result );
							$entities[ $join::getName() ][] = $entity;
						} catch ( ORMException $e ) {
							die( $e->getMessage() );
						}
					}
				}

				return $entities;
			}
			$entity = new self::$entity();

			try {
				$entity->hydrate( $result );
			} catch ( ORMException $e ) {
				die ( $e->getMessage() );
			}

			return $entity;
		}

		return false;
	}


	/**
	 * @param array $params
	 */
	public function fetchAll( $params = [], $offset = null, $limit = null, $sort = [] ) {

		$request   = sprintf( "SELECT * FROM %s %s %s %s", self::$entity::getMeta()['name'], $this->order( $sort ), $this->limit( $offset, $limit ), $this->where( $params ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );


		$results = $statement->fetchAll( \PDO::FETCH_ASSOC );
		if ( $results ) {


			foreach ( $results as $result ) {
				$entity = new self::$entity();
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
	 * Génère le select avec des alias
	 *
	 * @param bool $alias
	 *
	 * @return string
	 */
	private function select( $alias = false, $join = null ) {
		if ( $alias ) {
			foreach ( self::$entity::getMeta()['columns'] as $property => $value ) {
				$alias    = substr( self::$entity::getName(), 0, 1 );
				$select[] = sprintf( "%s.%s AS %s_%s", $alias, $property, $alias, $property );
			}

			if ( $join ) {
				foreach ( $join::getMeta()['columns'] as $property => $value ) {
					$alias    = substr( $join::getName(), 0, 1 );
					$select[] = sprintf( "%s.%s AS %s_%s", $alias, $property, $alias, $property );
				}
			}

			return sprintf( "%s", implode( ',', $select ) );
		}

		return "*";
	}

	/**
	 * Génère le WHERE de la requête préparée
	 *
	 * @param $params
	 *
	 * @return string
	 */
	private function where( $params, $alias = null ) {
		if ( ! empty( $params ) ) {

			foreach ( $params as $property => $value ) {
				if ( $alias ) {
					$conditions[] = sprintf( "%s.%s = :%s", substr( self::$entity::getName(), 0, 1 ), $property, $property );
				} else {
					$conditions[] = sprintf( "%s = :%s", $property, $property );
				}
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
	 * Ajoute un INNER JOIN à la requête préparée
	 *
	 * @param $class string
	 *
	 * @return string
	 */
	private function inner( $class ) {
		if ( $class ) {
			$table = $class::getName();

			// Prefixe la jointure
			$alias = substr( $table, 0, 1 );

			// Jointure par la colonne du meme nom que l'entité principale
			$join        = sprintf( "%s.%s", $alias, self::$entity::getName() );
			$entityalias = substr( self::$entity::getName(), 0, 1 );

			return sprintf( "INNER JOIN %s AS %s ON %s.id = %s", $table, $alias, $entityalias, $join );
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
		if ( $this->find( $entity->getId() ) ) {
			$request = sprintf( "UPDATE %s %s WHERE id = :id", self::$entity::getMeta()['name'], $this->set( $entity ) );

			$statement = $this->pdo->prepare( $request );

			$params = $this->setParams( $entity );

			return $statement->execute( $params );
		}
		throw new ORMException( "Impossible de mettre à jour cette entité car inexistant en base de donnée." );
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
		$request = sprintf( "INSERT INTO %s %s %s", self::$entity::getMeta()['name'], $this->stringProperties( $entity ), $this->stringProperties( $entity ) );


		$statement = $this->pdo->prepare( $request );

		$params = $this->setParams( $entity );

		$result = $statement->execute( $params );

		$statement->closeCursor();

		return $result;
	}

	/**
	 * Retourne la liste des propriétés de l'entité en chaine de caractere
	 *
	 * @param Entity $entity
	 *
	 * @return string
	 */
	private function stringProperties( Entity $entity ) {
		foreach ( self::$entity::getMeta()['columns'] as $property => $value ) {
			if ( $property != "id" ) {
				$getter = sprintf( "get%s", ucfirst( $property ) );

				if ( $entity->$getter() != null ) {
					$properties[] = sprintf( ":%s", $property );
				}
			}
		}

		return sprintf( "VALUES (%s)", implode( ",", $properties ) );
	}
}