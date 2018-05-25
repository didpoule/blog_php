<?php

namespace Blog\Controller;

use App\Controller\Controller;
use App\Router\RouterException;

/**
 * Class PostController
 * @package Controller
 */
class PostController extends Controller {


	public function showAction( $slug ) {
		/*
		 * $this->services('manager')->getOneBy($slug);
		 */
		$titre = $slug;

		try {
			$this->render( "post/post.html.twig", [
				"titre" => $titre
			] );
		} catch ( \Twig_Error_Loader $e ) {
			echo( $e->getMessage() );
		}
	}
}