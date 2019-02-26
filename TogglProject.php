<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'TogglInit.php';

class TogglProject extends TogglInit
{
	public function getProjects( array $workspaces ):array
	{
		$allProjects = [];

		foreach( $workspaces as $worspace ) {

            $projects = new TogglProject();
            $allProjects[] = $this->getProjectsOnWorkspace( $worspace['id'] );
		}
		
		return $allProjects;
	}

	public function getProjectsOnWorkspace( string $workSpaceId ):array
	{
		$options 	= $this->options();

		// Set the CURL variables.
		$curl = curl_init();

		// Include post data.
		if (isset($options['data'])) {

			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options['data']));
			$options['headers']['Content-Type'] = 'application/json';
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_URL, "{$this->baseurl}/workspaces/{$workSpaceId}/projects");
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // Needed since Toggl's SSL fails without this.
		//curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $options['method']);
		curl_setopt($curl, CURLOPT_USERPWD, "{$this->apitoken}:api_token");
		// Build and format the headers.
		/*
		foreach (array_merge($this->getHeaders(), $options['headers']) as $header => $value) {
		$options['headers'][$header] = $header . ': ' . $value;
		}
		*/
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

		return $this->projectResult( $response );

	}

	public function projectResult( $response )
	{
		if( $response->data ) {

			return $response->data;
		}
		exit;
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