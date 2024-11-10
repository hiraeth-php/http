<?php

namespace Hiraeth\Http;

use Hiraeth\Http;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 *
 */
trait ManagedTrait
{
	/**
	 * @var StreamFactory
	 */
	protected $streamFactory;

	/**
	 * @var Http\UrlGenerator
	 */
	protected $urlGenerator;


	/**
	 * Set a stream factory on the managed object
	 */
	public function setStreamFactory(StreamFactory $stream_factory): self
	{
		$this->streamFactory = $stream_factory;

		return $this;
	}


	/**
	 * Set a url generator on the managed object
	 */
	public function setUrlGenerator(Http\UrlGenerator $url_generator): self
	{
		$this->urlGenerator = $url_generator;

		return $this;
	}
}
