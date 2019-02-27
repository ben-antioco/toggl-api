<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglInit.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglProject.php';

$datas    = ["response" => "check your config ! :)"];
$toggl          = new TogglInit();

if ( $toggl ) {

    $init = $toggl->initConnexion();

    if( $init ) {

        if( $init['workspaces'] )
        {
            $projects = new TogglProject();
            $datas = $projects->getProjects( $init['workspaces'] );
        }
    }
}

echo json_encode( $datas );

exit;