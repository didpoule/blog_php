<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;

/**
 * Class CommentManager
 * @package Blog\Manager
 */
class CommentManager extends Manager {

	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	public function findAllByPost( $params = [] ) {
		return $this->fetchAll( $params, null, null, [ "added" => "DESC" ] );
	}

}