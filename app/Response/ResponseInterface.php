<?php

namespace App\Response;

/**
 * Interface ResponseInterface
 * @package App\Response
 */
interface ResponseInterface {

	/**
	 * @return mixed
	 */
	public function send();
}