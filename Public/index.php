<?php

require '../APIJet/APIJet.php';

use APIJet\APIJet;

APIJet::registerAutoload();

APIJet::runApp();

\APIJet\Response::render();
