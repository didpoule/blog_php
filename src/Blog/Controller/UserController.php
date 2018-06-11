<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\User;
use Blog\Forms\UserForm;

/**
 * Class UserController
 * @package Blog\Controller
 */
class UserController extends Controller {

	public function loginAction() {

		if ( ! isset( $_SESSION['authenticated'] ) ) {

			$form    = $this->form->get( UserForm::class, [
				"action" => "/login"
			] );

			if ( $this->request->getPost() ) {
				$manager = $this->database->getManager( User::class );

				$user = $form->sendForm();

				if ( ! is_array( $user ) ) {
					if ( $manager->checkLogin( $user->getUsername(), $user->getPassword() ) ) {
						$_SESSION['authenticated'] = true;

						$this->bag->addMessage("Connexion réussie", "success");
						return $this->redirect( 'admin' );
					} else {
						$this->bag->addMessage("Erreur: Le nom d'utilisateur et/ou le mot de passe sont incorrectes.", "danger");
						return $this->redirect('login');
					}
				}
				foreach ( $user as $error ) {
					$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
				}
			}

			return $this->render( 'login/login.html.twig', [
				'form' => $form->getForm(),
			] );
		}

		return $this->redirect( 'admin' );
	}

}