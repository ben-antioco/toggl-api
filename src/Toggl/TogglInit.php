<?php

namespace App\Toggl;

class TogglInit
{
	protected $apitoken;

	protected $baseurl;

	protected $errors;

	public function __construct()
	{
		require dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'config.php';

		$this->apitoken = $TOGGL_TOKEN;
		$this->baseurl 	= $TOGGL_BASE_URL;

		$this->errors   = ["response" => "check your config ! :)"];
	}

	public function initConnexion()
	{
		$response = $this->curlConnexion( 'me', $this->options() );

		return $this->initResult( $response );
	}


	public function curlConnexion( $routeUrl, $options )
	{
		$curl = curl_init();

		if (isset($options['data'])) {

			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options['data']));
			$options['headers']['Content-Type'] = 'application/json';
		}

		curl_setopt($curl, CURLOPT_URL, $this->baseurl.DIRECTORY_SEPARATOR.$routeUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt($curl, CURLOPT_CAINFO, __DIR__.DIRECTORY_SEPARATOR.'toggl.crt');
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $options['method']);
		curl_setopt($curl, CURLOPT_USERPWD, "{$this->apitoken}:api_token");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);

		$result = curl_exec($curl);

		if ( $result == FALSE || $result === '' ) {
			return $this->errors;
		}
		else {
			$response = new \stdClass();
			$response->data = json_decode($result, TRUE);
			$response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$response->success = $response->code == 200;

			curl_close($curl);
			
			return $response;
		}
	}

	public function initResult( $response )
	{
		if( $response != '' && isset($response->data) ) {

			$data 		= $response->data['data'];
			$workspaces = [];

			if ( $data['workspaces'] ) {

				foreach ( $data['workspaces'] as $workspace ) {

					$workspaces[] = [
						"id" => $workspace['id'],
						"name" => $workspace['name']
					];
				}
			}

			return [
				"id" 			=> $data['id'],
				"default_wid" 	=> $data['default_wid'],
				"email" 		=> $data['default_wid'],
				"fullname" 		=> $data['fullname'],
				"image_url" 	=> $data['image_url'],
				"workspaces" 	=> $workspaces
			];
		}

		return;
	}

	public function options()
	{
		$options = array(
			'headers' 	=> array(),
			'method' 	=> 'GET',
			'query' 	=> array(),
			'data' 		=> NULL,
		);
		return $options;
	}
}

