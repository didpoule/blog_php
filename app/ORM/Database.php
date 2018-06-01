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
	private static $managers;

	/**
	 * @var array
	 */
	private static $metas;

	/**
	 * Database constructor.
	 *
	 * @param $params
	 */
	public function __construct( $params ) {

		$file        = Yaml::parseFile( $params['file'] )['database'];
		self::$metas = Yaml::parseFile( $params['metas'] );

		$db = sprintf( "mysql:dbname=%s;host=%s:%s", $file['name'], $file['host'], $file['port'] );

		$this->pdo = new \PDO( $db, $file['user'], $file['password'] );

	}

	public function getPdo() {
		return $this->pdo;
	}

	/**
	 * @param $entity string className entité
	 */
	public function getManager( $entity ) {
		if ( !isset( self::$managers[ $entity ] ) ) {
			$manager                   = self::$metas[ $entity ]["manager"];
			self::$managers[ $entity ] = new $manager( $this, $entity, self::$metas[ $entity ] );

			$entity::setMeta( self::$metas[ $entity ] );
		}
		return self::$managers[ $entity ];
	}
}