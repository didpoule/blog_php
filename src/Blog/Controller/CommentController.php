<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Comment;
use Blog\Forms\CommentForm;

/**
 * Class CommentController
 * @package Blog\Controller
 */
class CommentController extends Controller {

	/**
	 * Récupère des commentaires formatés en JSON pour traitement en JS
	 *
	 * @param $postId
	 * @param $offset
	 * @param $limit
	 *
	 * @return \App\Http\Response\JsonResponse
	 */
	public function getAction( $postId, $offset, $limit ) {
		$manager = $this->database->getManager( Comment::class );

		$comments = $manager->findAllByPost( [ "postId" => $postId, "published" => 1 ], $offset, $limit );

		return $this->json( $comments );
	}
}