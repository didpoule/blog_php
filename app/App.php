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

		$this->router = new Router( $this->services );

		$this->routingConfig = __DIR__ . "/../config/routing.yml";

	}

	/**
	 * App run.
	 */
	public function run() {
		$this->router->parseRouting( $this->routingConfig );

		try {
			$response = $this->router->getRoute();

			$response->send();

		} catch ( RouterException $e ) {
			echo $e->getMessage();
		}
	}
}