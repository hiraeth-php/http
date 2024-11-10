<?php

namespace Hiraeth\Http;

use Hiraeth;

use Hiraeth\Http;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 * {@inheritDoc}
 */
class ActionProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			Action::class
		];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @param Action $instance
	 */
	public function __invoke(object $instance, Hiraeth\Application $app): object
	{
		$instance->setStreamFactory($app->get(StreamFactory::class));
		$instance->setUrlGenerator($app->get(Http\UrlGenerator::class));

		return $instance;
	}
}
