<?php

namespace Blog\Controller;

use App\Controller\Controller;
use App\Orm\ORMException;
use Blog\Entity\Comment;
use Blog\Entity\Post;

/**
 * Class PostController
 * @package Controller
 */
class PostController extends Controller {

	/**
	 * Affiche un billet
	 *
	 * @param $value mixed
	 */
	public function showAction( $value ) {
		$manager = $this->database->getManager( Post::class );
		if ( $this->slug->isSlug( $value ) ) {
			$post = $manager->find( [ "slug" => $value ] );
		} else {
			$post = $manager->find( [ "id" => $value ] );

		}

		if ( ! $post ) {

			$this->render( 'error/404.html.twig', [
				"message" => "Le billet demandé n'existe pas."
			] );
		} else {
			$manager = $this->database->getManager( Comment::class );

			$comments = $manager->findAllByPost( $post->getId() );

			foreach ( $comments as $comment ) {
				$post->addComment( $comment );
			}

			return $this->render( "post/post.html.twig", [
				"post" => $post
			] );
		}

	}

	/**
	 * Récupère la liste de billets
	 */
	public function listAction() {
		$manager = $this->database->getManager( Post::class );
		$posts   = $manager->findLasts();


		return $this->render( "post/posts.html.twig", [
			"posts" => $posts
		] );
	}

	/**
	 * Modifie un billet
	 *
	 * @param $id int
	 */
	public function editAction( $id ) {
		$manager = $this->database->getManager( Post::class );
		$post    = $manager->find( $id );

		if ( $post ) {
			$post->setTitle( 'Titre modifié' );
			$post->setSlug( $this->slug->slugify( $post->getTitle() ) );

			try {
				$post->setUpdated( new \DateTime() );
				$manager->update( $post );
				$this->redirect( 'billet', [ 'id' => $id ] );
			} catch ( ORMException $e ) {
				echo $e->getMessage();
			}
		} else {
			$this->redirect( 'billets' );
		}
	}

	/**
	 * Supprime un billet
	 *
	 * @param $id
	 */
	public function deleteAction( $id ) {
		$manager = $this->database->getManager( Post::class );
		$manager->delete( $id );


		$this->redirect( "billets" );

	}

	/**
	 * Insère un billet
	 */
	public function insertAction() {
		$manager = $this->database->getManager( Post::class );
		$post    = new Post();
		$post->setTitle( 'test article' );
		$post->setSlug( $this->slug->slugify( $post->getTitle() ) );
		$post->setAdded( new \DateTime() );
		$post->setContent( 'Un contenu tout pourri' );

		$manager->insert( $post );

		$this->redirect( "billets" );
	}
}