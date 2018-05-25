<?php

namespace App\Router;

use App\Request\Request;
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
	public function __construct( Request $request, $services ) {
		$this->request  = $request;
		$this->services = $services;
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
		foreach ( $this->routes as $route ) {
			if ( $route->match( $this->request->getUrl() ) ) {
				$this->matchedRoute = $route;

				return $route;
			};
		}
		throw new RouterException("Route inconnue");
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

		throw new RouterException( 'Route inconnue' );
	}

	/**
	 * Appelle la méthode du contrôleur de la route
	 */
	public function call() {
		$controller = $this->matchedRoute->getController();
		$action     = $this->matchedRoute->getAction();
		$params     = $this->matchedRoute->getArgs();

		$controller = new $controller( $this->services, $this );

		call_user_func_array( [ $controller, $action ], $params );
	}


	/**
	 * @return Route
	 */
	public function getMatchedRoute() {
		return $this->matchedRoute;
	}
}