<?php
function date_edit($getData){
  if(isset($getData['back'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
    unset($_SESSION['schedule']);

    if($month == 1){
      $year = $year - 1;
      $month = 12;
      $_SESSION['year'] = $year;
      $_SESSION['month'] = $month;
    }
    else{
      $month = $month - 1;
      $_SESSION['month'] = $month;
    }
  }
  elseif(isset($getData['next'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
    unset($_SESSION['schedule']);

    if($month == 12){
      $year = $year + 1;
      $month = 1;
      $_SESSION['year'] = $year;
      $_SESSION['month'] = $month;
    }
    else{
      $month = $month + 1;
      $_SESSION['month'] = $month;
    }
  }
  elseif(!empty($getData)){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
  }
  else{
    $year = date('Y');
    $_SESSION['year'] = $year;
    $month = date('n');
    $_SESSION['month'] = $month;
    unset($_SESSION['schedule']);
  }

  return array($year, $month);
}
?>
