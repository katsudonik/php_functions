    public function termChk($from, $to, $diff = '+1 week', $timeStamp = false){
        if(!$timeStamp){
            $from = strtotime($from);
            $to = strtotime($to);
        }
        return ( strtotime($diff , $from) - $from ) >= ( $to - $from );
    }
