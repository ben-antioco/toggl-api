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
		$response = $this->curlConnexion( "workspaces/{$workSpaceId}/projects", $this->options() );

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