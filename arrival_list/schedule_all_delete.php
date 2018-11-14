<?php
require_once('./class/calendar.php');
require_once('./class/schedule.php');

session_start();

$holiday = array();
// 現在の年月を取得
$year = $_SESSION['year'];
$month = $_SESSION['month'];
$schedule = array();
$schedules = array();

// 月末日を取得
$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

$clsCalendar = new Calendar();

$holidays = $clsCalendar -> get_holidays_this_month($year, $month);

list($calendar, $holiday) = $clsCalendar -> create_calendar($month, $year, $holidays, $last_day);

$schedules = $_SESSION['schedule'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>出勤登録</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
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
        <?php echo $year . "年" . $month; ?>月
      </center>
    </h3>
    <br>

    <form class="" action="schedule_register.php" method="post">
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
            if($schedules[$i]['day'] == $value['day'] && !empty($schedules[$i]['startTime'])){
              $schedule = $schedules[$i];
            }
          }

          if(!empty($schedule)){
            $day_schedule = implode(',', $schedule);
            if($count == 1 || $flag == 1){
              echo '<p><input type="checkbox" name="day_' . $value['day'] . '" value="' . $value['day'] . '">　<font color="red">' . $value['day'] . '</font></p>';
            }
            elseif($count == 7){
              echo '<p><input type="checkbox" name="day_' . $value['day'] . '" value="' . $value['day'] . '">　<font color="blue">' . $value['day'] . '</font></p>';
            }
            else{
              echo '<p><input type="checkbox" name="day_' . $value['day'] . '" value="' . $value['day'] . '">　' . $value['day'] . '</p>';
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

      <table class="none">
        <td><button class="submit" type="submit" name="btnBack">戻る</button></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><button class="submit" type="submit" name="allDelete">一括削除</button></td>
      </table>
    </form>
  </div>
</body>
</html>
