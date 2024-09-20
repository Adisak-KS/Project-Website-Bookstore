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
    $locationError = "Location: ../account_order_history_detail?id=$base64Encoded";

    valiDateFromReview($prdId, $ordId, $memId, $prvRating, $prvDetail, $locationError);
    
    $insertReview = $ReviewController->insertAccountReview($prdId, $ordId, $memId, $prvRating, $prvDetail);



    if ($insertReview) {
        $_SESSION['success'] = "เพิ่มรีวิวสินค้าสำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
