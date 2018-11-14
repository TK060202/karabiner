$(function(){
  $('.calendar a').on('click', function(){
  　var val = $(this).attr("data-target");　// valueの取得
    if(val == 'modal-content-register'){
      var day = $(this).attr("id");

      $("#modal-content-register").html('<form class="" action="schedule_register.php" method="post"><input type="hidden" name="date" value="' + day + '"><label for="startTime">出社時間：</label><input type="time" name="startTime" value=""><br><br><label for="finishTime">退社時間：</label><input type="time" name="finishTime" value=""><br><br><button type="submit" name="create">作成</button></form>');
    }
    else{
      var day_schedule = $(this).attr("id");
      array = day_schedule.split(',');

      $("#modal-content-edit").html('<form class="" action="schedule_register.php" method="post"><input type="hidden" name="date" value="' + array[2] + '"><label for="startTime">出社時間：</label><input type="time" name="startTime" value="' + array[3] + '"><br><br><label for="finishTime">退社時間：</label><input type="time" name="finishTime" value="' + array[4] + '"><br><br><button type="submit" name="edit">編集</button><button type="submit" name="copy" formaction="schedule_copy.php">複製</button><button type="submit" name="delete">削除</button></form>');
    }
  });

});
