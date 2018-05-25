<?php

namespace Blog\Controller;

use App\Controller\Controller;

class HomeController extends Controller {

	public function homeAction() {


		$this->render('home/home.html.twig', [
			// TODO
		]);
	}
}