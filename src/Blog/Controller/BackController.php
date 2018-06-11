<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Comment;
use Blog\Entity\Post;
use Blog\Forms\ChapterForm;
use Blog\Forms\CommentForm;
use Blog\Forms\PostForm;

/**
 * Class BackController
 * @package Blog\Controller
 */
class BackController extends Controller {

	/**
	 * @return \App\Http\Response\Response
	 */
	public function homeAction() {
		if ( $_SESSION['authenticated'] ) {
			return $this->render( 'admin/home.html.twig' );

		}

		return $this->redirect( 'login' );
	}

	/**
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function editoAction() {
		if ( $_SESSION['authenticated'] ) {

			$manager = $this->database->getManager( Category::class );

			$editoCat = $manager->findByName( 'edito' );

			$manager = $this->database->getManager( Post::class );

			// Récupération de l'edito
			if ( $editoCat ) {
				$edito = $manager->fetch( [ "category" => $editoCat->getId() ] );
			}

			$form = new PostForm( $edito, "/admin/edito", $this->request->getToken() );

			if ( $this->request->getPost() ) {

				$this->form->get( PostForm::class );
				$result = $form->sendForm( $this->request, $edito );
				if ( ! is_array( $result ) ) {
					$manager->update( $edito );

					$this->bag->addMessage( "Mise a jour effectuée", "success" );

					return $this->redirect( 'adminEdito' );
				} else {
					$this->bag->addMessage( sprintf( "Erreur: Aucun texte n'a été renseigné. L'Edito n'a pas été modifié." ), "danger" );
				}
			}

			return $this->render( "admin/edito.html.twig", [
				"form" => $form->getForm(),
				"bag"  => $this->bag
			] );

		}

		return $this->redirect( 'login' );
	}

	/**
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function synopsisAction() {
		if ( $_SESSION['authenticated'] ) {

			$manager = $this->database->getManager( Category::class );

			$synopsisCat = $manager->findByName( 'synopsis' );

			$manager = $this->database->getManager( Post::class );

			// Récupération de l'edito
			if ( $synopsisCat ) {
				$synopsis = $manager->fetch( [ "category" => $synopsisCat->getId() ] );
			}

			$form = new PostForm( $synopsis, "/admin/synopsis", $this->request->getToken() );

			if ( $this->request->getPost() ) {

				$this->form->get( PostForm::class );
				$result = $form->sendForm( $this->request, $synopsis );
				if ( ! is_array( $result ) && $result !== false ) {

					$manager->update( $synopsis );

					$this->bag->addMessage( "Mise a jour effectuée", "success" );

					return $this->redirect( 'adminSynopsis' );
				} else {
					$this->bag->addMessage( sprintf( "Erreur: Aucun texte n'a été renseigné. Le Synopsis n'a pas été modifié." ), "danger" );
				}
			}

			return $this->render( "admin/synopsis.html.twig", [
				"form" => $form->getForm(),
				"bag"  => $this->bag
			] );

		}

		return $this->redirect( 'login' );
	}

	/**
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chaptersAction() {
		if ( $_SESSION['authenticated'] ) {
			$manager = $this->database->geTManager( Category::class );

			$cat = $manager->findByName( 'chapter' );

			$manager = $this->database->getManager( Post::class );

			$chapters = $manager->findChapters( [ "category" => $cat->getId() ], null );

			return $this->render( 'admin/chapters.html.twig', [
				"chapters" => $chapters,
				"bag"      => $this->bag
			] );

		}

		return $this->redirect( 'login' );
	}

	/**
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chapterAction( $id ) {
		if ( $_SESSION['authenticated'] ) {

			$manager = $this->database->getManager( Post::class );

			$chapter = $manager->find( [ "id" => $id ] );

			$form = new ChapterForm( $chapter, sprintf( "/admin/chapter/%s", $chapter->getId() ), $this->request->getToken() );

			if ( $this->request->getPost() ) {
				$this->form->get( ChapterForm::class );
				$result = $form->sendForm( $this->request, $chapter );
				if ( ! is_array( $result ) && $result !== false ) {

					$manager->update( $chapter );

					$this->bag->addMessage( "Mise a jour effectuée", "success" );

					return $this->redirect( 'adminChapters' );
				} else {
					foreach ( $result as $error ) {
						$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
					}
				}

			}

			return $this->render( "admin/chapter.html.twig", [
				"title"   => 'Editer un chapitre',
				"bag"     => $this->bag,
				"form"    => $form->getForm(),
				"chapter" => $chapter
			] );
		}

		return $this->redirect( 'login' );
	}

	/**
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chapterNewAction() {
		if ( $_SESSION['authenticated'] ) {
			$manager = $this->database->getManager( Category::class );

			$cat = $manager->findByName( 'chapter' );

			$manager = $this->database->getManager( Post::class );

			$chapter = new Post();

			$chapter->setCategory( $cat->getId() );
			$chapter->setNumber( $manager->getNbChapters() + 1 );
			$form = new ChapterForm( $chapter, "/admin/chapter/new", $this->request->getToken() );
			if ( ! $this->request->getPost() ) {
				return $this->render( 'admin/chapter.html.twig', [
					"form"  => $form->getForm(),
					"bag"   => $this->bag,
					"title" => 'Ecrire un chapitre'
				] );
			} else {
				$this->form->get( ChapterForm::class );
				$result = $form->sendForm( $this->request, $chapter );
				if ( ! is_array( $result ) && $result !== false ) {

					$manager->insert( $result );

					$this->bag->addMessage( "Chapitre enregistré avec succès", "success" );

					return $this->redirect( 'adminChapters' );
				} else {
					foreach ( $result as $error ) {
						$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
					}

					return $this->redirect( 'adminChapterNew' );
				}
			}
		}

		return $this->redirect( 'login' );
	}

	/**
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse
	 */
	public function chapterDeleteAction( $id ) {
		if ( $_SESSION['authenticated'] ) {
			$manager = $this->database->getManager( Post::class );

			$chapter = $manager->find( [ "id" => $id ] );

			if ( $chapter ) {
				if ( $manager->delete( $chapter->getId() ) ) {
					$this->bag->addMessage( "Chapitre supprimé avec succès", "success" );
				} else {
					$this->bag->addMesasge( "Erreur : Impossible de supprimer le chapitre demandé.", "danger" );
				}

				return $this->redirect( 'adminChapters' );
			}
		}

		return $this->redirect( 'login' );
	}

