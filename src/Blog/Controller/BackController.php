<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Post;
use Blog\Forms\ChapterForm;
use Blog\Forms\PostForm;
use Blog\Manager\PostManager;

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
				if ( ! is_array( $result ) ) {
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

			$chapters = $manager->findChapters( $cat->getId() );;

			return $this->render( 'admin/chapters.html.twig', [
				"chapters" => $chapters,
				"bag"      => $this->bag
			] );

		}

		return $this->redirect( 'login' );
	}

	public function chapterAction( $id ) {
		if ( $_SESSION['authenticated'] ) {

			$manager = $this->database->getManager( Post::class );

			$chapter = $manager->find( [ "id" => $id ] );

			$form = new ChapterForm( $chapter, sprintf( "/admin/chapter/%s", $chapter->getId() ), $this->request->getToken() );

			if ( $this->request->getPost() ) {
				$this->form->get( ChapterForm::class );
				$result = $form->sendForm( $this->request, $chapter );
				if ( ! is_array( $result ) ) {
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
				"title" => 'Editer un chapitre',
				"bag"   => $this->bag,
				"form"  => $form->getForm()
			] );
		}

		return $this->redirect( 'login' );
	}

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
				if ( ! is_array( $result ) ) {
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

				return $this->redirect('adminChapters');
			}
		}

		return $this->redirect( 'login' );
	}
}