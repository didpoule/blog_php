<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Comment;

class CommentController extends Controller {

	public function insertAction() {
		$manager = $this->database->getManager( Comment::class );

		// Comparaison du token envoyé avec celui de la session
		if ( $this->request->getPost( 'token' ) === $this->request->getToken() ) {

			if ( ( $this->request->getPost( 'author' ) && $this->request->getPost( 'content' ) && $this->request->getPost( 'post' ) ) &&
			     ! is_null( $this->request->getPost( 'author' ) ) && ! is_null( $this->request->getPost( 'content' ) ) && ! is_null( $this->request->getPost( 'content' ) ) ) {

				$comment = new Comment();
				$comment->setAuthor( htmlspecialchars( $this->request->getPost( 'author' ) ) );
				$comment->setContent( htmlspecialchars( $this->request->getPost( 'content' ) ) );
				$comment->setPost( htmlspecialchars( $this->request->getPost( 'post' ) ) );

				$comment->setAdded( new \DateTime() );

				$manager->insert( $comment );

				return $this->redirect( 'idChapter', [ 'id' => $comment->getPost() ] );
			}

			return $this->redirectToBack();
		}

		return $this->render( 'error/token.html.twig', [
			"message" => "Le token est invalide."
		] );
	}
}