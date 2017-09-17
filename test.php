<?php 
echo "Today is " . date("Y-m-d") . "<br>";

$date=date("Y-m-d");
function showtime($time){
    $h =split(":",$time);
    echo $h[0] . "   ".$h[1];
}

showtime("24:55");
// for($i=1;$i<13;$i++)
// {
//     echo "$date $i";
// }

?>