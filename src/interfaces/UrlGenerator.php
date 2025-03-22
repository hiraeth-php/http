<?php

namespace Hiraeth\Http;

/**
 * URL Generators combine a location an parameters into a URL string
 *
 * How the parameters are integrated or applied to the location is up to the specific
 * implementation.  For example, it can parse the location to replace some parameters in the
 * path and append the rest as a query string, it can filter parameters if value is NULL, etc.
 *
 * Additionally, the supported locations are allowed to vary (not a strict interface).  Different
 * genreators may support strings, request objects, etc.
 */
interface UrlGenerator
{
	/**
	 * Convert the provided location and parameters to a string URL
	 *
	 * @param mixed $location The location to generate a URL for (depends on implementation)
	 * @param mixed[] $params The query parameters for the generated URL (NULL should remove)
	 */
	public function __invoke(mixed $location, array $params = []): string;


	/**
	 * Must call __invoke() with whatever arguments passed
	 */
	public function call(): string;
}
