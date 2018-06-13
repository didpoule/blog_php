<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Comment;
use Blog\Forms\CommentForm;

class CommentController extends Controller {

	public function insertAction() {
		$manager = $this->database->getManager( Comment::class );

		$form = $this->form->get( CommentForm::class, [
		] );

		$comment = $form->sendForm( $this->request );

		if ( ! is_array( $comment ) ) {
			$manager->insert( $comment );
			$this->bag->addMessage( "Votre commentaire a bien été envoyé.", "success" );
		} else {
			foreach ( $comment as $error ) {
				$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
			}
		}

		return $this->redirectToBack( 'comment-form' );
	}

	public function getAction($postId, $offset, $limit) {
		$manager = $this->database->getManager(Comment::class);

		$comments = $manager->findAllByPost(["postId" => $postId, "published" => 1], $offset, $limit);

		return $this->json($comments);
	}
}