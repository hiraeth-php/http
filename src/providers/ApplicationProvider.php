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
		$base_path = rtrim($app->getEnvironment('BASE_PATH', ''), '/');
		$url_path  = parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH);

		if ($base_path) {
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


		if (!$app->has(UrlGenerator::class)) {
			$app->get(Hiraeth\Broker::class)->alias(UrlGenerator::class, DefaultUrlGenerator::class);
		}

		$broker = $app->get(Hiraeth\Broker::class);
		$target = $app->get(UrlGenerator::class);
		$proxy  = $app->get(ProxyUrlGenerator::class, ['base_path' => $base_path]);
		$class  = $target::class;

		$broker->alias($class, ProxyUrlGenerator::class);
		$broker->alias(UrlGenerator::class, ProxyUrlGenerator::class);

		$app->share($proxy);

		return $instance;
	}
}
