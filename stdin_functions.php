<?php

function fetchAllStdin(){
    $lines = array();
    while ($line = fgets(STDIN)) {
        $lines[] = str_replace( array("\r\n","\r","\n"), '', trim($line) );
    }
    return $lines;
}

function mapLine($line, $keyMap = array(), $delimiter = " "){
    $tmp = explode($delimiter, $line);

    $_tmp = array();
    foreach($keyMap as $i => $key){
        $_tmp[$key] = $tmp[$i];
    }
    return $_tmp;
}

function mapLines($_lines, $keyMap = array(), $delimiter = " "){
    $records = array();
    foreach($_lines as $_line){
        $records[] = mapLine($_line, $keyMap, $delimiter);
    }
    return $records;
}


$linesAll = fetchAllStdin();
$stock = mapLine($linesAll[0], array(500, 100, 50, 10));

$recordNum = $linesAll[1];
$_lines = array_slice($linesAll, 2, $recordNum);
$records = mapLines($_lines, array('price', 500, 100, 50, 10));


foreach ($records as $record){
    //お釣り金額を算出

    $types = array(500, 100, 50, 10);
    $paymentSum = 0;
    foreach($types as $type){
        $paymentSum += $type * $record[$type];
    }
    $rest = $paymentSum - $record['price'];//支払い不足は考慮しない

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
