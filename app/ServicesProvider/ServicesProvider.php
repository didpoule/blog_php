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
	 * @return mixed
	 * @throws \ReflectionException
	 */
	public function get( $service ) {

		// Si le service n'est pas encore créé
		if ( ! key_exists( $service, $this->container ) ) {
			// On le créé
			try {
				if ( $this->createService( $service ) ) {

					// Apelle de la methode pour retourner l'instance créé par le service

					return $this->container[ $service ];

				}

			} catch ( ServicesException $e ) {
				echo $e->getMessage();
			}
		}

		// On renvoie le service
		return $this->container[ $service ];
	}

	/**
	 * @param $service
	 *
	 * @return bool
	 * @throws ServicesException
	 * @throws \ReflectionException
	 */
	private function createService( $service ) {

		/**
		 * On vérifie si le service demandé est dans le fichier de configuration
		 */
		foreach ( $this->datas as $name => $values ) {
			if ( $name === $service ) {
				$class  = $values['class'];
				$params = $values['params'];

				/**
				 * Injecte les services requis en paramètre
				 */
				foreach($params as $param => $value) {
					if(array_key_exists($value, $this->datas)) {
						$params[$param] = $this->get($value);
					}
				}
				// Si on a trouvé le service on l'instancie et on renvoie true

				$reflect = new \ReflectionClass($class);
				$this->container[$service] = $reflect->newInstanceArgs($params);

				return true;
			}
		}
		throw new ServicesException( "Erreur: Le service $service n'existe pas." );
	}
}