<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Comment;
use Blog\Entity\Post;
use Blog\Forms\CommentForm;

/**
 * Class PostController
 * @package Controller
 */
class PostController extends Controller {

	/**
	 * Affiche un chapitre
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
		$nbChapters   = $manager->getNbChapters();
		$readChapters = $this->readChapters;

		$readChapters->init( $this->request, $nbChapters );
		$current = ( $readChapters->getCookie() > 1 ) ?? null;

		if ( ! $post ) {

			return $this->render( 'error/404.html.twig', [
				"message" => "Le chapitre demandé n'a pas encore été écrit."
			] );
		} else {
			$manager = $this->database->getManager( Comment::class );

			$this->request->setToken();

			$form = new CommentForm($post->getId(), $this->request->getToken());

			$comments = $manager->findAllByPost( $post->getId() );


			return $this->render( "post/post.html.twig", [
				"post"     => $post,
				"comments" => $comments,
				"state"    => $readChapters->getState(),
				"current"  => $current,
				"form"     => $form->getForm()

			] );
		}

	}

	/**
	 * Récupère la liste de chapitres
	 */
	public function listAction() {
		$manager    = $this->database->getManager( Category::class );
		$chapterCat = $manager->findByName( 'chapter' );

		$manager = $this->database->getManager( Post::class );
		$posts   = $manager->findChapters( $chapterCat->getId() );

		return $this->render( "post/posts.html.twig", [
			"posts" => $posts
		] );
	}

	/**
	 * Modifie un post
	 *
	 * @param $id int
	 */
	public function editAction( $id ) {
		$manager = $this->database->getManager( Post::class );
		$post    = $manager->find( [ "id" => $id ] );

		if ( $post ) {
			$post->setTitle( "truc machin" );
			$post->setSlug( $this->slug->slugify( $post->getTitle() ) );

			$post->setUpdated( new \DateTime() );
			if ( $manager->update( $post ) ) {
				return $this->redirect( 'billet', [ 'id' => $id ] );
			}
		}

		return $this->redirect( 'billets' );
	}

	/**
	 * Supprime un billet
	 *
	 * @param $id
	 */
	public function deleteAction( $id ) {

		return $this->redirect( "billets" );

	}

	/**
	 * Insère un billet
	 */
	public function insertAction() {

		return $this->redirect( "billets" );
	}

	public function nextAction() {
		$manager = $this->database->getManager( Post::class );
		$this->request->setPost( "next" );

		$nbChapters   = $manager->getNbChapters();
		$readChapters = $this->readChapters;
		$readChapters->init( $this->request, $nbChapters );


		return $this->redirect( "slugChapter", [ "slug" => sprintf( "chapitre-%s", $readChapters->getCurrent() ) ], sprintf( "#%s", $readChapters->getCurrent() ) );
	}

	public function previousAction() {
		$manager = $this->database->getManager( Post::class );
		$this->request->setPost( "previous" );

		$nbChapters   = $manager->getNbChapters();
		$readChapters = $this->readChapters;
		$readChapters->init( $this->request, $nbChapters );


		return $this->redirect( "slugChapter", [ "slug" => sprintf( "chapitre-%s", $readChapters->getCurrent() ) ], sprintf( "#%s", $readChapters->getCurrent() ) );
	}
}