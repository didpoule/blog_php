<?php

namespace Blog\Manager;

use App\Orm\Manager;
use App\Orm\ORMException;
use Blog\Entity\Post\Post;

/**
 * Class PostManager
 * @package Blog\Manager
 */
class PostManager extends Manager {

	/**
	 * Cherche un billet avec son id
	 * @param $id
	 *
	 * @return Post
	 * @throws ORMException
	 */
	public function findById($id) {
		$result = $this->fetch(['id' => $id]);

		if ($result) {
			$post = new Post();
			try {
				$post->hydrate($result);
			} catch ( ORMException $e) {
				die($e->getMessage());

			}
			return $post;
		}
		throw new ORMException("Le billet demandé n'existe pas.");
	}

}