<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Comment;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Forms\ChangePasswordForm;
use Blog\Forms\ChapterForm;
use Blog\Forms\CommentForm;
use Blog\Forms\PostForm;

/**
 * Class BackController
 * @package Blog\Controller
 */
class BackController extends Controller {

	/**
	 * Accueil Administration
	 *
	 * @return \App\Http\Response\Response
	 */
	public function homeAction() {

		$totalComs     = $this->database->getManager( Comment::class )->countComments();
		$totalChapters = $this->database->getManager( Post::class )->getNbChapters();

		$waitComs = $this->database->getManager( Comment::class )->countComments( [ "published" => 0 ] );

		return $this->render( 'admin/home.html.twig', [
			"comments" => $totalComs,
			"waitComs" => $waitComs,
			"chapters" => $totalChapters
		] );


	}

	/**
	 * Formulaire Edito
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function editoAction() {

		$manager = $this->database->getManager( Category::class );

		$edito = $manager->findByName( 'edito' )->posts[0];

		$form = $this->form->get( PostForm::class, [
			'post'   => $edito,
			'action' => '/admin/edito'
		] );

		if ( $this->request->getPost() ) {

			$result = $this->form->sendForm( $edito );
			if ( ! is_array( $result ) ) {
				$manager = $this->database->getManager( Post::class );
				$manager->update( $edito );

				$this->bag->addMessage( "Mise a jour effectuée", "success" );

				return $this->redirect( 'adminEdito' );
			} else {
				$this->bag->addMessage( sprintf( "Erreur: Aucun texte n'a été renseigné. L'Edito n'a pas été modifié." ), "danger" );
			}
		}

		return $this->render( "admin/edito.html.twig", [
			"edito" => $edito,
			"form"  => $form->getForm(),
		] );


	}

	/**
	 * Formulaire Synopsis
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function synopsisAction() {

		$manager = $this->database->getManager( Category::class );

		$synopsis = $manager->findByName( 'synopsis' )->posts[0];


		$form = $this->form->get( PostForm::class, [
			'post'   => $synopsis,
			'action' => '/admin/synopsis'
		] );

		if ( $this->request->getPost() ) {

			$result = $form->sendForm( $synopsis );
			if ( ! is_array( $result ) && $result !== false ) {
				$manager = $this->database->getManager( Post::class );
				$manager->update( $synopsis );

				$this->bag->addMessage( "Mise a jour effectuée", "success" );

				return $this->redirect( 'adminSynopsis' );
			} else {
				$this->bag->addMessage( sprintf( "Erreur: Aucun texte n'a été renseigné. Le Synopsis n'a pas été modifié." ), "danger" );
			}
		}

		return $this->render( "admin/synopsis.html.twig", [
			"synopsis" => $synopsis,
			"form"     => $form->getForm(),
		] );


	}

	/**
	 * Liste des chapitres
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chaptersAction() {
		$manager = $this->database->getManager( Category::class );


		$chapters = $manager->findByName( 'chapter' )->posts;

		/**
		 * Récupère le nombre de commentaire de chaque chapitre
		 */
		foreach ( $chapters as $chapter ) {
			$nbComs[ $chapter->getId() ] = $this->database->getManager( Comment::class )->countComments( [ "postId" => $chapter->getId() ] );
		}

