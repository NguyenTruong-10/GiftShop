<?php

$phone = isset($_GET['id']) ? $_GET['id'] : '';
if (isset($_POST['login'])) {
    $account = $_POST['username'];
    $stmt = $pdo->prepare("SELECT * FROM customer_user WHERE (username = ? OR phone = ?) AND `password` = ? LIMIT 1;");
    $password = md5($_POST['password']);
    $stmt->bindParam(1, $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(3, $password, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($resultSet)) {
        echo '<script>alert("Account or password is incorrect,please re-enter.")</script>';
        header("index?page=login");
    } else {
        foreach ($resultSet as $row) {
            $_SESSION['name'] = $row['customerFname'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['city'] = $row['city'];
            $_SESSION['address'] = $row['address'];
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['customerFname'] = $row['customerFname'];
            $_SESSION['customerLname'] = $row['customerLname'];
            $_SESSION['email'] = $row['email'];
            if ($row['customerFname'] != '') {
                $_SESSION['login1'] = $row['customerFname'];
            } else {
                $_SESSION['login1'] = $row['username'];
            }
        }
        header("Location:index");
    }
}
?>

<?php include 'navbar.php'; ?>

<div class="wrapper-login justify-content-center d-flex">
    <div class=" m-auto">
        <div class="center2 mx-auto">
            <h1>Login</h1>
            <form action="" autocomplete="off" method="POST">
                <div class="txt_field_1">
                    <input type="text" name="username" placeholder="Username or Phone Number">
                    <span></span>
                </div>

                <div class="txt_field_1">
                    <input type="password" name="password" placeholder="Password">
                    <span></span>
                </div>
                <input class="login" type="submit" name="login" value="login">
            </form>
        </div>
    </div>
</div>