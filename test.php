<?php 
echo "Today is " . date("Y-m-d") . "<br>";


 function showtime($time)
{
    echo date("Y-m-d")." ". $time;
}

for ($i=0; $i < 12; $i++) { 
    # code...
    showtime($i)
}


?>