<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglInit.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglProject.php';

$toggl          = new TogglInit();
$init           = $toggl->initConnexion();
$allProjects    = ["response" => "check your config ! :)"];

if( $init ) {

    if( $init['workspaces'] )
    {
        $projects = new TogglProject();
        $allProjects = $projects->getProjects( $init['workspaces'] );
    }
}

echo json_encode( $allProjects );

exit;