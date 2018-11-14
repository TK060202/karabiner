<?php
include_once('./function/db_connect.php');
require_once('./function/calendar_date.php');
require_once('./class/calendar.php');
require_once('./class/schedule.php');
require_once('./class/db_operate.php');
require_once('./function/validation.php');

session_start();

$holiday = array();
$calendar = array();
$schedule = array();
$schedules = array();
$error = array();

$clsCalendar = new Calendar();
$clsSchedule = new Schedule();
$clsDBOperate = new DBOperate();

list($year, $month) = date_edit($_POST);

// 月末日を取得
$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

$holidays = $clsCalendar -> get_holidays_this_month($year, $month);

list($calendar, $holiday) = $clsCalendar -> create_calendar($month, $year, $holidays, $last_day);

if(isset($_POST['btnRegister'])){
  $schedules = $_SESSION['schedule'];
  $registerResult = $clsDBOperate -> register_schedule($mysqli, $_SESSION["user"], $year, $month, $schedules);

  if($registerResult){
    echo '<script type="text/javascript">alert("登録しました");</script>';
    unset($_SESSION['schedule']);
  }
  else{
    echo '<script type="text/javascript">alert("登録に失敗しました");</script>';
  }
}

$schedules = $clsSchedule -> get_schedule($year, $month, $_SESSION['user'], $calendar, $mysqli, $clsDBOperate);

if(isset($_POST['create']) || isset($_POST['edit'])){
  $error = validation($_POST);

  if(empty($error)){
    $schedules = $clsSchedule -> edit_schedule($_POST);
  }
}
elseif(isset($_POST['delete'])){
  $schedules = $clsSchedule -> delete_schedule($_POST);
}
elseif(isset($_POST['copy'])){
  $error = validation($_POST);

  if(empty($error)){
    $schedules = $clsSchedule -> copy_schedule($_POST);
  }
}
elseif(isset($_POST['allDelete'])){
  $schedules = $clsSchedule -> all_delete_schedule($_POST);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>出勤登録</title>
<!-- Bootstrap読み込み（スタイリングのため） -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/modal-multi.css">
<!-- JavaScriptの読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/modal-multi.js"></script>
<script src="js/edit.js"></script>

</head>
<body>
  <ul id="nav">
    <li><a href="home.php">HOME</a></li>
    <li><p>REGISTER</p></li>
    <li><a href="profile.php">PROFILE</a></li>
    <li class="right"><a href="logout.php?logout">ログアウト</a></li>
  </ul>

  <hr  class="bar">
  <br>

  <div class="col-xs-6 col-xs-offset-3">
    <h3>
      <center>
        <form class="" action="schedule_register.php" method="post">
          <button type="submit" name="back"><</button>
          <?php echo $year . "年" . $month; ?>月
          <button type="submit" name="next">></button>
        </form>
      </center>
    </h3>
    <br>

    <table class="calendar">
      <tr>
        <th><font color='red'>日</font></th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th><font color='blue'>土</font></th>
      </tr>

      <tr>
      <?php
      $count = 0;
      $displayCount = 0;
      $schedule = [];

      foreach ($calendar as $key => $value){
      ?>
        <td>
        <?php
        $count++;
        // $dateCount++;

        for($i = 0; $i < count($holiday); ++$i){
          if($value['day'] == $holiday[$i]){
            $flag = 1;
          }
          elseif($flag == 1){

          }
          else{
            $flag = 0;
          }
        }

        for($i = 0; $i < count($schedules); ++$i){
          if(($schedules[$i]['day'] == $value['day']) && !empty($schedules[$i]['startTime'])){
            $schedule = $schedules[$i];
          }
        }

        // echo var_dump($value['startTime']);

        if(!empty($schedule)){
          $day_schedule = implode(',', $schedule);
          if($count == 1 || $flag == 1){
            echo "<a class='modal-syncer button-link' data-target='modal-content-edit' id='" . $day_schedule . "'><font color='red'>" . $value['day'] . "</font></a>";
          }
          elseif($count == 7){
            echo "<a class='modal-syncer button-link' data-target='modal-content-edit' id='" . $day_schedule . "'><font color='blue'>" . $value['day'] . "</font></a>";
          }
          else{
            echo "<a class='modal-syncer button-link' data-target='modal-content-edit' id='" . $day_schedule . "'>" . $value['day'] . "</a>";
          }

          echo '<p>' . $schedule['startTime'] . '</p>';
          echo '<p">〜</p>';
          echo '<p>' . $schedule['finishTime'] . '</p>';
        }
        else{
          if($count == 1 || $flag == 1){
            echo "<a class='modal-syncer button-link' data-target='modal-content-register' id='" . $value['day'] . "'><font color='red'>" . $value['day'] . "</font></a>";
          }
          elseif($count == 7){
            echo "<a class='modal-syncer button-link' data-target='modal-content-register' id='" . $value['day'] . "'><font color='blue'>" . $value['day'] . "</font></a>";
          }
          else{
            echo "<a class='modal-syncer button-link' data-target='modal-content-register' id='" . $value['day'] . "'>" . $value['day'] . "</a>";
          }
        }

        ?>
        </td>
        <?php
        if ($count == 7){
        ?>
          </tr>
          <tr>
      <?php
          $count = 0;
        }
        $flag = 0;
        $schedule = [];
      }
      ?>
      </tr>
    </table>


      <?php
      /* $errorが空ではない場合の警告文表示のための条件分岐 */
      if(!empty($error)){
        foreach( $error as $value ){
          $alertMessage .= "$value\n";
        }
      ?>
        <script>
          var alertMessage = <?php echo json_encode($alertMessage); ?>;
          alert(alertMessage);
        </script>
      <?php
      }
      ?>

    <table class="none">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <form class="" action="schedule_register.php" method="post">
        <td><button class="submit" type="submit" name="allDelete" onClick="form.action='schedule_all_delete.php';return true">一括削除</button></td>
        <td><button class="submit" type="submit" name="btnRegister">登録</button></td>
      </form>
    </table>
  </div>

  <div id="modal-content-register" class="modal-content"></div>

  <div id="modal-content-edit" class="modal-content"></div>

</body>
</html>
