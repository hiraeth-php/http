<?php

namespace Hiraeth\Http;

use SplFileInfo;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Simple and default URL generator
 */
class DefaultUrlGenerator implements UrlGenerator
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(mixed $location, array $params = []): string
	{
		if ($location instanceof Request) {
			$params += $location->getQueryParams();

			return $this($location->getUri()->getPath(), $params);
		}

		if ($location instanceof SplFileInfo) {
			return $this($location->getPathname());
		}

		foreach ($params as $name => $value) {
			$count    = 0;
			$location = str_replace(
				sprintf('{%s}', $name),
				urlencode((string) $value),
				$location,
				$count
			);

			if ($count) {
				unset($params[$name]);
			}
		}

		if (count($params)) {
			$query    = parse_url((string) $location, PHP_URL_QUERY);
			$fragment = parse_url((string) $location, PHP_URL_FRAGMENT);
			$append   = http_build_query($params);

			if (!empty($query)) {
				$location = str_replace(
					sprintf('?%s', $query),
					sprintf('?%s&%s', $query, $append),
					$location
				);

			} elseif (!empty($fragment)) {
				$location = str_replace(
					sprintf('#%s', $fragment),
					sprintf('?%s#%s', $append, $fragment),
					$location
				);

			} else {
				$location = sprintf('%s?%s', $location, $append);

			}
		}

		return (string) $location;
	}

	/**
	 * {@inheritDoc}
	 */
	public function call(): string
	{
		return $this->__invoke(...func_get_args());
	}
}
