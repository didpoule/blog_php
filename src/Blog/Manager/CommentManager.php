<?php

namespace Blog\Manager;

use App\Orm\Manager;

/**
 * Class CommentManager
 * @package Blog\Manager
 */
class CommentManager extends Manager {

	public function __construct( \PDO $pdo, $entity, $meta ) {
		parent::__construct( $pdo, $entity, $meta );
	}

	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	public function findAllByPost( $id ) {
		return $this->fetchAll( [ "post" => $id ], null, null, [ "added" => "DESC" ] );
	}
}