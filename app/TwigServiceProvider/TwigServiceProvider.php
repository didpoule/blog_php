<?php

namespace App\TwigServiceProvider;

/**
 * Crée une instance Twig Environment pour le conteneur de services
 * Class TwigServiceProvider
 * @package App\TwigServiceProvider
 */
class TwigServiceProvider {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * TwigServiceProvider constructor.
	 *
	 * @param $params array
	 */
	public function __construct($params) {
		if (key_exists('file', $params)) {
			$this->file = $params['file'];
		}
		/**
		 * Injection du fichier dans le loader et créatinon de l'instance Twig
		 */
		$twig = new \Twig_Loader_Filesystem( $this->file);

		$this->twig = new \Twig_Environment($twig, [
			"cache" => false
		]);
	}

	/**
	 * @return \Twig_Environment
	 */
	public function getTwig(): \Twig_Environment {
		return $this->twig;
	}

}