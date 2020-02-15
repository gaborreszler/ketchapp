<?php
namespace App\Libraries;

class Tmdb {

	const ENDPOINT_FIND = "/find";
	const ENDPOINT_TV = "/tv";
	const ENDPOINT_SEASON = "/season";
	const ENDPOINT_EPISODE = "/episode";
	const ENDPOINT_EXTERNAL_IDS = "/external_ids";

	const ENDPOINT_KEYS = [
		self::ENDPOINT_FIND => "find",
		self::ENDPOINT_TV => "tv",
		self::ENDPOINT_SEASON => "season",
		self::ENDPOINT_EPISODE => "episode",
		self::ENDPOINT_EXTERNAL_IDS => "external_ids"
	];

	protected $api_key;
	protected $base_url = "https://api.themoviedb.org/3";
	protected $path_url, $query_string;

	public function __construct($api_key)
	{
		$this->api_key = $api_key;
	}

	public function buildEndpointString(array $parameters)
	{
		$endpoint_string = "";
		foreach ($parameters as $parameter_key => $parameter_value) {
			$endpoint_string .= array_search($parameter_key, self::ENDPOINT_KEYS);
			if (!is_null($parameter_value))
				$endpoint_string .= "/" . $parameter_value;
		}

		return $endpoint_string;
	}

	public function buildQueryString(array $parameters)
	{
		$query_string = "?" . http_build_query($parameters);

		return $query_string;
	}

	public function request(array $endpoint_parameters, array $query_parameters = [])
	{
		$query_parameters["api_key"] = $this->api_key;
		$query_parameters["language"] = "en_US";

		$this->path_url = $this->buildEndpointString($endpoint_parameters);
		$this->query_string = $this->buildQueryString($query_parameters);

		$response_headers = [];
		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->base_url . $this->path_url . $this->query_string,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			//CURLOPT_POSTFIELDS => "{}",
		));
		curl_setopt($ch, CURLOPT_HEADERFUNCTION,
			function($curl, $header) use (&$response_headers)
			{
				$len = strlen($header);
				$header = explode(':', $header, 2);
				if (count($header) < 2) // ignore invalid headers
					return $len;

				$response_headers[strtolower(trim($header[0]))][] = trim($header[1]);

				return $len;
			}
		);

		$response = curl_exec($ch);
		if ($err = curl_error($ch))
			dd("cURL Error #:" . $err);


		$now = time();
		//todo: review
		/*if (isset($response_headers["x-ratelimit-reset"][0]))
			$rate_limit_reset = $response_headers["x-ratelimit-reset"][0];
		else
			$rate_limit_reset = $now+10;*/
		$rate_limit_reset = isset($response_headers["x-ratelimit-reset"][0]) ? $response_headers["x-ratelimit-reset"][0] : $now+10;

		//dump(date('Y-m-d H:i:s', $now) . " - " . date('Y-m-d H:i:s', $rate_limit_reset));

		if (isset($response_headers["x-ratelimit-remaining"][0]) && intval($response_headers["x-ratelimit-remaining"][0]) < 3) {//As of December 16, 2019, TMDb has disabled the API rate limiting.

			$seconds = $rate_limit_reset-$now;
			//if ($seconds === 10) $seconds++;

			dump("API rate limit almost reached, going to sleep for " . $seconds . " seconds");
			sleep($seconds);
		}
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 429) {
			$retry_after = intval($response_headers["retry-after"][0]);

			$condition = $now+$retry_after > $rate_limit_reset;
			$seconds = $condition ? $retry_after : $rate_limit_reset-$now;
			//if ($seconds === 10) $seconds++;

			dump("API rate limit reached, going to sleep for " . $seconds . " seconds based on " . ($condition ? "retry-after." : "ratelimit-reset."));
			sleep($seconds);
		}

		curl_close($ch);

		return json_decode($response);
	}
}