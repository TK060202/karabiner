<?php
session_start();

if(isset($_GET['logout'])){
  session_destroy();
  unset($_SESSION['user'], $_SESSION['year'], $_SESSION['month'], $_SESSION['day'], $_SESSION['other_user'], $_SESSION['schedule']);
  header("Location: index.php");
}
else{
  header("Location: index.php");
}
?>
