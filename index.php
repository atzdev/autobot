<?php

//echo "Hello everyone. Are you there?";
//echo "bla bla ...";
require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

use \LINE\LINEBot;

use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = 'xa0m0qH9fn+pbwz4J82W6QqCO9xuP1c/M1yRgvq8bwiEkEupFRuvV49vw1KD+Y20iavYhr2nb50J5xC5Ie+SK3+llrDSmkHsrOOqsKceCJlNZdkqKir4JHayUiC5EFGFljG6+N8jOfdBoMVuKyuRXgdB04t89/1O/w1cDnyilFU=';

$channel_secret = '5c0528b536650228907f3e4cce3bde13';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);
dd($events);
if(!is_null($events['events'])) {
	// Loop through each event
	foreach($events['events'] as $event) {
		// Line API send a lot of event type, we interested in message only
		if($event['type'] == 'message') {
			switch ($event['message']['type']) {
				case 'text':
					// Get replyToken
					$replyToken = $event['replyToken'];

					// Reply message
					$respMessage = 'Hello, your message is '. $event['message']['text'];

					$httpClient = new CurlHTTPClient($channel_token);
					$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
					$TextMessageBuilder = new TextMessageBuilder($respMessage);
					$response = $bot->replyMessage($replyToken, $TextMessageBuilder);

					break;
				
				
			}
		}
	}
}

echo 'OK';




























