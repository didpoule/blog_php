<?php

namespace App\Orm;

use Symfony\Component\Yaml\Yaml;

class Database {

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var Database
	 */
	private static $database;

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 *
	 */
	public static function getInstance($file) {

		if ( self::$database === null ) {

			$params = Yaml::parseFile($file);
			self::$database = new Database($params['database']);
		}

		return self::$database;
	}

	/**
	 * Database constructor.
	 *
	 * @param $params
	 */
	public function __construct( $params ) {
		try {
			$this->pdo = new \PDO(
				"mysql:dbname=" . $params['name'] .
				";host=" . $params['host'] .
				":" . $params['port'],
				$params['user'],
				$params['password']
			);
		} catch(\PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getPdo() {
		return $this->pdo;
	}
}