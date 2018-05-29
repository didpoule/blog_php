<?php

namespace Blog\Manager;

use App\Orm\Manager;
use Blog\Entity\Comment\Comment;

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

	/**
	 * Récupère un post avec les commentaires associés
	 * @param $param
	 *
	 * @return mixed
	 */
	public function findOne( $param ) {
		$result = $this->fetch( $param, Comment::class, true );

		if ( $result ) {
			$post = $result['post'];

			foreach ( $result['comment'] as $comment ) {
				$post->addComment( $comment );
			}
		}

		return $post;

	}
}