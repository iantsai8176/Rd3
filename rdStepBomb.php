<script type="text/javascript" src="jquery.js"></script>
<?php
header("content-type: text/html; charset=utf-8");
$iTime = microtime(true);
srand ((float)microtime()*1000000);
$array[] = array();
for ($i=0;$i<100;$i++) {
        $bomb[$i] = $i;
}

shuffle($bomb);

for ($i=0;$i<10;$i++) {
    for ($j=0;$j<10;$j++) {
        $array[$i][$j] = $bomb[$n];
        if ($array[$i][$j] <=40) {
            $array[$i][$j] = 'M';
        }
        $n ++;
    }
}
for ($i=0;$i<10;$i++) {
    for ($j=0;$j<10;$j++) {
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
echo "<script>var array =".$array."</script>";
for($i=0;$i<10;$i++){
    for($j=0;$j<10;$j++) {
        $num = $array[$i][$j];
        $id = $i.$j;
        echo "<input type=button id=$id style=\"WIDTH:50px;HEIGHT:50px\" value=\"  \" onclick=\"myFunction($i,$j,'$num')\">";
    }
    echo "<br>";
}
 echo "<table border = '1'>";
foreach ($array as $a) {
    echo "<tr>";
    foreach ($a as $b) {
        echo "<td>$b</td>";
    }
    echo "</tr>";
}
echo "</table>";
echo "<input type =\"button\" onclick=\"javascript:location.href='rd3.php'\" value=\"重新開始\"></input>";
?>
<script>
function myFunction(i,j,k) {
    $(document).ready(function(){
        if (event.button == 0) {
            $("#"+i+j).val(k);
            if ($("#"+i+j).val() == 'M') {
                alert("You step on Bomb");
            }
        }
        if (event.button == 2) {
            alert("55");
        }
    });


    document.oncontextmenu = function(){
        window.event.returnValue=false; //將滑鼠右鍵事件取消
    }
}




</script>

