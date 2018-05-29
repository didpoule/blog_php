<?php

namespace Blog\Manager;

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
	public function __construct( \PDO $pdo, $entity, $meta ) {
		parent::__construct( $pdo, $entity, $meta );
	}

	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	public function find( $params = [] ) {
		return $this->fetch( $params );
	}
}