		return $this->render( 'admin/chapters.html.twig', [
			"chapters" => $chapters,
			"nbComs"   => $nbComs
		] );


	}

	/**
	 * Edition Chapitre
	 *
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chapterAction( $id ) {

		$manager = $this->database->getManager( Post::class );

		$chapter = $manager->find( [ "id" => $id ] );

		$form = $this->form->get( ChapterForm::class, [
			"post"   => $chapter,
			"action" => "/admin/chapter/" . $chapter->getId(),
		] );

		if ( $this->request->getPost() ) {
			$result = $form->sendForm( $chapter );
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
			"form"    => $form->getForm(),
			"chapter" => $chapter
		] );

	}

	/**
	 * Écriture chapitre
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function chapterNewAction() {
		$manager = $this->database->getManager( Category::class );

		$cat = $manager->findByName( 'chapter' );

		$manager = $this->database->getManager( Post::class );

		$chapter = $manager->getNew();

		$chapter->setCategory( $cat->getId() );
		$chapter->setNumber( $manager->getNbChapters() + 1 );
		$form = $this->form->get( ChapterForm::class, [
			"post"   => $chapter,
			"action" => "/admin/chapter/new"

		] );
		if ( ! $this->request->getPost() ) {
			return $this->render( 'admin/chapter.html.twig', [
				"form"  => $form->getForm(),
				"title" => 'Ecrire un chapitre'
			] );
		} else {
			$result = $form->sendForm( $chapter );
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

	/**
	 * Suppression Chapitre
	 *
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse
	 */
	public function chapterDeleteAction( $id ) {
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

		$this->bag->addMesasge( "Impossible de supprimer ce chapiter.", "danger" );

		return $this->redirectToBack();

	}

	/**
	 * Retourne tous les commentaires ou ceux d'un chapitre si spécifié
	 *
	 * @param null $postId
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function commentsAction( $postId = null ) {
		if ( ! $postId ) {
			$manager  = $this->database->getManager( Comment::class );
			$comments = $manager->fetchAll();
		} else {
			$manager = $this->database->getManager( Post::class );
			$post    = $manager->find( [ "id" => $postId ] );

		}


		if ( ( $postId && $post ) || $comments ) {
			return $this->render( 'admin/comments.html.twig', [
				"post"     => isset( $post ) ? $post : null,
				"comments" => isset( $post ) ? $post->getComments( true ) : $comments,
			] );
		}
		$this->bag->addMessage( "Le billet demandé n'existe pas.", "danger" );

		return $this->redirect( "admin" );
	}

	/**
	 * Affiche les commentaires encore non publiés
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function commentsModerateAction() {
		$manager  = $this->database->getManager( Comment::class );
		$comments = $manager->fetchAll( [ "published" => 0 ] );

		return $this->render( 'admin/comments.html.twig', [
			"comments" => ! is_null( $comments ) ? $comments : null,
		] );
	}


	/**
	 * Édition commentaire
	 *
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function commentAction( $id ) {

		$manager = $this->database->getManager( Comment::class );

		$comment = $manager->find( $id );

		$post = $this->database->getManager( Post::class )->find( [ "id" => $comment->getPostId() ] );

		$form = $this->form->get( CommentForm::class, [
				"admin"   => true,
				"action"  => "/admin/comment/" . $comment->getId(),
				"comment" => $comment,
				"post"    => $post
			]
		);
		if ( $this->request->getPost() ) {
			$result = $form->sendForm( $comment );
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
			"form"    => $form->getForm(),
			"comment" => $comment
		] );
	}

	/**
	 * Supression commentaire
	 *
	 * @param $id
	 *
	 * @return \App\Http\Response\RedirectResponse
	 */
	public function commentDeleteAction( $id ) {
		$manager = $this->database->getManager( Comment::class );

		$comment = $manager->find( $id );


		if ( $comment ) {
			if ( $manager->delete( $comment->getId() ) ) {
				$this->bag->addMessage( "Commentaire supprimé avec succès", "success" );
			} else {
				$this->bag->addMessage( "Erreur : Impossible de supprimer le commentaire demandé.", "danger" );
			}

			return $this->redirectToBack();
		}

		$this->bag->addMessage( 'Impossible de supprimer ce commentaire.', "danger" );

		return $this->redirectToBack();
	}

	/**
	 * Mise à jour du mot de passe de l'admin
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function userAction() {

		$manager = $this->database->getManager( User::class );

		$form = $this->form->get( ChangePasswordForm::class, [
			'action' => "/admin/user"
		] );

		if ( $this->request->getPost() ) {

			if ( $this->request->getPost( 'newPassword' ) === $this->request->getPost( 'newPasswordRepeat' ) ) {
				$check = $manager->checkLogin( $this->request->getPost( 'username' ), $this->request->getPost( 'password' ) );

				if ( $check ) {
					$manager->updatePassword( $this->request->getPost( 'username' ), $this->request->getPost( 'newPassword' ) );

					$this->bag->addMessage( "Mise a jour effectuée", "success" );

					return $this->redirect( 'admin' );
				} else {
					$this->bag->addMessage( 'Erreur: Le nom d\'utilisateur et/ou le mot de passe saisi est incorrect.' );
				}
			} else {
				$this->bag->addMessage( 'Erreur: Les 2 mots de passes ne sont pas identiques' );
			}

			return $this->redirectToBack();
		}

		return $this->render( "admin/user.html.twig", [
			"title" => 'Modifier le mot de passe',
			"form"  => $form->getForm(),
		] );
	}

	/**
	 * Formulaire page à propos
	 *
	 * @return \App\Http\Response\RedirectResponse|\App\Http\Response\Response
	 */
	public function aboutAction() {
		$manager = $this->database->getManager( Category::class );

		$about = $manager->findByName( 'about' )->posts[0];

		$form = $this->form->get( PostForm::class, [
			'post'   => $about,
			'action' => '/admin/about'
		] );

		if ( $this->request->getPost() ) {

			$result = $this->form->sendForm( $about );
			if ( ! is_array( $result ) ) {
				$manager = $this->database->getManager( Post::class );

				$manager->update( $about );

				$this->bag->addMessage( "Mise a jour effectuée", "success" );

				return $this->redirect( 'adminAbout' );
			} else {
				$this->bag->addMessage( sprintf( "Erreur: Aucun texte n'a été renseigné. La page n'a pas été modifié." ), "danger" );
			}
		}

		return $this->render( "admin/about.html.twig", [
			"about" => $about,
			"form"  => $form->getForm(),
		] );
	}
}