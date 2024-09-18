<?php
require_once('../db/connectdb.php');
require_once('../db/controller/ReviewController.php');
require_once('../includes/functions.php');

$ReviewController = new ReviewController($conn);

if (isset($_POST['btn-review-add'])) {
    $prdId = $_POST["prd_id"];
    $ordId = $_POST["ord_id"];
    $memId = $_POST["mem_id"];
    $prvRating = $_POST['prv_rating'];
    $prvDetail = $_POST["prv_detail"];

    $base64Encoded =  $_SESSION["base64Encoded"];

    $locationSuccess = "Location: ../account_order_history_detail?id=$base64Encoded";
    $locationSuccess = "Location: ../account_order_history_detail?id=$base64Encoded";

    echo "prd id =" . $prdId . "<br>";
    echo "ord id =" . $ordId . "<br>";
    echo "mem id =" . $memId . "<br>";
    echo "prv rating =" . $prvRating . "<br>";
    echo "prv detail =" . $prvDetail . "<br>";

    $updateReview = $ReviewController->updateAccountReview($prdId, $ordId, $memId, $prvRating, $prvDetail);



    if ($updateReview) {
        $_SESSION['success'] = "แก้ไขรีวิวสินค้าสำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
