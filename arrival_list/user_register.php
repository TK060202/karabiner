<?php
include_once('./function/db_connect.php');

session_start();

$log_flag = 0;

if(isset($_POST['signup'])){
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $log_flag = 1;

  //postされた情報をDBに格納
  $query = "INSERT INTO user_table (user_name, email, password) VALUES ('$username', '$email', '$password')";
}
?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHPの会員登録機能</title>
<link rel="stylesheet" href="style.css">

<!-- Bootstrap読み込み（スタイリングのため） -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="col-xs-6 col-xs-offset-3">

<?php
if($mysqli->query($query) && $log_flag != 0){
?>
  <div class="alert alert-success" role="alert">登録しました</div>
<?php
}
elseif(!$mysqli->query($query) && $log_flag != 0){
?>
  <div class="alert alert-danger" role="alert">エラーが発生しました。</div>
<?php
}
?>

<form method="post">
  <h1>登録フォーム</h1>
  <div class="form-group">
    <input type="text" class="form-control" name="username" placeholder="ユーザー名" required />
  </div>
  <div class="form-group">
    <input type="email"  class="form-control" name="email" placeholder="メールアドレス" required />
  </div>
  <div class="form-group">
    <input type="password" class="form-control" name="password" placeholder="パスワード" required />
  </div>
  <button type="submit" class="btn btn-default" name="signup">会員登録する</button>
  <a href="index.php">ログインはこちら</a>
</form>

</div>
</body>
</html>
