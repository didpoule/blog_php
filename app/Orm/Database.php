<?php

namespace App\Orm;

use Symfony\Component\Yaml\Yaml;

class Database {

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var array
	 */
	private $managers;

	/**
	 * @var array
	 */
	private $metas;

	/**
	 * Database constructor.
	 *
	 * @param $file
	 * @param $meta
	 */
	public function __construct( $file, $meta ) {

		$file        = Yaml::parseFile( $file )['database'];
		$this->metas = Yaml::parseFile( $meta );

		$db = sprintf( "mysql:dbname=%s;host=%s:%s", $file['name'], $file['host'], $file['port'] );

		try {
			$this->pdo = new \PDO( $db, $file['user'], $file['password'] );
		} catch ( \PDOException $e ) {
			exit( $e->getMessage() );
		}

	}

	/**
	 * @return \PDO
	 */
	public function getPdo() {
		return $this->pdo;
	}

	/**
	 * @param $entity
	 *
	 * @return Manager
	 */
	public function getManager( $entity ) {

		if ( ! isset( $this->managers[ $entity ] ) ) {
			$manager                   = $this->metas[ $entity ]["manager"];
			$this->managers[ $entity ] = new $manager( $this, $entity, $this->metas[ $entity ] );
		}

		return $this->managers[ $entity ];
	}
}