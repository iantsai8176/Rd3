<?php
$tri = trim('1110000N1M21100N112M100N0012321N1101MM1NM101221');
$start = 0;
$x = 0;
$y = 0;
$checkArray = array();
strlen($tri);
do{
    $checkStr = substr($tri,$start,1);
    if ($checkStr != 'N' ){
        $checkArray[$x][$y] = $checkStr;
    } else {
        $x ++;
        $y = 0;
    }
    $y++;
    $start++;
}while($checkStr != '');

echo $x.$y;
foreach($checkArray as $row){
    foreach($row as $value){
        echo $value;
    }
    echo "<br>";
}
echo "<br>";
for ($i=0;$i<=$x;$i++) {
    for($j=0;$j<$y;$j++){
        if ($checkArray[$i][$j] == 'M') {

            //左上
            if ($checkArray[$i-1][$j-1] != 'M') {
                $checkArray[$i-1][$j-1] -= 1;
            }
            //正上
            if ($checkArray[$i-1][$j] != 'M') {
                $checkArray[$i-1][$j] -= 1;
            }
            //右上
            if ($checkArray[$i-1][$j+1] != 'M') {
                $checkArray[$i-1][$j+1] -= 1;
            }
            //左
            if ($checkArray[$i][$j-1] != 'M') {
                $checkArray[$i][$j-1] -= 1;
            }
            //右
            if ($checkArray[$i][$j+1] != 'M') {
                $checkArray[$i][$j+1] -= 1;
            }
            //左下
            if ($checkArray[$i+1][$j-1] != 'M') {
                $checkArray[$i+1][$j-1] -= 1;
            }
            //正下
            if ($checkArray[$i+1][$j] != 'M') {
                $checkArray[$i+1][$j] -= '1';
            }
            //右下
            if ($checkArray[$i+1][$j+1] != 'M') {
                $checkArray[$i+1][$j+1] -= '1';
            }
        }
    }
}
// foreach($checkArray as $row){
//     foreach($row as $value){
//         echo $value;
//     }
//     echo "<br>";
// }
for ($i=0;$i<=$x;$i++) {
    for($j=0;$j<$y;$j++){
        echo $checkArray[$i][$j];
    }
    echo "<br>";
}
?>
