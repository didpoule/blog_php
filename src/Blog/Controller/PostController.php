<?php

namespace Blog\Controller;

use App\Controller\Controller;
use App\Orm\ORMException;
use Blog\Entity\Post\Post;
use Blog\Manager\PostManager;


/**
 * Class PostController
 * @package Controller
 */
class PostController extends Controller {

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

	public function listAction() {
		$manager = $this->services->get( 'manager' );
		$manager::setEntity( Post::class );

		$manager = $manager::getManager();

		$posts = $manager->findLasts(2);

		$this->render("post/posts.html.twig", [
			 "posts" => $posts
		]);

	}
}