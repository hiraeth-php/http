<?php

namespace Hiraeth\Http;

use Hiraeth\Application;

/**
 * Proxy URL Generator
 */
class ProxyUrlGenerator implements UrlGenerator
{
	private readonly string $basePath;

	private readonly string $baseRewrite;

	private readonly UrlGenerator $target;

	/**
	 *
	 */
	public function __construct(UrlGenerator $target, string $base_path, bool $base_rewrite) {
		$this->target      = $target;
		$this->basePath    = $base_path;
		$this->baseRewrite = $base_rewrite;
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

		if ($location[0] ?? null == '/') {
			if (str_starts_with($location, $this->basePath . '/')) {
				if ($this->baseRewrite) {
					$location = substr($location, strlen($this->basePath));
				}
			} else {
				if ($this->basePath) {
					$location = $this->basePath . $location;
				}
			}
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
