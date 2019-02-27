<?php

require_once 'vendor/autoload.php';

/*
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglInit.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'TogglProject.php';
*/

use App\Toggl\{
    TogglInit,
    TogglProject
};

$toggl    = new TogglInit();
$init     = $toggl->initConnexion();

$projects = new TogglProject();
$datas = $projects->getProjects( $init );

echo json_encode( $datas );

exit;