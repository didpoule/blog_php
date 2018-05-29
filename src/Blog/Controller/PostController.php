<?php

namespace Blog\Controller;

use App\Controller\Controller;
use App\Orm\ORMException;
use Blog\Entity\Post\Post;


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
		$manager = $this->manager;
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		if ( $this->slug->isSlug( $value ) ) {
			$post = $manager->findBySlug( $value );
		} else {
			$post = $manager->find( $value );

		}

		if ( ! $post ) {
			$this->render( 'error/404.html.twig', [
				"message" => "Le billet demandé n'existe pas."
			] );
		} else {
			$this->render( "post/post.html.twig", [
				"post" => $post
			] );
		}

	}

	/**
	 * Récupère la liste de billets
	 */
	public function listAction() {
		$manager = $this->manager;
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$posts = $manager->findLasts();


		$this->render( "post/posts.html.twig", [
			"posts" => $posts
		] );
	}

	/**
	 * Modifie un billet
	 *
	 * @param $id int
	 */
	public function editAction( $id ) {
		$manager = $this->manager;
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$post = $manager->find( $id );

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
		$manager = $this->manager;
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$manager->delete( $id );


		$this->redirect( "billets" );

	}

	/**
	 * Insère un billet
	 */
	public function insertAction() {
		$manager = $this->manager;
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$post = new Post();
		$post->setTitle( 'test article' );
		$post->setSlug( $this->slug->slugify( $post->getTitle() ) );
		$post->setAdded( new \DateTime() );
		$post->setContent( 'Un contenu tout pourri' );

		$manager->insert( $post );

		$this->redirect( "billets" );
	}
}