<?php

class PostService implements IPostService
{
	/**
	 * Retrieves posts via WP_Query
	 * @param $queryParams array
	 * @return WP_Query object
	 */
	public function getPosts($queryParams)
	{
		return new WP_Query($queryParams);
	}

	/**
	 * Retrieves posts stored via the Transients API
	 * http://codex.wordpress.org/Transients_API
	 * @param $key string
	 * @param $queryParams array
	 * @param $expiration int
	 * @return WP_Query object
	 */
	public function getPostsTransient($key, $queryParams, $expiration)
	{
		// Check for cached queries. If none, then execute WP_Query
		$queryResult = get_transient($key);

		if (empty($queryResult)) 
		{
			$queryResult = $this->getPosts($queryParams);

			// Put the results in a transient. Expire after 4 hours.
			set_transient($key, $queryResult, empty($expiration) ? 4 * HOUR_IN_SECONDS : $expiration);
		}
		 
		return $queryResult;
	}
}