<?php
/**格式化时间20140602 格式化为2014-06-19*/
function format_mydate($number,$formatsymbol = "-"){
	if(is_numeric($number)){
		$year = substr($number, 0,4);
		$month = substr($number, 4,2);
		$day  =  substr($number, 6,2);
		if(strlen($number) > 8){
			$hor = substr($number, 8,2);
			$min = substr($number, 10,2);
			$sec = substr($number, 12,2);
			$number = $year.$formatsymbol.$month.$formatsymbol.$day.' '.$hor.':'.$min.":".$sec;
		}else{
			$number = $year.$formatsymbol.$month.$formatsymbol.$day;
		}
	}
	return $number;
}
function randstr($len=6) {
	$chars='ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789'; // characters to build the password from
	mt_srand((double)microtime() * 1000000 * getmypid()); // seed the random number generater (must be done)
	$password='';
	while(strlen($password)<$len)
		$password.=substr($chars,(mt_rand()%strlen($chars)),1);
	return $password;
}
function myimgname($filename){
	return md5($filename);
}
?>

