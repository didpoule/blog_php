<?php

namespace App\ServicesProvider;

use Symfony\Component\Yaml\Yaml;

/**
 * Conteneur de services
 * Class ServiceProvider
 * @package App\ServiceProvider
 */
class ServicesProvider {

	/**
	 * @var array
	 */
	private $container = [];

	/**
	 * @var array
	 */
	private $datas;

	/**
	 * ServicesProvider constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file ) {
		$this->datas = Yaml::parseFile( $file );
	}

	/**
	 * @param $service
	 *
	 * @return bool|mixed
	 */
	public function get( $service ) {

		// Si le service n'est pas encore créé
		if ( ! key_exists( $service, $this->container ) ) {
			// On le créé
			if($this->createService( $service )) {

				// Apelle de la methode pour retourner l'instance créé par le service
				$function = 'get' . ucfirst($service);
				return $this->container[$service]->$function();
			}
			return false;
		}
		// On renvoie le service
		return $this->container[$service];
	}

	/**
	 * @param $service
	 *
	 * @return bool
	 */
	private function createService( $service ) {

		/**
		 * On vérifie si le service demandé est dans le fichier de configuration
		 */
		foreach ( $this->datas as $name => $values ) {
			if ( $name === $service ) {
				$class  = $values['class'];
				$params = $values['params'];

				// Si on a trouvé le service on l'instancie et on renvoie true
				$this->container[ $service ] = new $class( $params );
				return true;
			}
		}
		return false;
	}
}