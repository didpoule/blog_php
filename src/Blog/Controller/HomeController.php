<?php

namespace Blog\Controller;

use App\Controller\Controller;

class HomeController extends Controller {

	public function homeAction() {


		return $this->render( 'home/home.html.twig', [
			// TODO
		] );
	}
}