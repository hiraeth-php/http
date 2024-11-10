<?php

namespace Hiraeth\Http;

use Hiraeth\Http;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 *
 */
interface Action
{
	/**
	 * Set a stream factory on the managed object
	 */
	public function setStreamFactory(StreamFactory $stream_factory): self;


	/**
	 * Set a url generator on the managed object
	 */
	public function setUrlGenerator(Http\UrlGenerator $url_generator): self;
}
