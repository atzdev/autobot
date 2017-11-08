<?php

//echo "Hello everyone. Are you there?";
//echo "bla bla ...";
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

use \LINE\LINEBot;

use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'jra+Ecst9ju1KRTMl6KLCQl6uP7jag8mayRDQxQF68eWfUePohFI5xAB3Y12s8pziavYhr2nb50J5xC5Ie+SK3+llrDSmkHsrOOqsKceCJnVk9dUXXnrw+0SoRTolRNPzybUUG+9f1yE80sIqDrzYQdB04t89/1O/w1cDnyilFU=';

$channel_secret = '5c0528b536650228907f3e4cce3bde13';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if(!is_null($events['events'])) {
	// Loop through each event
	foreach($events['events'] as $event) {



		// Line API send a lot of event type, we interested in message only
		if($event['type'] == 'message') {
			// Get replyToken
			$replyToken = $event['replyToken'];

			switch ($event['message']['type']) {
				case 'text':

				

					$respMessage = 'Hello, your message is : '. $event['message']['text'];

					/*$httpClient = new CurlHTTPClient($channel_token);
					$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
					$TextMessageBuilder = new TextMessageBuilder($respMessage);
					$response = $bot->replyMessage($replyToken, $TextMessageBuilder);*/

					break;

				case 'image':
					$messageID = $event['message']['id'];
					$respMessage = 'Hello, your image ID is '. $messageID;
					
					break;
				case 'sticker':
					$messageID = $event['message']['packageId'];
					//Reply message
					$respMessage = 'Hello, your Sticker Package ID is '.$messageID;
					break;
				case 'video':
					$messageID = $event['message']['id'];

					// Create video file on server.
					$fileID = $event['message']['id'];
					$response = $bot->getMessageContent($fileID);
					$fileName = 'linebot.mp4';
					$file = fopen($fileName, 'w');
					fwrite($file, $response->getRawBody());

					// replyMessage
					$respMessage = 'Hello, your video ID is '. $messageID;
					break;
				default:
					$respMessage = 'Please send image or text only ^^';
					break;
				
			}

			$httpClient = new CurlHTTPClient($channel_token);
			$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
			$TextMessageBuilder = new TextMessageBuilder($respMessage);
			$response = $bot->replyMessage($replyToken, $TextMessageBuilder);
		}
	}
}






























