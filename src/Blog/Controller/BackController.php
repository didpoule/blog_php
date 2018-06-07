<?php

namespace Blog\Controller;

use App\Controller\Controller;

/**
 * Class BackController
 * @package Blog\Controller
 */
class BackController extends Controller {

	public function homeAction() {
		if (!$_SESSION['authenticated']) {
			return $this->redirect('login');
		}

		return $this->render('admin/home.html.twig');
	}
}