<?php

namespace Blog\Manager;

use App\Orm\Manager;

/**
 * Class PostManager
 * @package Blog\Manager
 */
class PostManager extends Manager {
	public function findLasts( $limit = null ) {
			return $this->fetchAll(null, 0, $limit, ['added' => "DESC"]);
	}
}