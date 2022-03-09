<?php
session_start();
include('config/connect.php');
if (isset($_POST['login'])) {
    $account = $_POST['username'];
    $stmt = $pdo->prepare("SELECT COUNT(*) AS numberOfUsers FROM tbl_admin WHERE username = ? AND password = ? LIMIT 1;");
    $password = md5($_POST['password']);
    $stmt->bindParam(1, $account, PDO::PARAM_STR);
    $stmt->bindParam(2, $password, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($resultSet as $row) {
        $count = intval($row['numberOfUsers']);
        if ($count > 0) {
            $_SESSION['loginadmin'] = $account;
            $_SESSION['passwordadmin'] = $_POST['password'];
            header("Location:index");
        } else {
            echo '<script>alert("Account or password is incorrect,please re-enter.")</script>';
            header("Location:login");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="./cssadmin/style1.css">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
</head>

<body class="body1">
    <div class="center">
        <h1>Login</h1>
        <form action="" autocomplete="off" method="POST">
            <div class="txt_field">
                <input type="text" name="username">
                <span></span>
                <label>User Name</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password">
                <span></span>
                <label>Password</label>
            </div>
            <input class="login" type="submit" name="login" value="login">
        </form>
    </div>
</body>


</html>