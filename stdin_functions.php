<?php

function fetchAllStdin(){
    $lines = array();
    while ($line = fgets(STDIN)) {
        $lines[] = $line;
    }
    return $lines;
}

function mapLine($line, $keyMap = array(), $delimiter = " "){
    $tmp = explode($delimiter,
        str_replace( array("\r\n","\r","\n"), '', trim($line) ));

    $_tmp = array();
    foreach($keyMap as $i => $key){
        $_tmp[$key] = $tmp[$i];
    }
    return $_tmp;
}

$linesAll = fetchAllStdin();
$stock = mapLine($linesAll[0], array(500, 100, 50, 10));





$_v = mapLine($linesAll[1], array('line_num'));

$_lines = array_slice($linesAll, 2, $_v['line_num']);
$records = array();
foreach($_lines as $_line){
    $records[] = mapLine($_line, array('price', 500, 100, 50, 10));
}

foreach($records as $record){

}


for($i = 1; $i <= $n; $i++){
    //お釣り金額を算出

    $stock = mapLine($linesAll[0], array(500, 100, 50, 10));


    $s = getVals();
    $price = $s[0];
    $payment[500] = $s[1];
    $payment[100] = $s[2];
    $payment[50] = $s[3];
    $payment[10] = $s[4];
    $types = array(500, 100, 50, 10);
    $paymentSum = 0;
    foreach($types as $type){
        $paymentSum += $type * $payment[$type];
    }
    $rest = $paymentSum - $price;//支払い不足は考慮しない

    //お釣りの支払い方を算出
    foreach($types as $type){
        $changeNum[$type] = floor($rest / $type);//changeが100より低い場合は0になる
        if($changeNum[$type] > $stock[$type]){
            $changeNum[$type] = $stock[$type];
        }
        $stock[$type] -= $changeNum[$type];//在庫減算
        $rest -= $type * $changeNum[$type];//残りのお釣り金額を算出
        if($type == 100
            && $rest >= 100){
                break;//エラー（50円玉と10円玉で100円以上のお釣りは返せない）→$rest > 0によりimpossibleを出力
        }
    }
    if($rest > 0){
        echo "impossible";
    }else{
        echo "{$changeNum[500]} {$changeNum[100]} {$changeNum[50]} {$changeNum[10]}";
    }
    if($i < $n){
        echo "\n";
    }
}//end for(行)

?>
