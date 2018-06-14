<?php

namespace App\Services;

use App\Http\Request\Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Firewall
 * @package App\Services
 */
class Firewall {

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var array
	 */
	private $patterns;

	/**
	 * Firewall constructor.
	 *
	 * @param $file
	 * @param Request $request
	 */
	public function __construct( $file, Request $request ) {
		$this->params  = Yaml::parseFile( $file );
		$this->request = $request;
	}

	/**
	 * Parse l'url pour vérifier si l'url nécessite une condition
	 *
	 * @return bool
	 */
	public function checkPath() {
		if ( empty ( $this->patterns ) ) {
			$this->setPatterns();
		}

		$uri = $this->request->getUrl();

		$parts = explode( "/", $uri );


		foreach ( $this->patterns as $pattern => $params ) {

			if ( $parts[1] === $params["pattern"] ) {

				if ( $this->checkConditions( $params["conditions"] ) ) {

					// Si les conditions sont remplies on autorise l'accès
					return true;
				}

				// Si les conditions ne sont pas respectées
				return $params['redirect'];
			}
		}

		// Si l'url ne nécessite pas d'autorisation on autorise l'accès
		return true;
	}

	/**
	 * Définit les patterns à vérifier
	 */
	private function setPatterns() {
		foreach ( $this->params as $name => $params ) {
			$this->patterns[ $name ] = $params;
		}
	}

	/**
	 * Vérifie si les conditions sont respectées pour accéder à l'url
	 *
	 * @param $conditions
	 *
	 * @return bool
	 */
	private function checkConditions( $conditions ) {

		foreach ( $conditions as $condition => $value ) {
			if ( ! isset( $_SESSION[ $condition ] ) || $_SESSION[ $condition ] !== true ) {
				return false;
			}
		}

		return true;
	}

}