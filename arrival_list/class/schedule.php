<?php
class Schedule{

  public function get_schedule($year, $month, $userId, $calendar, $mysqli, $clsDBOperate){
    session_start();

    $schedules = array();

    $json = $clsDBOperate -> select_schedule($mysqli, $userId, $year, $month);

    if(!empty($json['schedule']) && empty($_SESSION['schedule'])){
      $schedules = json_decode($json['schedule'], true);

      $_SESSION['schedule'] = $schedules;
    }
    else{
      if(empty($_SESSION['schedule'])){
        for($i = 0; $i < count($calendar); $i++){
          $schedules[$i]['year'] = $year;
          $schedules[$i]['month'] = $month;
          $schedules[$i]['day'] = $calendar[$i]['day'];
        }
        $_SESSION['schedule'] = $schedules;
      }
    }

    return $schedules;
  }

  public function get_week_schedule($year, $month, $userId, $calendar, $mysqli, $clsDBOperate){
    session_start();

    $schedules = array();

    $json = $clsDBOperate -> select_schedule($mysqli, $userId, $year, $month);

    if(!empty($json['schedule'])){
      $schedules = json_decode($json['schedule'], true);

      $_SESSION['schedule'] = $schedules;
    }
    else{
      if(empty($_SESSION['schedule'])){
        for($i = 0; $i < count($calendar); $i++){
          $schedules[$i]['year'] = $year;
          $schedules[$i]['month'] = $month;
          $schedules[$i]['day'] = $calendar[$i]['day'];
        }
        $_SESSION['schedule'] = $schedules;
      }
    }

    return $schedules;
  }

  public function edit_schedule($getData){
    session_start();

    $schedules = array();

    $schedules = $_SESSION['schedule'];

    for($i = 0; $i < count($schedules); ++$i){
      if($schedules[$i]['day'] == $getData['date']){
        $schedules[$i]['startTime'] = $getData['startTime'];
        $schedules[$i]['finishTime'] = $getData['finishTime'];
      }
    }
    $_SESSION['schedule'] = $schedules;

    return $schedules;
  }

  public function delete_schedule($getData){
    session_start();

    $schedules = array();

    $schedules = $_SESSION['schedule'];

    for($i = 0; $i < count($schedules); ++$i){
      if($schedules[$i]['day'] == $getData['date']){
        $schedules[$i]['startTime'] = "";
        $schedules[$i]['finishTime'] = "";
      }
    }
    $_SESSION['schedule'] = $schedules;

    return $schedules;
  }

  public function all_delete_schedule($getData){
    session_start();

    $schedules = array();
    $day = array();

    $schedules = $_SESSION['schedule'];

    foreach($_POST as $key => $value) {
      if(strpos($key,'day') !== false){
        $day[] = $value;
      }
    }

    for($i = 0; $i < count($schedules); ++$i){
      for($j = 0; $j < count($day); ++$j){
        if($schedules[$i]['day'] == $day[$j]){
          $schedules[$i]['startTime'] = "";
          $schedules[$i]['finishTime'] = "";
        }
      }
    }
    $_SESSION['schedule'] = $schedules;

    return $schedules;
  }

  public function copy_schedule($getData){
    session_start();

    $attendanceTime = array();
    $day = array();
    $schedules = array();

    $schedules = $_SESSION['schedule'];

    foreach($getData as $key => $value) {
      if(strpos($key,'day') !== false){
        $day[] = $value;
      }
      elseif(strpos($key,'Time') !== false){
        $attendanceTime[] = $value;
      }
    }

    for($i = 0; $i < count($schedules); ++$i){
      for($j = 0; $j < count($day); ++$j){
        if($schedules[$i]['day'] == $day[$j]){
          $schedules[$i]['startTime'] = $attendanceTime[0];
          $schedules[$i]['finishTime'] = $attendanceTime[1];
        }
      }
    }
    $_SESSION['schedule'] = $schedules;

    return $schedules;
  }

}
?>
