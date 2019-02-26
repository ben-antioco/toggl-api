<?php

class TogglInit
{
	protected $apitoken;

	protected $baseurl;

	public function __construct()
	{
		require __DIR__.DIRECTORY_SEPARATOR.'config.php';

		$this->apitoken = $TOGGL_TOKEN;
		$this->baseurl 	= $TOGGL_BASE_URL;
	}

	public function initConnexion()
	{
		$options 	= $this->options();
		$curl 		= curl_init();

		if (isset($options['data'])) {

			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options['data']));
			$options['headers']['Content-Type'] = 'application/json';
		}

		curl_setopt($curl, CURLOPT_URL, "{$this->baseurl}/me");

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // Needed since Toggl's SSL fails without this.
		//curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $options['method']);
		curl_setopt($curl, CURLOPT_USERPWD, "{$this->apitoken}:api_token");

		curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
		// Perform the API request.
		$result = curl_exec($curl);

		if ($result == FALSE) {
			//throw new TogglException(curl_error($curl));

			return false;
		}
		// Build the response.
		$response = new stdClass();
		$response->data = json_decode($result, TRUE);
		$response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$response->success = $response->code == 200;
		curl_close($curl);

		return $this->initResult( $response );
	}

	public function initResult( $response )
	{
		if( $response->data ) {

			$data 		= $response->data['data'];
			$workspaces = [];

			if ( $data['workspaces'] ) {

				foreach ( $data['workspaces'] as $key => $workspace ) {

					$workspaces[] = [
						"id" => $workspace['id'],
						"name" => $workspace['name']
					];
				}
			}

			return $result = [
				"id" => $data['id'],
				"default_wid" => $data['default_wid'],
				"email" => $data['default_wid'],
				"fullname" => $data['fullname'],
				"image_url" => $data['image_url'],
				"workspaces" => $workspaces
			];
		}

		return;
	}

	public function options()
	{
		$options = array(
			'headers' => array(),
			'method' => 'GET',
			'query' => array(),
			'data' => NULL,
		);
		return $options;
	}
}

