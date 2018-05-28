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
	 * @param $id int
	 */
	public function showAction( $id ) {

		$manager = $this->services->get( 'manager' );
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		try {
			$post = $manager->find( $id );

			$this->render( "post/post.html.twig", [
				"post" => $post
			] );
		} catch ( ORMException $e ) {
			$this->render( "error/404.html.twig", [
				"message" => $e->getMessage()
			] );
		}
	}

	/**
	 * Récupère la liste de billets
	 */
	public function listAction() {
		$manager = $this->services->get( 'manager' );
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
		$manager = $this->services->get( 'manager' );
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$post = $manager->find( $id );

		$post->setTitle( 'Titre modifié' );
		$post->setSlug($this->services->get('slug')->slugify($post->getTitle()));

		$manager->update( $post );

		$this->redirect( 'billet', [ 'id' => $id ] );
	}

	/**
	 * Supprime un billet
	 * @param $id
	 */
	public function deleteAction( $id ) {
		$manager = $this->services->get( 'manager' );
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$manager->delete( $id );

		$this->redirect( "billets" );
	}

	/**
	 * Insère un billet
	 */
	public function insertAction() {
		$manager = $this->services->get( 'manager' );
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$post = new Post();
		$post->setTitle('Un article bidon');
		$post->setSlug($this->services->get('slug')->slugify($post->getTitle()));
		$post->setAdded(new \DateTime());
		$post->setContent('Un contenu tout pourri');
		$manager->insert( $post );
		$this->redirect("billets");
	}
}