<?php
require_once('./twilio-php/Services/Twilio.php');
require_once('./randos.php');
require_once('./config.php');

// An identifier for your app - can be anything you'd like
$appName = 'TwilioChatDemo';

// choose a random username for the connecting user
$identity = randomUsername();

// A device ID is passed as a query string parameter to this script
$deviceId = $_GET['device'];

// The endpoint ID is a combination of the above
$endpointId = $appName . ':' . $identity . ':' . $deviceId;

// Create access token, which we will serialize and send to the client
$token = new Services_Twilio_AccessToken(
    $TWILIO_ACCOUNT_SID, 
    $TWILIO_API_KEY, 
    $TWILIO_API_SECRET, 
    3600, 
    $identity
);

// Create IP Messaging grant
$ipmGrant = new Services_Twilio_Auth_IpMessagingGrant();
$ipmGrant->setServiceSid($TWILIO_IPM_SERVICE_SID);
$ipmGrant->setEndpointId($endpointId);

// Add grant to token
$token->addGrant($ipmGrant);

// return serialized token and the user's randomly generated ID
echo json_encode(array(
    'identity' => $identity,
    'token' => $token->toJWT(),
));