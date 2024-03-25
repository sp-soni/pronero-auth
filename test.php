<?php

require_once 'src/Pronero.php';

$pronero = new  ProneroAuth\Pronero('c081547e59a076e978ea9a6bac147a0c');

//projectInfo
$apiResponse1 = $pronero->projectInfo();

//login
//$apiResponse2 = $pronero->login('superadmin@pronero.in','Meta@190712');

//forgotPassword
// $apiResponse3 = $pronero->forgotPassword('superadmin@pronero.in');

$pronero->debug($apiResponse1);
