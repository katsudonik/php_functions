<?php
/**
 * Created by IntelliJ IDEA.
 * User: kaikeda
 * Date: 2017/07/22
 * Time: 0:54
 */

function _array_chunk($array, $size, $index_key = null){
    if($size < 1){
        return false;
    }

    $recordsNum = count($array);

    $tmpRecords = array();
    $_tmpRecords = array();
    $cntRecords = 0;
    foreach($array as $i => $record){
        $cntRecords++;

        $index = $i;
        if($index_key !== null){
            $index = $index_key;
        }
        $tmpRecords[$index] = $record;

        if(($cntRecords % $size) != 0
            && $cntRecords < $recordsNum){
                continue;
        }
        $_tmpRecords[] = $tmpRecords;
    }
    return $_tmpRecords;
}

function indexing($array, $index_key){
    $_array = array();
    foreach($array as $i => $record){
        if(isset($record[$index_key])){
            $_array[$record[$index_key]] = $record;
        }
    }
    return $_array;
}

function _array_column($input, $column_key){
    $vals = array();
    foreach($input as $i => $record){
        if(isset($record[$column_key])){
            $vals[] = $record[$column_key];
        }
    }
    return $vals;
}

function mergeAssociate($records, $pkColumn = 'oid', $chunkNum = 500){
    $chunkedRecords = _array_chunk($records, $chunkNum, $pkColumn);
    $rRecords = array();
    foreach($chunkedRecords as $_records){
        $foundRecords = indexing(fetchByIn($pkColumn,_array_column($_records,$pkColumn)), $pkColumn);
        foreach($_records as $_i => $tmpRecord){
            $rRecords[$tmpRecord[$pkColumn]] = array_merge($tmpRecord, $foundRecords[$tmpRecord[$pkColumn]]);
        }
    }

    return $rRecords;
}


function splitInsert($records, $pkColumn = 'oid', $chunkNum = 500){
    $recordsNum = count($records);

    $rows = array();
    $tmpRecords = array();
    $searchIds = array();
    $cntRecords = 0;
    foreach($records as $i => $record){
        $cntRecords++;

        $tmpRecords[$i] = $record;
        $searchIds[] = $record[$pkColumn];
        unset($records[$i]);

        if(count($searchIds) < $chunkNum
            && $cntRecords < $recordsNum){
            continue;
        }

        $foundRecords = fetchByIn($pkColumn, $searchIds);
        $searchIds = array();

        foreach($tmpRecords as $_i => $tmpRecord){
            $foundRecord = $foundRecords[$tmpRecord[$pkColumn]];
            insertRow($rows, $_i, $tmpRecord, $foundRecord);
            unset($tmpRecords[$_i]);
        }
    }
    return $rows;
}

function fetchByIn($pkColumn, $searchIds){
    return $this->getEntityManager()->getRepository('VCECBundle:Site')->fetchSiteInfo($searchIds);
}

function insertRow(&$rows, $_i, $tmpRecord, $foundRecord){
    $rows[] = array(
        'id' => $foundRecord['id']
    );
}