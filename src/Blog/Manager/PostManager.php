<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;

/**
 * Class PostManager
 * @package Blog\Manager
 */
class PostManager extends Manager {

	/**
	 * PostManager constructor.
	 *
	 * @param $entity
	 * @param $meta
	 */
	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	public function findChapters( $catId, $limit = null ) {
		return $this->fetchAll( [ "category" => $catId ], null, $limit, [ 'added' => "ASC" ] );
	}

	public function find( $params = [] ) {
		return $this->fetch( $params );
	}

	public function getNbChapters() {
		$request = "SELECT COUNT(*) AS count FROM post WHERE number IS NOT NULL";
		$statement = $this->pdo->prepare($request);
		$statement->execute();

		return $statement->fetch()[0];
	}

}