<?php
include_once('./function/db_connect.php');

ob_start();
session_start();

$log_flag = 0;

if(isset($_SESSION['user']) != ""){
  header("Location: home.php");
}

if(isset($_POST['login'])){
  $name = $_POST['name'];
  $password = $_POST['password'];
  $log_flag = 1;

  $query = "SELECT * FROM user_table WHERE user_name='$name'";
  $result = $mysqli->query($query);
  if(!$result){
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }

  while($row = $result->fetch_assoc()){
    $db_hashed_password = $row['password'];
    $user_id = $row['user_id'];
  }

  $result->close();

}

?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PHPのログイン機能</title>
<link rel="stylesheet" href="style.css">
<!-- Bootstrap読み込み（スタイリングのため） -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>
</head>
<body>
<div class="col-xs-6 col-xs-offset-3">

<?php
if(password_verify($password, $db_hashed_password)){
  $_SESSION['user'] = $user_id;
  header("Location: home.php");
  exit;
}
elseif(!password_verify($password, $db_hashed_password) && $log_flag != 0){
?>
  <div class="alert alert-danger" role="alert">メールアドレスとパスワードが一致しません。</div>
<?php
}
?>

<form method="post">
  <h1>ログインフォーム</h1>
  <div class="form-group">
    <input type="text"  class="form-control" name="name" placeholder="名前" required />
  </div>
  <div class="form-group">
    <input type="password" class="form-control" name="password" placeholder="パスワード" required />
  </div>
  <button type="submit" class="btn btn-default" name="login">ログインする</button>
  <a href="user_register.php">会員登録はこちら</a>
</form>

</div>
</body>
</html>
