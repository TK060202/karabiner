<?php
function validation($getData){
	$error = array();

  if(empty($getData['startTime']) || empty($getData['finishTime'])){
    $error[] = "時刻を入力してください";
  }
  elseif(strtotime($getData['startTime']) >= strtotime($getData['finishTime'])){
    $error[] = "時刻が不正です";
  }

	return $error;
}
?>
