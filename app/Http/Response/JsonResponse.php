<?php

namespace App\Http\Response;

/**
 * Class JsonResponse
 * @package App\Http\Response
 */
class JsonResponse implements ResponseInterface {

	/**
	 * @var string
	 */
	private $content;

	/**
	 * Response constructor.
	 *
	 * @param $content
	 */
	public function __construct( $content ) {
		$this->content = $content;
	}

	/**
	 * Gives content
	 */
	public function send() {

		$content = [];
		foreach($this->content as $object) {

			$content[] = $object->jsonSerialize();
		}
		echo json_encode( $content, JSON_FORCE_OBJECT );
	}
}