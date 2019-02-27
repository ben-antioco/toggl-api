<?php

namespace App\Toggl;

//require_once __DIR__.DIRECTORY_SEPARATOR.'TogglInit.php';

class TogglProject extends TogglInit
{
	public function getProjects( ?array $init )
	{
		$allProjects = [];

		if ( $init && isset( $init['workspaces'] ) ) {

			foreach( $init['workspaces'] as $worspace ) {

				//$projects = new TogglProject();
				$allProjects[] = $this->getProjectsOnWorkspace( $worspace['id'] );
			}

			return $allProjects;
		}
		
		return $this->errors;
	}

	public function getProjectsOnWorkspace( string $workSpaceId ):array
	{
		$response = $this->curlConnexion( "workspaces/{$workSpaceId}/projects", $this->options() );

		if ($response ) {

			return $this->projectResult( $response );
		}

		return $this->errors;
	}

	public function projectResult( $response )
	{
		if( $response->data ) {

			return $response->data;
		}
		else {
			return $this->errors;
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