	public function commentsAction() {
		if ( $_SESSION['authenticated'] ) {
			$manager = $this->database->getManager( Comment::class );

			$comments = $manager->fetchAll();


			$titles = $this->database->getManager(Post::class)->getChaptersTitles();


			return $this->render( 'admin/comments.html.twig', [
				"comments" => $comments,
				"bag"      => $this->bag,
				"titles"   => $titles
			] );
		}

		return $this->redirect( 'login' );
	}

	/**
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function commentAction( $id ) {
		if ( $_SESSION['authenticated'] ) {

			$manager = $this->database->getManager( Comment::class );

			$comment = $manager->find( $id );

			$form = new CommentForm( $comment, $comment->getPost(), $this->request->getToken(), true, "/admin/comment/" . $comment->getId() );

			if ( $this->request->getPost() ) {
				$this->form->get( CommentForm::class );
				$result = $form->sendForm( $this->request, $comment );
				if ( ! is_array( $result ) && $result !== false ) {
					$manager->update( $result );
					$this->bag->addMessage( "Mise a jour effectuée", "success" );

					return $this->redirect( 'adminComments' );
				} else {
					foreach ( $result as $error ) {
						$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
					}
				}

			}

			return $this->render( "admin/comment.html.twig", [
				"title"   => 'Editer un commentaire',
				"bag"     => $this->bag,
				"form"    => $form->getForm(),
				"comment" => $comment
			] );
		}
	}

	/**
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse
	 */
	public function commentDeleteAction( $id ) {
		if ( $_SESSION['authenticated'] ) {
			$manager = $this->database->getManager( Comment::class );

			$comment = $manager->find( $id );

			if ( $comment ) {
				if ( $manager->delete( $comment->getId() ) ) {
					$this->bag->addMessage( "Commentaire supprimé avec succès", "success" );
				} else {
					$this->bag->addMessage( "Erreur : Impossible de supprimer le commentaire demandé.", "danger" );
				}

				return $this->redirect( 'adminComments' );
			}
		}

		return $this->redirect( 'login' );
	}
}