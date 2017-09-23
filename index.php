<?php
$access_token = 'QoYlV2SNWU962nXM/7iAkLLD73bvlzZFpxvqi29SgqPeeKDt0xFNVEoobjqxNkh8cqRPM5FjqxmeQX+cZxv4Vwg6+SM2iVjNqR7CLWrhVM5w12OR8mTQdyiCSNj0dE53mvOE/3GHgW8keruuXF82AgdB04t89/1O/w1cDnyilFU=';
$host = "ec2-107-22-211-182.compute-1.amazonaws.com";
$user = "mmdkvvqziulstc";
$pass = "e10240d71df70c411f5201bc37491e9091491ff276b8d8b66f8e507ea5b7dc22";
$db = "dcv361109jo6fh";
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");

function showtime($time)
{
	$date = date("Y-m-d");
	$h = split(":", $time);
	if ($h[1] < 15)
	{
		$h[1] = "00";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:0:00' and '$date $h[0]:15:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 15 && $h[1] < 30)
	{
		$h[1] = "15";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:15:01' and '$date $h[0]:30:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 30 && $h[1] < 45)
	{
		$h[1] = "30";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:30:01' and '$date $h[0]:45:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 45)
	{
		$h[1] = "45";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:45:01' and '$date $h[0]:59:59' order by \"DATETIME\" desc limit 1";
	}
	

	return array(
		$h[0] . ":" . $h[1],
		$selectbydate
	);
}

// database

$dbconn = pg_connect("host=" . $GLOBALS['host'] . " port=5432 dbname=" . $GLOBALS['db'] . " user=" . $GLOBALS['user'] . " password=" . $GLOBALS['pass']) or die('Could not connect: ' . pg_last_error());

// Get POST body content

$content = file_get_contents('php://input');

// Parse JSON

$events = json_decode($content, true);

// Validate parsed JSON data

$Light = file_get_contents('https://api.thingspeak.com/channels/262354/fields/1/last.txt');
$HUM = file_get_contents('https://api.thingspeak.com/channels/262354/fields/2/last.txt');
$TEM = file_get_contents('https://api.thingspeak.com/channels/262354/fields/3/last.txt');
$aba = ('https://i.imgur.com//yuRTcoH.jpg');

// convert

$sqlgetlastrecord = "select * from weatherstation order by \"DATETIME\" desc limit 1";

if (!is_null($events['events']))
{

	// Loop through each event

	foreach($events['events'] as $event)
	{

		// Reply only when message sent is in 'text' format

		if ($event['type'] == 'message' && $event['message']['type'] == 'text')
		{

			// Get text sent

			$text = $event['message']['text'];

			// Get replyToken

			$replyToken = $event['replyToken'];

			// Build message to reply back

			$messages = ['type' => 'text', 'text' => "ไม่มีคำสั่งที่คุณพิมพ์ " . "\n" . "กรุณาพิมพ์ [help] เพื่อดูเมนู" . "\n" . "[help] Show Status"

			// "text"

			];
			if (strtoupper($text) == "HELP")
			{
				$messages = ['type' => 'text', 'text' => "พิมพ์ [1]เพื่อดูสถานะอากาศปัจจุบัน" . "\n"  . "พิมพ์ [ภาพ] เพื่อดูรูปล่าสุด"."\n"."พิมพ์ [ภาพ 00:00] ภาพตามช่วงเวลา"."\n"."#ภาพจะแสดงทุกๆ 15 นาที"];
			}

			if (trim($text) == "1")
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . $Light . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n" . "พิมพ์ [help] เพื่อดูเมนู"];
			}

			if (trim($text) == "แสง")
			{
				if ($Light > 900)
				{
					$messages = ['type' => 'text', 'text' => "ค่าแสง : " . "มีแสงน้อย \t" . " กลางคืน"];
				}
			}

			if (trim($text) == "แสง")
			{
				if ($Light < 700)
				{
					$messages = ['type' => 'text', 'text' => "ค่าแสง : " . "มีแสงพอประมาณ" . " ตอนเย็น"];
				}
			}

			if (trim($text) == "แสง")
			{
				if ($Light < 100)
				{
					$messages = ['type' => 'text', 'text' => "ค่าแสง : " . "มีแสง" . " เช้า"];
				}
			}

			

			

			if (trim($text) == "2")
			{
				$messages = ['type' => 'text', 'text' => "" . "\n"];
			}

			if (strtoupper($text) == "HI")
			{
				$messages = ['type' => 'text', 'text' => "hello"];
			}

			if ($text == "รูป")
			{
				$messages = ['type' => 'image', 'originalContentUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg", 'previewImageUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg"];
			}

			if ($text == "ภาพ")
			{
				$rs = pg_query($dbconn, $sqlgetlastrecord) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}

				$messages = ['type' => 'image', 'originalContentUrl' => $templink, 'previewImageUrl' => $templink];
			}

			$textSplited = split(" ", $text);
			if ($textSplited[0] == "ภาพ")
			{
				$dataFromshowtime = showtime($textSplited[1]);
				$rs = pg_query($dbconn, $dataFromshowtime[1]) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}

				//$messages = ['type' => 'text', 'text' => "HI $dataFromshowtime[0] \n$dataFromshowtime[1] \n$templink"

				$messages = [
				'type' => 'image',
				'originalContentUrl' => $templink,
					'previewImageUrl' => $templink

				];
			}

			if ($text == "ภาพ1")
			{
				$rs = pg_query($dbconn, $sqlgetlastrecord) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}

				$messages = ['type' => 'image', 'originalContentUrl' => $templink, 'previewImageUrl' => $templink];
			}
			if ($text == "qw")
			{
				$messages = ['type' => 'location','title'=> 'my location','address'=> '〒150-0002 東京都渋谷区渋谷２丁目２１−１',
				'latitude'=> 35.65910807942215,'longitude'=> 139.70372892916203];
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
			$data = ['replyToken' => $replyToken, 'messages' => [$messages], ];
			$post = json_encode($data);
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $access_token
			);
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
echo $date;

