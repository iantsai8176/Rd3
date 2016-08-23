<?php
$iTime = microtime(true);
$array[] = array();
for ($i=0;$i<3000;$i++) {
        $bomb[$i] = $i;
}

shuffle($bomb);

for ($i=0;$i<50;$i++) {
    for ($j=0;$j<60;$j++) {
        $array[$i][$j] = $bomb[$n];
        if ($array[$i][$j] <=1200) {
            $array[$i][$j] = 'M';
        }
        $n ++;
    }
}

for ($i=0;$i<50;$i++) {
    for ($j=0;$j<60;$j++) {
        $count_bomb = "0"; //**
        if ($array[$i][$j] == 'M') {
            continue;
        }

        //左上
        if ($array[$i-1][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //正上
        if ($array[$i-1][$j] == 'M') {
            $count_bomb += 1;
        }
        //右上
        if ($array[$i-1][$j+1] == 'M') {
            $count_bomb += 1;
        }
        //左
        if ($array[$i][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //右
        if ($array[$i][$j+1] == 'M') {
            $count_bomb += 1;
        }
        //左下
        if ($array[$i+1][$j-1] == 'M') {
            $count_bomb += 1;
        }
        //正下
        if ($array[$i+1][$j] == 'M') {
            $count_bomb += 1;
        }
        //右下
        if ($array[$i+1][$j+1] == 'M') {
            $count_bomb += 1;
        }

        $array[$i][$j] = $count_bomb;
    }
}
// $c = 0;
// echo "<table border = '1'>";
// foreach ($array as $a) {
//     echo "<tr>";
//     foreach ($a as $b) {
//         echo "<td>$b</td>";
//     }
//     echo "</tr>";
// }
// echo "</table>";

foreach ($array as $a) {
    foreach ($a as $b) {
        echo $b;
    }
    $c += 1;
    if ($c <50) {
        echo "N";
    }
}
echo "<br>";
// $iTime2 = microtime(true);
// echo $iTime2-$iTime;