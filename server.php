<?php
header('Access-Control-Allow-Origin: *');
include ('db_connect.php');
include ('functions.php');

$req = $_REQUEST['REQUEST_TYPE'];

switch($req)
{
case 'SIGNUP':
echo SIGNUP($conn);
break;

case 'LOGIN':
echo LOGIN($conn);
break;

case 'FORGET_PASSWORD':
echo FORGET_PASSWORD($conn);
break;

case 'ACCOUNT_DELETE':
echo ACCOUNT_DELETE($conn);
break;

case 'ACCOUNT_UPDATE':
echo ACCOUNT_UPDATE($conn);
break;

case 'ACCOUNT_VIEW':
echo ACCOUNT_VIEW($conn);
break;
 
case 'REQUEST_BLOOD':
echo REQUEST_BLOOD($conn);
break;

case 'REQUEST_UPDATE':
echo REQUEST_UPDATE($conn);
break;

case 'ALL_REQUEST_VIEW':
echo ALL_REQUEST_VIEW($conn);
break;

case 'REQUEST_ACCEPT':
echo REQUEST_ACCEPT($conn);
break;

case 'REQUEST_RESEND':
echo REQUEST_RESEND($conn);
break;

case 'REQUEST_VIEW':
echo REQUEST_VIEW($conn);
break;

case 'REQUEST_DELETE':
echo REQUEST_DELETE($conn);
break;
 
case 'DONATE_BLOOD':
echo DONATE_BLOOD($conn);
break;

case 'DONATE_DELETE':
echo DONATE_DELETE($conn);
break;

case 'DONATE_VIEW':
echo DONATE_VIEW($conn);
break;

case 'DONATE_UPDATE':
echo DONATE_UPDATE($conn);
break;

case 'CARD':
echo CARD($conn);
break;

case 'CARD_DELETE':
echo CARD_DELETE($conn);
break;
}

include ('db_close.php');

?>