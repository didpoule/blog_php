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

		/**
		 * Gestion du cookie de lecture
		 */
		$nbChapters   = $manager->getNbChapters();
		$readChapters = $this->readChapters;

		$readChapters->init( $this->request, $nbChapters );
		$current = ( $readChapters->getCookie() > 1 ) ?? null;


		/**
		 * Si le chapitre demandé n'existe pas en base de donnée
		 */
		if ( ! $post ) {
			return $this->render( 'error/404.html.twig', [
				"message" => "Le chapitre demandé n'a pas encore été écrit."
			] );
		} else {
			$manager = $this->database->getManager( Comment::class );

			/**
			 * Création formulaire
			 */
			$comment = $manager->getNew();
			$comment->setPostId( $post->getId() );
			$form = $this->form->get( CommentForm::class, [
				"admin"   => false,
				"action"  => "/chapitres/chapitre-" . $post->getNumber(),
				"comment" => $comment,
				"post"    => $post
			] );


			/**
			 * Gestion envoi du formulaire
			 */
			if ( $this->request->getPost() ) {

				$result = $form->sendForm();
				if ( ! is_array( $result ) && $result !== false ) {
					$manager->insert( $result );
					$this->bag->addMessage( "Votre commentaire a bien été envoyé.", "success" );
				} elseif ( is_array( $result ) ) {
					foreach ( $result as $error ) {
						$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
					}
				} else {
					$this->bag->addMessage( "Le commentaire n'a pas pu être envoyé.", "danger" );
				}

				return $this->redirect( 'numberChapter', [ "number" => $post->getNumber() ], "#comment-form" );

			}

			return $this->render( "post/post.html.twig", [
				"post"       => $post,
				"current"    => $current,
				"form"       => $form->getForm(),
				"nbComments" => $this->database->getManager( Comment::class )->countComments( [ "postId" => $post->getId() ] ),
				"limit"      => COM_PER_PAGE

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
	 * Met à jour le cookie de lecture
	 * @return \App\Http\Response\RedirectResponse
	 */
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

	public function aboutAction() {
		$manager = $this->database->getManager( Category::class );

		$about = $manager->findByName( 'about' )->posts[0];

		if ( ! $about ) {
			return $this->render( "error/404.html.twig", [
				"message" => "La page n'existe pas."
			] );
		}

		return $this->render( "about/about.html.twig", [
			"about" => $about
		] );
	}
}