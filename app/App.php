<?php

namespace App;

use App\Http\Request\Request;
use App\Router\Router;
use App\Router\RouterException;
use App\ServicesProvider\ServicesProvider;

/**
 * Class App
 * @package App
 */
class App {

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $routingConfig;

	/**
	 * @var ServicesProvider
	 */
	private $services;

	/**
	 * App constructor.
	 */
	public function __construct() {
		$this->services = new ServicesProvider( __DIR__ . "/../config/services.yml" );

		$this->request = new Request();

		$this->router = new Router( $this->request, $this->services );

		$this->routingConfig = __DIR__ . "/../config/routing.yml";

	}

	/**
	 * App run.
	 */
	public function run() {
		$this->router->parseRouting( $this->routingConfig );

		try {
			$this->router->getRoute();
		} catch ( RouterException $e ) {
			echo $e->getMessage();
		}

		if ( $this->router->getMatchedRoute() ) {
			$this->router->call();
		}
	}
}