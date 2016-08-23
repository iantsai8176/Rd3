<?php
header("content-type: text/html; charset=utf-8");
$getTestCode = str_split($_GET['map']);
$origenal = $_GET['map'];

$tri = str_split('1110000N1M21100N112M100N0012321N1101MM1NM101221');
$string = '1110000N1M21100N112M100N0012321N1101MM1NM101221';

$long = strlen($origenal);
$countN = substr_count($origenal,'N');
$bombCount = substr_count($origenal,'M') + substr_count($origenal,'m');
$checkArray = array();
$check = explode('N',$origenal);
$column = strlen($check[0]); //取列數
$row = count($check); //取行數
$k = 0;
$checkResult = true;
do{
    if (strlen($check[$k]) != $column) {
        $returnRespond = true;
        break;
    }
    $k++;
}while($row > $k);

if (preg_match("/^([0-9A-Z]+)$/",$origenal) == '0'){
    echo '炸彈必須是大寫字母或有意外字元存在<br>';
    $checkResult = false;
}
if ($returnRespond == true) {
    echo "空行第'$k'個N不正確<br>";
    $checkResult = false;
}

if ($long - $countN != $column * $row ) {
    echo "長度不符<br>";
    $checkResult = false;
}

if (10 * 10 != $column * $row ) {
    echo "地圖為'$column'*'$row',非10*10<br>";
    $checkResult = false;
}

if ($bombCount < 40) {
    echo "炸彈數量過少,只有'$bombCount'顆<br>";
    $checkResult = false;
}
//將地圖塞入二微陣列
$n = 0;
for ($i=0;$i<$row;$i++) {
    for($j=0;$j<$column;$j++){
        if($getTestCode[$n] != "N"){
            $checkArray[$i][$j] = $getTestCode[$n];
        } else {
            $j -- ;
        }
        $n++;
    }
}

//檢查是否正確
for ($i=0;$i<$row;$i++) {
    for($j=0;$j<$column;$j++){
        $count_bomb = '0';
        if ($checkArray[$i][$j] == 'M') {
            continue;
        }

        //左上
        if ($checkArray[$i-1][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //正上
        if ($checkArray[$i-1][$j] == 'M') {
            $count_bomb += 1;
        }
        //右上
        if ($checkArray[$i-1][$j+1] == 'M') {
            $count_bomb += 1;
        }
        //左
        if ($checkArray[$i][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //右
        if ($checkArray[$i][$j+1] == 'M') {
            $count_bomb += 1;
        }
        //左下
        if ($checkArray[$i+1][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //正下
        if ($checkArray[$i+1][$j] == 'M') {
            $count_bomb += 1;
        }
        //右下
        if ($checkArray[$i+1][$j+1] == 'M') {
            $count_bomb += 1;
        }

        if ($checkArray[$i][$j] != $count_bomb){
            echo '位置:('.$i.','.$j.')數字炸彈數量錯誤,'."正確為'$count_bomb'<br>";
            $checkResult = false;
            break;
        }
    }
}
if ($checkResult == false) {
    echo '不符合';
} else {
    echo '符合';
}
