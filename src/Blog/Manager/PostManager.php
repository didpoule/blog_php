<?php

namespace Blog\Manager;

use App\Orm\Manager;

/**
 * Class PostManager
 * @package Blog\Manager
 */
class PostManager extends Manager {

	/**
	 * @param null $limit
	 *
	 * @return array
	 */
	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	public function findBySlug( $slug ) {
		return $this->fetch( [ "slug" => $slug ] );
	}
}