<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;

/**
 * Class UserManager
 * @package Blog\Manager
 */
class UserManager extends Manager {

	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	public function checkLogin( $username, $password ) {
		$request = sprintf( "SELECT * FROM user" );

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
}