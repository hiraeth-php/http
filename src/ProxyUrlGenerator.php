<?php

namespace Hiraeth\Http;

use Hiraeth\Application;

/**
 * Proxy URL Generator
 */
class ProxyUrlGenerator implements UrlGenerator
{
	private readonly string $basePath;

	private readonly UrlGenerator $target;

	/**
	 *
	 */
	public function __construct(private readonly string $base_path, UrlGenerator $target) {
		$this->target   = $target;
		$this->basePath = $base_path;
	}

	/**
	 * Convert the provided location and parameters to a string URL
	 *
	 * @param mixed $location The location to generate a URL for (depends on implementation)
	 * @param mixed[] $params The query parameters for the generated URL (NULL should remove)
	 */
	public function __invoke(mixed $location, array $params = []): string
	{
		$location = $this->target->__invoke(...func_get_args());

		if ($location[0] == '/') {
			$location = $this->basePath . $location;
		}

		return $location;
	}


	/**
	 *
	 */
	public function call(): string
	{
		return $this->__invoke(...func_get_args());
	}
}
