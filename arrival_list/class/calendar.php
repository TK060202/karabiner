<?php
class Calendar{
  public function create_calendar($month, $year, $holidays, $last_day){
    $holiday = array();

    foreach ($holidays as $key => $value) {
      $cut = strlen($key) - (strlen($key) - 2);
      $day = substr($key, strlen($key) - $cut, strlen($key) - $cut);
      $holiday[] = ltrim($day, '0');
    }

    $calendar = array();
    $j = 0;

    // 月末日までループ
    for ($i = 1; $i < $last_day + 1; ++$i) {
      // 曜日を取得
      $week = date('w', mktime(0, 0, 0, $month, $i, $year));
      // 1日の場合
      if ($i == 1) {
        // 1日目の曜日までをループ
        for ($s = 1; $s <= $week; ++$s) {
          // 前半に空文字をセット
          $calendar[$j]['day'] = '';
          $j++;
        }
      }
      // 配列に日付をセット
      $calendar[$j]['day'] = $i;

      $j++;

      // 月末日の場合
      if ($i == $last_day) {
        // 月末日から残りをループ
        for ($e = 1; $e <= 6 - $week; ++$e) {
          // 後半に空文字をセット
          $calendar[$j]['day'] = '';
          $j++;
        }
      }
    }
    return array($calendar, $holiday);
  }

  public function get_holidays_this_month($year, $month){
    // 月初日
    $first_day = mktime(0, 0, 0, intval($month), 1, intval($year));
    // 月末日
    $last_day = strtotime('-1 day', mktime(0, 0, 0, intval($month) + 1, 1, intval($year)));
    $api_key = 'AIzaSyCA9SMzuT37X_9WUJs3m4cd_ZL4tyTULL0';
    // $holidays_id = 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com';  // mozilla.org版
    $holidays_id = 'japanese__ja@holiday.calendar.google.com';  // Google 公式版日本語
    //$holidays_id = 'japanese@holiday.calendar.google.com';  // Google 公式版英語
    $holidays_url = sprintf(
      'https://www.googleapis.com/calendar/v3/calendars/%s/events?'.
      'key=%s&timeMin=%s&timeMax=%s&maxResults=%d&orderBy=startTime&singleEvents=true',
      $holidays_id,
      $api_key,
      date('Y-m-d', $first_day).'T00:00:00Z' ,  // 取得開始日
      date('Y-m-d', $last_day).'T00:00:00Z' ,   // 取得終了日
      31            // 最大取得数
      );
    if ( $results = file_get_contents($holidays_url) ) {
      $results = json_decode($results);
      $holidays = array();
      foreach ($results->items as $item ) {
        $date  = strtotime((string) $item->start->date);
        $title = (string) $item->summary;
        $holidays[date('Y-m-d', $date)] = $title;
      }
      ksort($holidays);
    }
    return $holidays;
  }
}
?>
