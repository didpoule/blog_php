<?php

namespace App\Router;

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
	public function __construct($services ) {
		$this->services = $services;
		$this->request  = $this->services->get("request");

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

				return $this->call();
			};
		}
		throw new RouterException( "Route inconnue" );
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
		if(!$this->request->getPost()) {
			$this->request->setToken();
		}

		$controller = $this->matchedRoute->getController();
		$action     = $this->matchedRoute->getAction();
		$params     = $this->matchedRoute->getArgs();

		$controller = new $controller( $this->services, $this );

		return call_user_func_array( [ $controller, $action ], $params );
	}


	/**
	 * @return Route
	 */
	public function getMatchedRoute() {
		return $this->matchedRoute;
	}
}