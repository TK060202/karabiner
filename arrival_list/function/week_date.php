<?php
function date_edit($getData){
  if(isset($getData['back'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
    $day = $_SESSION['day'];

    if($day - 7 < 1){
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

      $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
      $day = ($last_day + $day) - 7;
      $_SESSION['day'] = $day;
    }
    else{
      $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
      $day = $day - 7;
      $_SESSION['day'] = $day;
    }
  }
  elseif(isset($getData['next'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
    $day = $_SESSION['day'];
    $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

    if($day + 7 > $last_day){
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

      $day = 7 - ($last_day - $day);
      $_SESSION['day'] = $day;
    }
    else{
      $day = $day + 7;
      $_SESSION['day'] = $day;
    }
  }
  else{
    $year = date('Y');
    $month = date('n');
    $day = date('j');
    $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

    $_SESSION['year'] = $year;
    $_SESSION['month'] = $month;
    $_SESSION['day'] = $day;
  }

  return array($year, $month, $day, $last_day);
}

?>
