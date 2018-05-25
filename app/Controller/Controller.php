<?php

namespace App\Controller;

use App\Http\Response\RedirectResponse;
use App\Http\Response\Response;
use App\Router\Router;
use App\Router\RouterException;
use App\ServicesProvider\ServicesProvider;

/**
 * Class Controller
 * @package App\Controller
 */
class Controller {

	/**
	 * @var ServicesProvider
	 */
	protected $services;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * Controller constructor.
	 *
	 * @param $services
	 * @param $router
	 */
	public function __construct( $services, $router ) {
		$this->services = $services;
		$this->router   = $router;
	}

	/**
	 * @param $fileName
	 * @param array $datas
	 *
	 * @return mixed|void
	 */
	public function render( $fileName, $datas = [] ) {

		$view = $this->services->get( 'twig' )->load( $fileName );

		$content = $view->render( $datas );

		$response = new Response( $content );

		return $response->send();
	}

	/**
	 * @param $routeName string
	 * @param array $arg
	 */
	public function redirect( $routeName, $args = [] ) {

		try {
			$route = $this->router->getRouteByName( $routeName );
			$url   = $route->generateUrl( $args );

			$response = new RedirectResponse( $url );

			$response->send();
		} catch ( RouterException $e ) {
			echo $e->getMessage();
		}


	}
}