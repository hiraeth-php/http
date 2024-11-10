<?php

namespace Hiraeth\Http;

use Psr\Http\Message\ResponseInterface;

class Interrupt extends \Exception
{
	/**
	 * @var ResponseInterface
	 */
	protected $response;


	/**
	 * Create a new interrupt response
	 *
	 * @param ResponseInterface $response
	 * @return self
	 */
	static public function response(ResponseInterface $response): self
	{
		$interrupt = new self(
			'The response flow has been interrupted',
			$response->getStatusCode()
		);

		$interrupt->response = $response;

		return $interrupt;
	}


	/**
	 *
	 */
	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}
}
