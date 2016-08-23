<?php
header("content-type: text/html; charset=utf-8");
$getTestCode = str_split($_GET['map']);
$origenal = $_GET['map'];

$tri = str_split('1110000N1M21100N112M100N0012321N1101MM1NM101221');
$string = '1110000N1M21100N112M100N0012321N1101MM1NM101221';

$long = strlen($origenal);
$countN = substr_count($origenal,'N');
$checkArray = array();
$check = explode('N',$origenal);
$column = strlen($check[0]); //取列數
$row = count($check); //取行數

if (preg_match("/^([0-9A-Z]+)$/",$origenal) == '0'){
    echo '必須是數字和大寫字母';
    exit();
}

if ($countN != $row) {
    echo '空行\'N\'不正確';
    exit();
}

if ($long - $countN != $column * $row ) {
    echo "長度有錯";
    exit();
}

//將地圖塞入二微陣列
$n = 0;
for ($i=0;$i<$row;$i++) {
    for($j=0;$j<$column;$j++){
        if($tri[$n] != "N"){
            $checkArray[$i][$j] = $getTestCode[$n];
        } else {
            $checkArray[$i][$j] = $getTestCode[$n+1];
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
            echo '位置:('.$i.','.$j.')數字炸彈數量錯誤<br>';
            $checkResult = false;
            break;
        }
    }
}
if ($checkResult == false) {
    echo '不符炸彈數量錯誤';
} else {
    echo '符合';
}
echo "<br>";

// foreach($checkArray as $a){
//     foreach($a as $b){
//         echo $b;
//     }
//     echo "<br>";
// }
?>
