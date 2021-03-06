<?php

include 'cartinfo.php';

//insert information to invoices and bill table
if (isset($_POST['invoice']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($products as $product) {
        $stmt = $pdo->prepare('SELECT `products`.`quantity` from `products` WHERE `products`.`productID` = ?');
        $stmt->bindParam(1, $product['productID'], PDO::PARAM_STR);
        $stmt->execute();
        $quant = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($quant["quantity"] < $products_in_cart[$product['productID']]) {
            header('Location: index?page=error');
            exit();
        }
    }

    foreach ($products as $product) {
        $stmt = $pdo->prepare('UPDATE `products` SET `quantity` = `quantity` - ? WHERE `products`.`productID` = ?');
        $stmt->bindParam(1, $products_in_cart[$product['productID']], PDO::PARAM_STR);
        $stmt->bindParam(2, $product['productID'], PDO::PARAM_STR);
        $stmt->execute();
    }

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = date('Y-m-d H:i:s', time());


    $stmt = $pdo->prepare('INSERT INTO `invoices` '
        . '(`name`, `phone`, `address`, `city`, `CreateDate`, `stt`,`userID`)' .
        ' VALUES (?, ?, ?, ?, ?, 0, 0);'); 
    if(isset($_SESSION['userID'])) {
        $stmt = $pdo->prepare('INSERT INTO `invoices` '
        . '(`name`, `phone`, `address`, `city`, `CreateDate`, `stt`, `userID`)' .
        ' VALUES (?, ?, ?, ?, ?, 0, ?);');
        $stmt->bindParam(6, $_SESSION['userID'], PDO::PARAM_STR);
    }

    $stmt->bindParam(1, $_POST['customer_name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['address'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['addresscity'], PDO::PARAM_STR);
    $stmt->bindParam(5, $date, PDO::PARAM_STR);
    $stmt->execute();

    $stmt = $pdo->prepare('SELECT invoiceID FROM invoices WHERE `name` = ? AND `phone` = ?  AND `address` = ? AND `city` = ? AND `CreateDate` = ? LIMIT 1');
    $stmt->bindParam(1, $_POST['customer_name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['address'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['addresscity'], PDO::PARAM_STR);
    $stmt->bindParam(5, $date, PDO::PARAM_STR);
    $stmt->execute();
    $lastinvoices = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($lastinvoices as $lastinvoice) {
        $BillID = $lastinvoice;
    }

    foreach ($products as $product) {
        $stmt = $pdo->prepare('INSERT INTO `billing` '
            . '(`invoiceID`, `ProductID`, `price` ,`quantity`)' .
            ' VALUES (?, ?, ?, ?);');
        $stmt->bindParam(1, $BillID, PDO::PARAM_STR);
        $stmt->bindParam(2, $product['productID'], PDO::PARAM_STR);
        $stmt->bindParam(3, $product['price'], PDO::PARAM_STR);
        $stmt->bindParam(4, $products_in_cart[$product['productID']], PDO::PARAM_STR);
        $stmt->execute();
        unset($_SESSION['cart'][$product['productID']]);
    }
    header('Location: index?page=placeorder');
    exit;
} elseif (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    header('Location: index?page=home');
}
?>


<?php include 'navbar.php'; ?>
<div class="mx-auto " style="width: 90%;">
<div class="cart col-lg-9 col-md-10 col-sm-11 col-12 mx-auto" >
    <h1 class="mx-auto">Invoice's Information</h1>
    <form action="index?page=invoice" method="post">
        <div class="col-10">
        <div class="form-row col-7">
            <div class=" mb-3">
                <label>Your name</label>
                <input type="text" class="form-control" placeholder="Your name" required name="customer_name" value="<?= isset($_SESSION['customerFname']) ? $_SESSION['customerFname'] : '' ?>">
            </div>
        </div>
        <div class="form-row col-7">
            <div class="mb-3">
                <label>Phone number </label>
                <input type="tel" min="0" class="form-control" pattern="(84|0[3|5|7|8|9])+([0-9]{8})\b" required name="phone" value="<?= isset($_SESSION['phone']) ? $_SESSION['phone'] : '' ?>">
            </div>
        </div>
        <div class="form-row col-7">
            <div class=" mb-3">
                <label>Address City</label>
                <select id="city" name="addresscity">
                    <option value="H?? N???i">Tp.H?? N???i
                    <option value="H??? Ch?? Minh">TP HCM
                    <option value="C???n Th??">Tp.C???n Th??
                    <option value="???? N???ng">Tp.???? N???ng
                    <option value="H???i Ph??ng">Tp.H???i Ph??ng
                    <option value="An Giang">An Giang
                    <option value="B?? R???a - V??ng T??u">B?? R???a - V??ng T??u
                    <option value="B???c Giang">B???c Giang
                    <option value="B???c K???n">B???c K???n
                    <option value="B???c Li??u">B???c Li??u
                    <option value="B???c Ninh">B???c Ninh
                    <option value="B???n Tre">B???n Tre
                    <option value="B??nh ?????nh">B??nh ?????nh
                    <option value="B??nh D????ng">B??nh D????ng
                    <option value="B??nh Ph?????c">B??nh Ph?????c
                    <option value="B??nh Thu???n">B??nh Thu???n
                    <option value="C?? Mau">C?? Mau
                    <option value="Cao B???ng">Cao B???ng
                    <option value="?????k L???k">?????k L???k
                    <option value="?????k N??ng">?????k N??ng
                    <option value="??i???n Bi??n">??i???n Bi??n
                    <option value="?????ng Nai">?????ng Nai
                    <option value="?????ng Th??p ">?????ng Th??p
                    <option value="Gia Lai">Gia Lai
                    <option value="H?? Giang">H?? Giang
                    <option value="H?? Nam">H?? Nam
                    <option value="H?? T??nh">H?? T??nh
                    <option value="H???i D????ng">H???i D????ng
                    <option value="H???u Giang">H???u Giang
                    <option value="H??a B??nh">H??a B??nh
                    <option value="H??ng Y??n">H??ng Y??n
                    <option value="Kh??nh H??a">Kh??nh H??a
                    <option value="Ki??n Giang">Ki??n Giang
                    <option value="Kon Tum">Kon Tum
                    <option value="Lai Ch??u">Lai Ch??u
                    <option value="L??m ?????ng">L??m ?????ng
                    <option value="L???ng S??n">L???ng S??n
                    <option value="L??o Cai">L??o Cai
                    <option value="Long An">Long An
                    <option value="Nam ?????nh">Nam ?????nh
                    <option value="Ngh??? An">Ngh??? An
                    <option value="Ninh B??nh">Ninh B??nh
                    <option value="Ninh Thu???n">Ninh Thu???n
                    <option value="Ph?? Th???">Ph?? Th???
                    <option value="Qu???ng B??nh">Qu???ng B??nh
                    <option value="Qu???ng B??nh">Qu???ng B??nh
                    <option value="Qu???ng Ng??i">Qu???ng Ng??i
                    <option value="Qu???ng Ninh">Qu???ng Ninh
                    <option value="Qu???ng Tr???">Qu???ng Tr???
                    <option value="S??c Tr??ng">S??c Tr??ng
                    <option value="S??n La">S??n La
                    <option value="T??y Ninh">T??y Ninh
                    <option value="Th??i B??nh">Th??i B??nh
                    <option value="Th??i Nguy??n">Th??i Nguy??n
                    <option value="Thanh H??a">Thanh H??a
                    <option value="Th???a Thi??n Hu???">Th???a Thi??n Hu???
                    <option value="Ti???n Giang">Ti???n Giang
                    <option value="Tr?? Vinh">Tr?? Vinh
                    <option value="Tuy??n Quang">Tuy??n Quang
                    <option value="V??nh Long">V??nh Long
                    <option value="V??nh Ph??c">V??nh Ph??c
                    <option value="Y??n B??i">Y??n B??i
                    <option value="Ph?? Y??n">Ph?? Y??n
                </select>
                <script>
                    var ident = "<?= isset($_SESSION['city']) ? $_SESSION['city'] : '' ?>";
                    $('#city option[value="' + ident + '"]').attr("selected", "selected");
                </script>
            </div>
        </div>
        <div class="form-row col-10">
            <div class="mb-3">
                <label>Address</label>
                <input type="text" class="form-control" value="<?= isset($_SESSION['address']) ? $_SESSION['address'] : '' ?>" placeholder="address" name="address" required>
            </div>
        </div>
        </div>
        <div class="col-12">
        <table class="col-12" >
            <thead>
                <tr>
                    <td colspan="2" class="col-6">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) :
                    if (isset($_POST["remove$product[productID]"])) {
                        unset($_SESSION['cart'][$product['productID']]);
                        header('location: index?page=cart');
                    }
                ?>
                    <tr>

                        <td class="img">
                            <a href="index?page=product&id=<?= $product['productID'] ?>">
                                <img src="imgs/<?= $product['img'] ?>" width="50" height="50" alt="<?= $product['name'] ?>">
                            </a>
                        </td>
                        <td >
                            <a class="wrap" style="white-space: normal !important; padding-left:10px " href="index?page=product&id=<?= $product['productID'] ?>"><?= $product['name'] ?></a>
                        </td>
                        <td class="price">&dollar;<?= $product['price'] ?></td>
                        <td class="quantity">
                            <p name="quantity-<?= $product['productID'] ?>" required><?= $products_in_cart[$product['productID']] ?></p>
                        </td>
                        <td class="price">&dollar;<?= $product['price'] * $products_in_cart[$product['productID']] ?></td>
                    </tr>
                <?php endforeach; ?>


            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <input type="text" value="<?= $subtotal ?>" name="totalcost" hidden>
            <span class="price">&dollar;<?= $subtotal ?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Send order" name="invoice">
        </div></div>
    </form>
</div></div>