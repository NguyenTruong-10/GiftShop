<?php

if (isset($_POST['invoiceid'])) {
    $stmt = $pdo->prepare('UPDATE invoices SET `name` = ? ,`phone` = ? ,`address` = ?   WHERE invoiceID = ?');
    $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(2, $_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(3, $_POST['address'], PDO::PARAM_STR);
    $stmt->bindParam(4, $_POST['invoiceid'], PDO::PARAM_STR);
    $stmt->execute();
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_GET['id'] = $_POST['invoiceid'];
}

if (isset($_POST['cancel'])) {
    $stmt = $pdo->prepare('UPDATE invoices SET `stt` = 1 WHERE invoiceID = ?');
    $stmt->bindParam(1, $_POST['invoiceid'], PDO::PARAM_STR);
    $stmt->execute();
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_GET['id'] = $_POST['invoiceid'];

    $stmt = $pdo->prepare("DELETE FROM invoice_noti WHERE invoiceID = ?");
    $stmt->bindParam(1, $_POST['invoiceid'], PDO::PARAM_STR);
    $stmt->execute();
}

$total = 0.0;
include 'navbar.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM billing WHERE invoiceID = ?');
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($bills)) {
        die('Invoice not exist!');
    }

    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE invoiceID = ?');
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $invoice = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($invoice)) {
        die('Invoice not exist!');
    }
} else {
    die('Invoice not exist!');
}
?>
<div class="justify-content-center text-center col-lg-8 col-md-10 col-sm-12 col-12 mx-auto p-5" style=" background-color:whitesmoke"> 
<hr>
    <table class="table-hover ml-5">
        <form action="" method="post">
            <input type="text" name="invoiceid" id="" value="<?= $invoice[0]['invoiceID'] ?>" hidden>
            <tr>
                <td colspan="2">
                    <h5>Date of the order: <span><?= $invoice[0]['CreateDate'] ?></span></h5>
                </td>
            </tr>
            <tr><td colspan="2"><h5>Invoice code: <span><?= $invoice[0]['invoiceID'] ?></span></h5></td></tr>
            <tr>
                <td>
                    <h5>Recipient:</h5>
                </td>
                <td><input type="text" name="name" required value="<?= $invoice[0]['name'] ?>" hidden>
                    <input type="text" name="name" required id="name" value="<?= $invoice[0]['name'] ?>" disabled>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Phone:
                </td>
                </h5>
                <td><input type="tel" name="phone" required pattern="(84|0[3|5|7|8|9])+([0-9]{8})\b" value="<?= $invoice[0]['phone'] ?>" hidden>
                    <input type="tel" name="phone" required pattern="(84|0[3|5|7|8|9])+([0-9]{8})\b" id="phone" value="<?= $invoice[0]['phone'] ?>" disabled>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Address:</h5>
                </td>
                <td><input type="text" name="address" required value="<?= $invoice[0]['address'] ?>" hidden>
                    <input type="text" name="address" required id="address" value="<?= $invoice[0]['address'] ?>" disabled>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Status:</h5>
                </td>
                <td><?php if ($invoice[0]['stt'] == 0) { ?>
                        PENDING <a id="editinvoice" href="">EDIT</a>
                        <br>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="btn btn-outline-danger" type="submit" name="cancel" onclick="return confirmcancel();" value="Cancel">
                </td>
                <td><input class="btn btn-outline-success"type="submit" id="confirm" hidden disabled>
                </td>
            </tr>

        <?php   } elseif ($invoice[0]['stt'] == 1) {
                        echo 'CANCELLED</td></tr>';
                    } elseif ($invoice[0]['stt'] == 2) {
                        echo 'SHIPPING</td></tr>';
                    } elseif ($invoice[0]['stt'] == 3) {
                        echo 'DECLINED</td></tr>';
                    } elseif ($invoice[0]['stt'] == 5) {
                        echo 'COMPLETED</td></tr>';
                    } elseif ($invoice[0]['stt'] == 4) {
                        echo 'DECLINED</td></tr>';
                    }
        ?>


        </form>
    </table>
    <hr>
    <div class="cart col-12 mx-auto">
        <h1 class="mx-auto">Item List</h1>

        <table class="col-12">
            <thead>
                <tr>
                    <td class="col-5">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $bill) : ?>
                    <?php
                    $stmt = $pdo->prepare('SELECT * FROM products WHERE productID = ?');
                    $stmt->bindParam(1, $bill['productID'], PDO::PARAM_STR);
                    $stmt->execute();
                    $pro = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <tr>

                        <td class="img">
                            <a class="truncated wrap my-auto" style="white-space: normal !important;" href="index?page=product&id=<?= $pro[0]['productID'] ?>">
                                <img src="imgs/<?= $pro[0]['img'] ?>" width="50" height="50" alt="<?= $pro[0]['name'] ?>">
                                <?= $pro[0]['name'] ?></a>

                        </td>
                        <td class="price">
                            <h4>$<?= $bill['price'] ?></h4>
                            <?php $total += $bill['price'] * $bill['quantity']; ?>
                        </td>
                        <td class="quantity">
                            <h4><?= $bill['quantity'] ?></h4>
                        </td>
                        <td class="price">
                            <h4>$<?= $bill['price'] * $bill['quantity'] ?></h4>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?= $total ?></span>
        </div>
    </div>
</div>
<script>
    $('#editinvoice').on('click', function() {
        event.preventDefault();
        $('#name').removeAttr('disabled');
        $('#phone').removeAttr('disabled');
        $('#address').removeAttr('disabled');
        $('#confirm').removeAttr('hidden');
        $('#confirm').removeAttr('disabled');
    });

    function confirmcancel() {
        if (confirm("Do you really want to cancel order?")) {
            // do stuff
        } else {
            return false;
        }
    }
</script>