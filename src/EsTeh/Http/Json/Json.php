<?php

namespace EsTeh\Http\Json;

use EsTeh\Contracts\Abilities\Renderable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Http\Json
 * @license MIT
 */
class Json implements Renderable
{
	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @var int
	 */
	private $httpCode;

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function __construct($data, $httpCode = 200, $jsonOpt = null)
	{
		$this->data = $data;
		$this->httpCode = $httpCode;
		$this->jsonOpt = $jsonOpt;
	}

	/**
	 * @return void
	 */
	public function render()
	{
		http_response_code($this->httpCode);
		header("Content-type:application/json");
		echo json_encode($this->data, $this->jsonOpt);
	}
}
