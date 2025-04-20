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
		$base_path    = rtrim($app->getEnvironment('BASE_PATH', ''), '/');
		$base_rewrite = $app->getEnvironment('BASE_REWRITE', FALSE);

		if ($base_path && isset($_SERVER['REQUEST_URI'])) {
			if ($base_rewrite) {
				$_SERVER['REQUEST_URI'] = rtrim($base_path, '/') . $_SERVER['REQUEST_URI'];

			} else {
				$url_path  = parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH);

				if (str_starts_with($url_path, $base_path . '/')) {
					$_SERVER['REQUEST_URI'] = str_replace(
						$url_path,
						substr($url_path, strlen($base_path)),
						$_SERVER['REQUEST_URI']
					);

				} else {
					header('Location: ' . str_replace(
						$_SERVER['REQUEST_URI'],
						$url_path,
						$base_path . $url_path
					), TRUE, 301);
					exit(301);
				}
			}
		}


		if (!$app->has(UrlGenerator::class)) {
			$app->get(Hiraeth\Broker::class)->alias(UrlGenerator::class, DefaultUrlGenerator::class);
		}

		$proxy  = $app->get(ProxyUrlGenerator::class, compact('base_path', 'base_rewrite'));
		$broker = $app->get(Hiraeth\Broker::class);
		$target = $app->get(UrlGenerator::class);
		$class  = $target::class;

		$broker->alias($class, ProxyUrlGenerator::class);
		$broker->alias(UrlGenerator::class, ProxyUrlGenerator::class);

		$app->share($proxy);

		return $instance;
	}
}
