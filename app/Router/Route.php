<?php

namespace App\Router;

/**
 * Class Route
 * @package App\Route
 */
class Route {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @var string
	 */
	private $controller;

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @var array
	 */
	private $args = [];

	/**
	 * Route constructor.
	 *
	 * @param $name
	 * @param $path
	 * @param $parameters
	 * @param $controller
	 * @param $action
	 */
	public function __construct( $name, $path, $parameters, $controller, $action ) {
		$this->name       = $name;
		$this->path       = $path;
		$this->parameters = $parameters;
		$this->controller = $controller;
		$this->action     = $action;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * @return mixed
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @return string
	 */
	public function getController(): string {
		return $this->controller;
	}

	/**
	 * @return string
	 */
	public function getAction(): string {
		return $this->action;
	}

	/**
	 * @return mixed
	 */
	public function getArgs() {
		return $this->args;
	}

	/**
	 * Vérifie si l'url soumise correspond au pattern de la route
	 *
	 * @param $uri
	 *
	 * @return bool
	 */
	public function match( $uri ) {

		/**
		 * Récupération des regex des parametres
		 */
		$path = preg_replace_callback( "/:(\w+)/", [ $this, "parameterMatch" ], $this->path );

		/**
		 * Echappement "/" pour regex
		 */
		$path = str_replace( "/", "\/", $path );


		/**
		 * Si la route ne correspond pas a l'url
		 */
		if ( ! preg_match( "/^$path$/i", $uri, $matches ) ) {
			return false;
		};

		/**
		 * Récupération des valeur de parametre
		 */
		$this->args = array_slice( $matches, 1 );

		return true;

	}

	/**
	 * retourne la REGEX du paramètre demandé
	 *
	 * @param $match
	 *
	 * @return string
	 */
	private function parameterMatch( $match ): string {

		if ( isset( $this->parameters[ $match[1] ] ) ) {
			return sprintf( "(%s)", $this->parameters[ $match[1] ] );
		}

		return '([^/]+)';
	}

	/**
	 * Génère une URL à partir des arguments envoyés
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function generateUrl( $args, $anchor = null ): string {
		$url = str_replace( array_keys( $args ), $args, $this->path );
		$url = str_replace( ":", "", $url );

		$url .= $anchor;

		return $url;
	}

}