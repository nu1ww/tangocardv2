<?php

$base = realpath(dirname(__FILE__) . '/..');
require $base."/src/TangoCardBase.php";
require $base."/src/TangoCard.php";

$tangoCard = new TangoCard('FidoTrackTest','rsvDPOJiyv5Dw2QKhmY1Ig9ukTeI2xJskZupo7VCN3LDeVFhkgJCrhNs');
//'PLATFORM_ID' & 'PLATFORM_KEY' will be provided by TangoCard
$tangoCard->setAppMode("sandbox"); 
//By default app mode is production. Change it to sandbox if needed. Skip     
 //this line in production

 $customer="Nuwan";
 $email='nu1.rock@gmail.com';
 $accountIdentifier='nuwanww';
   $tangoCard->createAccount($customer, $accountIdentifier, $email);
 
 $data=$tangoCard->getAccountInfo($customer, $accountIdentifier);
 
 var_dump( $data);