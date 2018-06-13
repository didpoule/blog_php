<?php

namespace App\Router;

use App\Controller\Controller;
use App\Http\Request\Request;
use App\ServicesProvider\ServicesProvider;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Router
 * @package App\Router
 */
class Router {

	/**
	 * @var array
	 */
	private $routes = [];

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var Route
	 */
	private $matchedRoute;


	/**
	 * @var ServicesProvider
	 */
	private $services;

	/**
	 * Router constructor.
	 *
	 * @param Request $request
	 */
	public function __construct( $services ) {
		$this->services = $services;
		$this->request  = $this->services->get( "request" );
		$this->firewall = $this->services->get( "firewall" );

	}

	/**
	 * @param $file
	 */
	public function parseRouting( $file ) {
		$routes = Yaml::parseFile( $file );

		$this->setRoutes( $routes );
	}

	/**
	 * @param $routes
	 */
	public function setRoutes( $routes ) {
		foreach ( $routes as $name => $datas ) {
			if ( ! isset ( $this->routes[ $name ] ) ) {
				$this->routes[ $name ] = new Route( $name, $datas["path"], $datas['parameters'], $datas['controller'], $datas['action'] );
			}
		}
	}

	/**
	 * Cherche une route correspondant à l'url
	 * @return Route
	 * @throws RouterException
	 */
	public function getRoute() {
		// Vérifie si l'utilisateur a accès à la route
		$access = $this->firewall->checkPath();

		// On redirige vers la route définie dans les paramètres du firewall
		if ( $access !== true ) {
			$route = $this->getRouteByName( $access );

			if ( $route instanceof Route ) {
				$url = $route->generateUrl( [] );
				$this->request->setUrl( $url );
			} else {
				throw new RouterException( sprintf( "La route '%s' définie dans security.yml n'existe pas", $access ) );
			}
		}

		// Cherche une route correspondant à l'url
		foreach ( $this->routes as $route ) {
			if ( $route->match( $this->request->getUrl() ) ) {
				$this->matchedRoute = $route;

				return $this->call();
			};
		}

		// Renvoie une erreur 404
		return $this->call( false );
	}

	/**
	 * Cherche une route avec son nom
	 *
	 * @param $name
	 *
	 * @return mixed
	 * @throws RouterException
	 */
	public function getRouteByName( $name ) {
		if ( isset( $this->routes[ $name ] ) ) {

			return $this->routes[ $name ];
		}

		// Renvoie une erreur 404
		return $this->call( false );
	}

	/**
	 * Appelle la méthode du contrôleur de la route
	 */
	public function call( $success = true ) {
		if ( $success ) {

			if ( ! $this->request->getPost() ) {
				$this->request->setToken();
			}

			$controller = $this->matchedRoute->getController();
			$action     = $this->matchedRoute->getAction();
			$params     = $this->matchedRoute->getArgs();

			$controller = new $controller( $this->services, $this );

			return call_user_func_array( [ $controller, $action ], $params );
		}

		$controller = new Controller( $this->services, $this );

		return $controller->render( "error/404.html.twig", [
			"message" => "La page que vous avez demandé n'existe pas."
		] );

	}


	/**
	 * @return Route
	 */
	public function getMatchedRoute() {
		return $this->matchedRoute;
	}
}