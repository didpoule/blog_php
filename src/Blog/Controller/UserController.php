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

			if(!$this->request->getToken()) {
				$this->request->setToken();
			}

			if ( $this->request->getPost() ) {
				$manager = $this->database->getManager( User::class );
				$form    = $this->form->get( UserForm::class );

				$user = $form->sendForm( $this->request );

				if ( ! is_array( $user ) ) {
					if ( $manager->checkLogin( $user->getUsername(), $user->getPassword() ) ) {
						var_dump('test');
						$_SESSION['authenticated'] = true;
						return $this->redirect('admin');
					}
				}
				foreach ( $user as $error ) {
					$this->bag->addMessage( sprintf( "Erreur: le champ %s doit être renseigné", $error ), "danger" );
				}
			}
			$form = new UserForm( $this->request->getToken() );

			return $this->render( 'login/login.html.twig', [
				'form' => $form->getForm()
			] );
		}
	return $this->redirect('admin');
	}

}