<?php
include_once('./function/db_connect.php');
require_once('./function/week_date.php');
require_once('./class/schedule.php');
require_once('./class/db_operate.php');

session_start();

$userName = array();
$userId = array();
$schedule = array();
$schedules = array();

if(!isset($_SESSION['user'])){
  header("Location: index.php");
}

unset($_SESSION['schedule']);

$week = array('日','月', '火', '水', '木', '金', '土', '日', '月', '火', '水', '木', '金', '土');
$nowWeek = date('w');

list($year, $month, $day, $last_day) = date_edit($_POST);

$query = "SELECT * FROM user_table";
$result = $mysqli->query($query);

while($row = $result->fetch_assoc()){
  if($row['user_id'] == $_SESSION['user']){
    array_unshift($userName, $row['user_name']);
    array_unshift($userId, $row['user_id']);
  }
  else{
    $userName[] = $row['user_name'];
    $userId[] = $row['user_id'];
  }
}

if(!$result){
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}
?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>出勤一覧</title>
<link rel="stylesheet" href="css/style.css">
<!-- Bootstrap読み込み（スタイリングのため） -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>
</head>
<body>
  <ul id="nav">
    <li><p>HOME</p></li>
    <li><a href="schedule_register.php">REGISTER</a></li>
    <li><a href="profile.php">PROFILE</a></li>
    <li class="right"><a href="logout.php?logout">ログアウト</a></li>
  </ul>
  <hr  class="bar">
  <br>

  <div class="col-xs-6 col-xs-offset-3">
    <h1>出勤一覧</h1>
    <br>

    <h3>
      <center>
        <form class="" action="home.php" method="post">
          <button type="submit" name="back"><</button>
          <?php


          if($day + 6 > $last_day){
            if($day == $last_day){
              if($month == 12){
                echo $month . "/" . $day . "〜1/6の出勤予定";
              }
              else{
                echo $month . "/" . $day . '〜' . ($month + 1) . "/6の出勤予定";
              }
            }
            else{
              if($month == 12){
                echo $month . "/" . $day . "〜1/" . (6 - ($last_day - $day)) . "の出勤予定";
              }
              else{
                echo $month . "/" . $day . '〜' . ($month + 1) . "/" . (6 - ($last_day - $day)) . "の出勤予定";
              }
            }
          }
          else{
            echo $month . "/" . $day . '〜' . $month . "/" . ($day + 6) . "の出勤予定";
          }
          ?>
          <button type="submit" name="next">></button>
        </form>
      </center>
    </h3>
    <br>

    <center>
      <table class="view">
        <tr>
          <th>ユーザー名</th>
          <?php
          $dayCount = 0;
          $weekYear = array();
          $weekMonth = array();
          $weekDay = array();

          for($i = 0; $i < 7; ++$i){
          ?>
            <th>
              <?php
                if($last_day < $day + $dayCount){
                  if($month + 1 > 12){
                    $year = $year + 1;
                    $month = 1;
                    echo $month . '/';
                  }
                  else{
                    $month = $month + 1;
                    echo $month . '/';
                  }
                  $day = 1;
                  $dayCount = 0;
                  $weekYear[] += $year;
                  $weekMonth[] += $month;
                  $weekDay[] += $day;
                  echo $day . '(';
                }
                else{
                  $weekYear[] += $year;
                  $weekMonth[] += $month;
                  $weekDay[] += $day + $dayCount;
                  echo $month . '/';
                  echo $day + $dayCount . '(';
                }

                if($week[$nowWeek + $i] == '土'){
                  echo "<font color='blue'>" . $week[$nowWeek + $i] . "</font>";
                }
                elseif($week[$nowWeek + $i] == '日'){
                  echo "<font color='red'>" . $week[$nowWeek + $i] . "</font>";
                }
                else{
                  echo $week[$nowWeek + $i];
                }
                echo ')';
              ?>
            </th>
          <?php
            $dayCount++;
          }
          ?>
        </tr>

        <?php
        $clsSchedule = new Schedule();
        $clsDBOperate = new DBOperate();

        $displayCount = 0;

        for($i = 0; $i < count($userId); ++$i){
          unset($_SESSION['schedule']);
          $year = $_SESSION['year'];
          $month = $_SESSION['month'];
          $day = $_SESSION['day'];
        ?>
          <tr>
            <?php
            if($i == 0){
            ?>
              <td><?php echo $userName[$i]; ?></td>
            <?php
            }
            else{
            ?>
              <td>
                <form class="" action="other_profile.php" method="post" name="form">
                  <input type="hidden" name="other_user" value="<?php echo $userId[$i]; ?>">
                  <a href="#" onClick="document.form[<?php echo $i - 1; ?>].submit();return false;"><?php echo $userName[$i]; ?></a>
                </form>
              </td>
            <?php
            }

            $schedules = $clsSchedule -> get_week_schedule($weekYear[0], $weekMonth[0], $userId[$i], $calendar, $mysqli, $clsDBOperate);

            if(!empty($schedules)){
              for($n = 0; $n < 7; ++$n){
                if($weekMonth[$n] != $month){
                  $month = $weekMonth[$n];
                  $year = $weekYear[$n];
                  $schedules = $clsSchedule -> get_week_schedule($year, $month, $userId[$i], $calendar, $mysqli, $clsDBOperate);
                }

                for($m = 0; $m < count($schedules); ++$m){
                  if($schedules[$m]['day'] == $weekDay[$n] && !empty($schedules[$m]['startTime'])){
            ?>
                    <td>
                      <?php echo $schedules[$m]['startTime']; ?>
                      <br>
                      〜
                      <br>
                      <?php echo $schedules[$m]['finishTime']; ?>
                    </td>
            <?php
                    $displayCount = 1;
                  }
                }
                if($displayCount != 1){
            ?>
                  <td></td>
            <?php
                }
                $displayCount = 0;
              }
            }
            else{
              for($n = 0; $n < 7; ++$n){
            ?>
              <td></td>
            <?php
              }
            }
            ?>
          </tr>
        <?php
          $schedules = [];
        }
        ?>
      </table>
    </center>

  </div>
</body>
</html>
