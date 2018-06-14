<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;
use Blog\Entity\User;

/**
 * Class UserManager
 * @package Blog\Manager
 */
class UserManager extends Manager {

	/**
	 * UserManager constructor.
	 *
	 * @param Database $database
	 * @param $entity
	 * @param $meta
	 */
	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	/**
	 * Vérifie si le nom d'utilisateur et le mot de passes soumis correspnodent
	 *
	 * @param $username
	 * @param $password
	 *
	 * @return bool
	 */
	public function checkLogin( $username, $password ) {
		$request = "SELECT * FROM user";

		$statement = $this->pdo->prepare( $request );

		$statement->execute();
		$result = $statement->fetch( \PDO::FETCH_ASSOC );

		if ( $result ) {
			if ( $username === $result['username'] && password_verify( $password, $result['password'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Met à jour le mot de passe de l'utilisateur
	 *
	 * @param $username
	 * @param $password
	 */
	public function updatePassword( $username, $password ) {
		$request   = "UPDATE user SET password= :password WHERE username = :username ";
		$statement = $this->pdo->prepare( $request );


		$statement->execute( [
			"password" => password_hash( $password, PASSWORD_BCRYPT ),
			"username" => $username,
		] );

	}

	/**
	 * @return User
	 */
	public function getNew() {
		return new $this->entity( $this->meta );
	}
}