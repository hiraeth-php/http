<?php

namespace Hiraeth\Http;

use Hiraeth;

/**
 * {@inheritDoc}
 */
class ApplicationProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			Hiraeth\Application::class
		];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @param Hiraeth\Application $instance The application instance
	 */
	public function __invoke($instance, Hiraeth\Application $app): object
	{
		if (!$app->has(UrlGenerator::class)) {
			$app->get(Hiraeth\Broker::class)->alias(UrlGenerator::class, DefaultUrlGenerator::class);
		}

		$app->share($app->get(UrlGenerator::class));

		return $instance;
	}
}
