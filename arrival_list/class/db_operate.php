<?php
class DBOperate{

  public function select_year($mysqli, $userId, $year){
    $yearQuery = "SELECT * FROM schedule_year WHERE user_id = " . $userId . " AND year = $year";
    $yearResult = $mysqli->query($yearQuery);

    return $yearResult;
  }

  public function select_month($mysqli, $yearId, $month){
    $monthQuery = "SELECT schedule FROM schedule_month  WHERE year_id = $yearId AND month = $month";
    $monthResult = $mysqli->query($monthQuery);

    return $monthResult;
  }

  public function select_schedule($mysqli, $userId, $year, $month){
    $yearResult = $this->select_year($mysqli, $userId, $year);
    $yearNumRow = $yearResult->num_rows;

    if($yearNumRow != 0){
      $yearRow = $yearResult->fetch_assoc();
      $yearId = $yearRow['id'];

      $monthQuery = "SELECT schedule FROM schedule_month  WHERE year_id = $yearId AND month = $month";
      $monthResult = $mysqli->query($monthQuery);
      $json = $monthResult->fetch_assoc();
    }
    else{
      $json = NULL;
    }

    return $json;
  }

  public function insert_year($mysqli, $userId, $year){
    $insertYearQuery = "INSERT INTO schedule_year (year, user_id) VALUES ('$year', '$userId')";
    $insertYearResult = $mysqli->query($insertYearQuery);

    return $insertYearResult;
  }

  public function insert_schedule($mysqli, $yearId, $month, $schedules){
    $json = json_encode($schedules, JSON_PRETTY_PRINT);
    $insertMonthQuery = "INSERT INTO schedule_month (month, schedule, year_id) VALUES ('$month', '$json', '$yearId')";
    $insertMonthResult = $mysqli->query($insertMonthQuery);

    return $insertMonthResult;
  }

  public function update_schedule($mysqli, $yearId, $month, $schedules){
    $json = json_encode($schedules, JSON_PRETTY_PRINT);
    $updateMonthQuery = "UPDATE schedule_month SET schedule = '$json' WHERE year_id = $yearId AND month = $month";
    $updateMonthResult = $mysqli->query($updateMonthQuery);

    return $updateMonthResult;
  }

  public function register_schedule($mysqli, $userId, $year, $month, $schedules){
    $yearResult = $this -> select_year($mysqli, $userId, $year);
    $yearNumRow = $yearResult->num_rows;

    if($yearNumRow == 0){
      $insertYearResult = $this -> insert_year($mysqli, $userId, $year);

      if($insertYearResult){
        $yearResult = $this -> select_year($mysqli, $userId, $year);
      }
    }

    $yearRow = $yearResult->fetch_assoc();
    $yearId = $yearRow['id'];

    $monthResult = $this -> select_month($mysqli, $yearId, $month);
    $monthRow = $monthResult -> fetch_assoc();

    if(empty($monthRow)){
      $registerResult = $this -> insert_schedule($mysqli, $yearId, $month, $schedules);
    }
    else{
      $registerResult = $this -> update_schedule($mysqli, $yearId, $month, $schedules);
    }

    return $registerResult;
  }
}
?>
