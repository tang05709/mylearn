$s = '1234567890';
$o = '';
$i = 0;
while(isset($s[$i]) && $s[$i] != null) {
    $o = $s[$i++].$o;
}
echo $o;


list($b,$a)=array($a,$b);
var_dump($a,$b);

/**
 * 将一个时间段分成n份
 * start_at、end_at 开始和结束时间，需为时间戳
 * count 份数
*/
private function makeTimeCut($start_at, $end_at, $count) {
    $count = intval($count);
    // 计算分段数量
    $parts = ($end_at - $start_at) / $count;
    // 是否除尽
    $remainder = ($end_at - $start_at) % $count;
    if($remainder > 0) {
        // 重新计算分段数量
        $parts = ($end_at - $start_at - $remainder) / $count;
    }
    $times = [];
    for($i = 0; $i < $count; $i++) {
        $start = $start_at + $parts * $i;
        $end = $start + $parts;
        $data = [
            'start_at' => $start,
            'end_at' => $end,
        ];
        $times[] = $data; 
    }

    return $times;
}
            
            
  public static function generateSn()
  {
       $SN = date("YmdHis") . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
       return $SN;
  }
