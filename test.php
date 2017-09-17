<?php 
echo "Today is " . date("Y-m-d") . "<br>";

$date=date("Y-m-d");
function showtime($time){
    $h =split(":",$time);
    if($h[1]>=0&&$h[1]<15)
        $h[1]=0;
    else if($h[1]>=15&&$h[1]<30)
    $h[1]=15;
    else if($h[1]>=30&&$h[1]<45)
    $h[1]=30;
    else if($h[1]>=45&&$h[1]<60)
    $h[1]=45;
    else if($h[1]>=60)
    $h[1]=60;
    echo $h[0] . "   ".$h[1];
}

showtime($GET["time"]);
// for($i=1;$i<13;$i++)
// {
//     echo "$date $i";
// }

?>