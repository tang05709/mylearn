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
       
public function encrypt($info, $key)
{
    $string = json_encode($info);
    // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变       
    $data = openssl_encrypt($string, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $data = strtolower($this->hexXbin($data, true));
    return $data;
}

public function hexXbin($data, $types = false)
{
    if (!is_string($data))
        return 0;
    if ($types === false) {
        $len = strlen($data);
        if ($len % 2) {
            return 0;
        } else if (strspn($data, '0123456789abcdefABCDEF') != $len) {
            return 0;
        }
        return pack('H*', $data);
    } else {
        return bin2hex($data);
    }
}

public function decrypt($string, $key)
{
    $temp = openssl_decrypt($this->hexXbin($string), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $result = [];
    if ($temp) {
        $result = json_decode($temp, true);
    }
    return $result;
}
            
            
  public static function generateSn()
  {
       $SN = date("YmdHis") . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
       return $SN;
  }

public  function  getMenu($data,$pid,$deep=0)
   {
       //static $tree=array();
       $tree='';
       foreach ($data as $row) {
           if($row['parentid']==$pid){
              $row['deep']=$deep;
              $row['children']=$this->getMenu($data,$row['id'],$deep+1);
              $tree[]=$row;
              //$this->getMenu($data,$row['id'],$deep+1);
           }
       }
       return $tree;

   }
