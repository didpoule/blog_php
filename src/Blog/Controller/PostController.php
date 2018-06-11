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
		$post    = $manager->find( [ "number" => $value ] );

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

			$comment = new Comment();
			$comment->setPost( $post->getId() );

			$form = new CommentForm( $comment, $post, $this->request->getToken() );


			if ( $this->request->getPost() ) {
				$this->form->get( CommentForm::class );
				$result = $form->sendForm( $this->request, $comment );
				if ( ! is_array( $result ) && $result !== false ) {
					$manager->insert( $result );
					$this->bag->addMessage( "Votre commentaire a bien été envoyé.", "success" );
				} else {
					foreach ( $result as $error ) {
						$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
					}
				}

				return $this->redirect( 'numberChapter', [ "number" => $post->getNumber() ] );

			}

			$comments = $manager->findAllByPost( [ "post" => $post->getId(), "published" => 1 ] );

			return $this->render( "post/post.html.twig", [
				"post"     => $post,
				"comments" => $comments,
				"state"    => $readChapters->getState(),
				"current"  => $current,
				"form"     => $form->getForm(),
				"bag"      => $this->bag

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
		$posts   = $manager->findChapters( [ "category" => $chapterCat->getId(), "published" => 1 ] );

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


		return $this->redirect( "numberChapter", [ "number" => sprintf( "%s", $readChapters->getCurrent() ) ], sprintf( "#%s", $readChapters->getCurrent() ) );
	}

	public function previousAction() {
		$manager = $this->database->getManager( Post::class );
		$this->request->setPost( "previous" );

		$nbChapters   = $manager->getNbChapters();
		$readChapters = $this->readChapters;
		$readChapters->init( $this->request, $nbChapters );


		return $this->redirect( "numberChapter", [ "number" => sprintf( "%s", $readChapters->getCurrent() ) ], sprintf( "#%s", $readChapters->getCurrent() ) );
	}
}