<?php

require '../APIJet/APIJet.php';

use APIJet\APIJet;

APIJet::registerAutoload();

$app = new APIJet();
$app->run();

\APIJet\Response::render();
