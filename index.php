<?php

require_once('./vendor/autoload.php');

// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

//Token
$channel_token = 'jra+Ecst9ju1KRTMl6KLCQl6uP7jag8mayRDQxQF68eWfUePohFI5xAB3Y12s8pziavYhr2nb50J5xC5Ie+SK3+llrDSmkHsrOOqsKceCJnVk9dUXXnrw+0SoRTolRNPzybUUG+9f1yE80sIqDrzYQdB04t89/1O/w1cDnyilFU=';
$channel_secret = '5c0528b536650228907f3e4cce3bde13';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if(!is_null($events['events'])) {

	// Loop through each event
	foreach($events['events'] as $event) {

		// Line API send a lot of event type, we interested in message only.
		if($event['type'] == 'message' && $event['message']['type'] == 'text') {

			//Get replyToken
			$replyToken = $event['replyToken'];

			//Split message then keep it in database.
			$appointments = explode(',', $event['message']['text']);

			if(count($appointments) == 2) {
				$host = 'ec2-184-72-255-211.compute-1.amazonaws.com';
				$dbname = 'd42gpikgni8fd5';
				$user = 'vlpqdlcykflbpx';
				$pass = '92eff834a65004f36dea77d0f95d61b9d2622751936bf02118908e20ba08005d';

				$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);

				$params = array(
					'time' => $appointments[0],
					'content' => $appointments[1],
				);
				$chk_type = $appointments['0'];
				switch ($chk_type) {
					case 'check':
						$respMessage = 'Check your appointments.';	
						break;
					
					default:
						$statement = $connection->prepare("INSERT INTO appointments (time, content)VALUES(:time,:content)");
							$result = $statement->execute($params);
							$respMessage = 'Your appointments has saved';
						break;
				}
				
			} else {
				$respMessage = 'You can send appointment like this "12.00,House keeping."';
			}

			$httpClient = new CurlHTTPClient($channel_token);
			$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

			$TextMessageBuilder = new TextMessageBuilder($respMessage);
			$response = $bot->replyMessage($replyToken, $TextMessageBuilder);


		}
		

		

	}
}






























