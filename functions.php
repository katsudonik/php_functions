<?php
/**
 * Created by IntelliJ IDEA.
 * User: kaikeda
 * Date: 2017/07/22
 * Time: 0:54
 */

function array_chunk($array, $size){
    if($size < 1){
        return false;
    }

    $recordsNum = count($array);

    $tmpRecords = array();
    $_tmpRecords = array();
    $cntRecords = 0;
    foreach($array as $i => $record){
        $cntRecords++;

        $tmpRecords[$i] = $record;

        if(($cntRecords % $size) != 0
            && $cntRecords < $recordsNum){
                continue;
        }
        $_tmpRecords[] = $tmpRecords;
    }
    return $_tmpRecords;
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
    $records = $this->getEntityManager()->getRepository('VCECBundle:Site')->fetchSiteInfo($searchIds);

    $_records = array();
    foreach($records as $i => $record){
        $_records[$record[$pkColumn]] = $record;
    }
    return $_records;
}

function insertRow(&$rows, $_i, $tmpRecord, $foundRecord){
    $rows[] = array(
        'id' => $foundRecord['id']
    );
}