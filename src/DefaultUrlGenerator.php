<?php

namespace Hiraeth\Http;

/**
 * Simple and default URL generator
 */
class DefaultUrlGenerator implements UrlGenerator {
	public function __invoke($location, array $params = array()): string
	{
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
			$query    = parse_url($location, PHP_URL_QUERY);
			$fragment = parse_url($location, PHP_URL_FRAGMENT);
			$append   = http_build_query($params);

			if (strlen($query)) {
				$location = str_replace(
					sprintf('?%s', $query),
					sprintf('?%s&%s', $query, $append),
					$location
				);

			} elseif ($fragment) {
				$location = str_replace(
					sprintf('#%s', $fragment),
					sprintf('?%s#%s', $append, $fragment),
					$location
				);

			} else {
				$location = sprintf('%s?%s', $location, $append);

			}
		}

		return $location;
	}

	public function call(): string
	{
		return $this->__invoke(...func_get_args());
	}
}
