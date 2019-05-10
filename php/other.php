$s = '1234567890';
$o = '';
$i = 0;
while(isset($s[$i]) && $s[$i] != null) {
    $o = $s[$i++].$o;
}
echo $o;


list($b,$a)=array($a,$b);
var_dump($a,$b);
