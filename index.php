<?php
$access_token = 'QoYlV2SNWU962nXM/7iAkLLD73bvlzZFpxvqi29SgqPeeKDt0xFNVEoobjqxNkh8cqRPM5FjqxmeQX+cZxv4Vwg6+SM2iVjNqR7CLWrhVM5w12OR8mTQdyiCSNj0dE53mvOE/3GHgW8keruuXF82AgdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
$Light = file_get_contents('https://api.thingspeak.com/channels/262354/fields/1/last.txt');

//convert
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			
			
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text 

					// "text"
			];
			if($text == "แสง"){		
				$messages = [
				'type' => 'text',
				'text' => $Light
					
				
			];
				
				
			}	
			if($text == "HI"){
                            
				$messages = [
				'type' => 'text',
				'text' => "hello"
			];

			}
			if($text == "รูป"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg",
    				'previewImageUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg"

			];	
			}
			if($text == "ภาพ"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com//WOwfu0A.jpg",
    				'previewImageUrl' => "https://i.imgur.com//WOwfu0A.jpg"

			];	
			}
			if($text == "ภาพแซน"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com/kaWhCJl.jpg",
    				'previewImageUrl' => "https://i.imgur.com/kaWhCJl.jpg"

			];	
			}
				if($text == "ภาพ 1"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com/kaWhCJl.jpg",
    				'previewImageUrl' => "https://i.imgur.com/kaWhCJl.jpg"
			];	
			}
			
			/*if($text == "image"){
                            
				$messages = [
				$img_url = "http://sand.96.lt/images/q.jpg";
				$outputText = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($img_url, $img_url);
				$response = $bot->replyMessage($event->getReplyToken(), $outputText);
			
			];	
			}*/
						
			
			

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
