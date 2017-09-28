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

$Light = file_get_contents('https://api.thingspeak.com/channels/331361/fields/3/last.txt');
$water = file_get_contents('https://api.thingspeak.com/channels/331361/fields/4/last.txt');
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

			$messages = ['type' => 'text', 'text' => "ไม่มีคำสั่งที่คุณพิมพ์ "."\n"."พิมพ์ตัวอักษรตามที่กำหนดให้" ."\n" ."\n". "[help] เพื่อดูเมนู" 

			// "text"

			];
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "HELP")
			{
				$messages = ['type' => 'text', 'text' => "พิมพ์ตัวอักษรตามที่กำหนดให้"."\n"."\n"."[อากาศ] เพื่อดูสถานะอากาศปัจจุบัน" . "\n"  . "[ภาพ] เพื่อดูภาพล่าสุด"."\n"."[ภาพ 00:00] พิมพ์ภาพตามด้วยเวลา"."\n"."#อุปกรณ์จะถ่ายรูปทุกๆ 15 นาที"];
			}

			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 100)
				{
				if($water>240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "เริ่มมืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนไม่ตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 100)
				{
				if($water<240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "เริ่มมืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : มีฝนประปลาย"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 100)
				{
				if($water<180)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "เริ่มมืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกไม่หนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 100)
				{
				if($water<140)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "เริ่มมืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 100)
				{
				if($water<100)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "เริ่มมืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกหนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 150)
				{
				if($water>240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนไม่ตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 150)
				{
				if($water<240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน :"."มีฝนประปลาย"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 150)
				{
				if($water<180)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน :"."ฝนตกไม่หนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 150)
				{
				if($water<140)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน :"."ฝนตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light > 150)
				{
				if($water<100)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มืด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน :"."ฝนตกหนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 70)
				{
				if($water>240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดดบ้างเป็นบางจุด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนไม่ตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 70)
				{
				if($water<240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดดบ้างเป็นบางจุด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : มีฝนประปลาย"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 70)
				{
				if($water<180)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดดบ้างเป็นบางจุด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกไม่หนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 70)
				{
				if($water<140)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดดบ้างเป็นบางจุด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 70)
				{
				if($water<100)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดดบ้างเป็นบางจุด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกหนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 30)
				{
				if($water>240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนไม่ตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 30)
				{
				if($water<240)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : มีฝนประปลาย"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 30)
				{
				if($water<180)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกไม่หนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 30)
				{
				if($water<140)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "อากาศ"){
				if($Light < 30)
				{
				if($water<100)
			{
				$messages = ['type' => 'text', 'text' => "สถานที่ : " . "มหาวิทยาลัยวลัยลักษณ์" . "\n" . "ความสว่างของแสง : " . "มีแดด" . "\n" . "อุณหภูมิ C :" . $TEM . "\n" . "ความชื้น :" . $HUM . " %" . "\n"."ฝน : ฝนตกหนัก"."\n"."\n" . "[help] เพื่อดูเมนู"];
			}
			}
			}
			
			//EndCase

			if (trim(strtoupper($text)) == "HI")
			{
				$messages = ['type' => 'text', 'text' => "hello"];
			}

			if ($text == "รูป")
			{
				$messages = ['type' => 'image', 'originalContentUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg", 'previewImageUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg"];
			}
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "INFO")
			{
				$messages = ['type' => 'text', 'text' => "มหาวิทยาลัยวลัยลักษณ์เป็นมหาวิทยาลัยของรัฐ และอยู่ในกำกับของรัฐบาลที่ได้รับพระมหากรุณาธิคุณจากพระบาทสมเด็จพระเจ้าอยู่หัว พระราชทานชื่ออันเป็นสร้อยพระนามในสมเด็จพระเจ้าลูกเธอ เจ้าฟ้าจุฬาภรณวลัยลักษณ์อัครราชกุมารี" ."\n"."อ่านเพิ่มเติม: https://www.wu.ac.th"];
			}
				
			if ( ereg_replace('[[:space:]]+', '', trim($text)) == "ภาพ")
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
				$qcount=0;
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
					$qcount++;
				}

				//$messages = ['type' => 'text', 'text' => "HI $dataFromshowtime[0] \n$dataFromshowtime[1] \n$templink"
				if ($qcount > 0){
				$messages = [
				'type' => 'image',
				'originalContentUrl' => $templink,
					'previewImageUrl' => $templink

				];}
				else {
					$messages = [
						'type' => 'image',
						'originalContentUrl' => "https://imgur.com/aOWIijh.jpg",
							'previewImageUrl' => "https://imgur.com/aOWIijh.jpg" 
		
						];

				}

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
			if ($text == "map")
			{
				$messages = ['type' => 'location','title'=> 'my location','address'=> 'เคลิ้ม',
				'latitude'=> 8.652311,'longitude'=> 99.918031];
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

