<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Post;

/**
 * Class HomeController
 * @package Blog\Controller
 */
class HomeController extends Controller {

	/**
	 * @return \App\Http\Response\Response
	 */
	public function homeAction() {
		$manager = $this->database->getManager( Category::class );

		$edito = $manager->findByName( 'edito' );

		// Récupération du chapitre en cours
		if ( $this->request->getCookie( 'current' ) ) {
			$chapter = $this->database->getManager( Post::class )->getExtract( [ "number" => $this->request->getCookie( "current" ) ] );
		}

		// Si un chapitre est en cours de lecture on affiche le résumé de celui ci sinon le synopsis
		$featured = ( isset( $chapter ) ) ? $chapter : $manager->findByName( 'synopsis' )->posts[0];

		return $this->render( 'home/home.html.twig', [
			'edito'    => $edito->posts[0],
			'featured' => $featured
		] );
	}
}