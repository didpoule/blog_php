<?php

namespace Blog\Manager;

use App\Orm\Manager;

/**
 * Class CommentManager
 * @package Blog\Manager
 */
class CommentManager extends Manager {

	public function findAllByPost($id) {
		return $this->fetchAll(["post" => $id], null, null, ["added" => "DESC"]);
	}
}