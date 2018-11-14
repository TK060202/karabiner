<?php
include_once('./function/db_connect.php');
require_once('./function/calendar_date.php');
require_once('./class/calendar.php');
require_once('./class/schedule.php');
require_once('./class/db_operate.php');

session_start();
$holiday = array();

if(isset($_POST['other_user'])){
  $userId = $_POST['other_user'];
  $_SESSION['other_user'] = $userId;
  unset($_SESSION['schedule']);
}
else{
  $userId = $_SESSION['other_user'];
}

$query = "SELECT * FROM user_table WHERE user_id=" . $userId . "";
$result = $mysqli->query($query);

while($row = $result->fetch_assoc()){
  $userName = $row['user_name'];
  $email = $row['email'];
}

$clsCalendar = new Calendar();
$clsSchedule = new Schedule();
$clsDBOperate = new DBOperate();

list($year, $month) = date_edit($_POST);

// 月末日を取得
$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

$holidays = $clsCalendar -> get_holidays_this_month($year, $month);

list($calendar, $holiday) = $clsCalendar -> create_calendar($month, $year, $holidays, $last_day);

$schedules = $clsSchedule -> get_schedule($year, $month, $userId, $calendar, $mysqli, $clsDBOperate);

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHPのマイページ機能</title>
<link rel="stylesheet" href="./css/style.css">
<!-- Bootstrap読み込み（スタイリングのため） -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>
</head>
<body>
<ul id="nav">
  <li><a href="home.php">HOME</a></li>
  <li><a href="schedule_register.php">REGISTER</a></li>
  <li><p>PROFILE</p></li>
  <li class="right"><a href="logout.php?logout">ログアウト</a></li>
</ul>
<hr  class="bar">

<div class="col-xs-6 col-xs-offset-3">

<h2><?php echo $userName ?>さんの出勤予定</h2>
  <h3>
    <center>
      <form class="" action="other_profile.php" method="post">
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

        if(!empty($schedule)){
          $day_schedule = implode(',', $schedule);
          if($count == 1 || $flag == 1){
            echo "<p><font color='red'>" . $value['day'] . "</font></p>";
          }
          elseif($count == 7){
            echo "<p><font color='blue'>" . $value['day'] . "</font></p>";
          }
          else{
            echo "<p>" . $value['day'] . "</p>";
          }

          echo '<p>' . $schedule['startTime'] . '</p>';
          echo '<p">〜</p>';
          echo '<p>' . $schedule['finishTime'] . '</p>';
        }
        else{
          if($count == 1 || $flag == 1){
            echo "<p><font color='red'>" . $value['day'] . "</font></p>";
          }
          elseif($count == 7){
            echo "<p><font color='blue'>" . $value['day'] . "</font></p>";
          }
          else{
            echo "<p>" . $value['day'] . "</p>";
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
</div>

</body>
</html>
