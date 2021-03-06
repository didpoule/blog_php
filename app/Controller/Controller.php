<?php

namespace App\Controller;

use App\Http\Response\JsonResponse;
use App\Http\Response\RedirectResponse;
use App\Http\Response\Response;
use App\Router\Router;
use App\Router\RouterException;
use App\ServicesProvider\ServicesException;
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
	 * @return Response
	 */
	public function render( $fileName, $datas = [] ) {

		$view = $this->twig->load( $fileName );

		$content = $view->render( $datas );

		return new Response( $content );
	}

	/**
	 * @param $routeName string
	 * @param array $arg
	 */
	public function redirect( $routeName, $args = [], $anchor = null ) {

		try {
			$route = $this->router->getRouteByName( $routeName );
			$url   = $route->generateUrl( $args, $anchor );

			return new RedirectResponse( $url );

		} catch ( RouterException $e ) {
			echo $e->getMessage();
		}
	}

	/**
	 * Renvoie a l'url précédente
	 * @return RedirectResponse
	 */
	public function redirectToBack( $anchor = null ) {
		return new RedirectResponse ( $_SERVER['HTTP_REFERER'] . "#$anchor" );
	}

	/**
	 * Renvoie Réponse encodée en JSON
	 *
	 * @param $datas
	 *
	 * @return JsonResponse
	 */
	public function json( $datas ) {
		return new JsonResponse( $datas );
	}


	/**
	 * Raccourci pour apeller un service
	 *
	 * @param $name
	 *
	 * @return bool|mixed
	 */
	public function __get( $name ) {
		try {
			return $this->services->get( strtolower( $name ) );
		} catch ( ServicesException $e ) {
			echo( $e );
		}
		exit();
	}
}