<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');
require_once(__DIR__ . '/../../includes/salt.php');

$ProductController = new ProductController($conn);

if (isset($_POST['btn-edit'])) {
    $prdId = $_POST['prd_id'];
    $prdName = $_POST['prd_name'];
    $prdISBN = $_POST['prd_isbn'];
    $prdCoin = $_POST['prd_coin'];
    $prdQuantity = $_POST['prd_quantity'];
    $prdNumberPages = $_POST['prd_number_pages'];
    $prdPrice = $_POST['prd_price'];
    $prdPercentDiscount = $_POST['prd_percent_discount'];	
    $ptyId = $_POST['pty_id'];
    $pubId = $_POST['pub_id'];
    $authId = $_POST['auth_id'];
    $prdPreorder = $_POST['prd_preorder'];
    $prdStatus = $_POST['prd_status'];
    $prdDetail = $_POST['prd_detail'];

    $prdImg1 = $_POST['prd_img1'];
    $prdNewImg1 = $_FILES['prd_newImg1']['name'];

    $prdImg2 = $_POST['prd_img2'];
    $prdNewImg2 = $_FILES['prd_newImg2']['name'];


    echo "prdId : ". $prdId . "<br>";
    echo "prdName : ". $prdName . "<br>";
    echo "prdISBN : ". $prdISBN . "<br>";
    echo "prdCoin : ". $prdCoin . "<br>";
    echo "prdQuqantity : ". $prdQuantity . "<br>";
    echo "prdPrice : ". $prdPrice . "<br>";
    echo "ptyId : ". $ptyId . "<br>";
    echo "pubId : ". $pubId . "<br>";
    echo "authId : ". $authId . "<br>";
    echo "prdPreorder : ". $prdPreorder . "<br>";
    echo "prdStatus : ". $prdStatus . "<br>";
    echo "prdDetail : ". $prdDetail . "<br>";
    echo "<hr>";
    echo "prdImg1 : ". $prdImg1 . "<br>";
    echo "prdNewImg1 : ". $prdNewImg1 . "<br>";
    echo "<hr>";
    echo "prdImg2 : ". $prdImg2 . "<br>";
    echo "prdNewImg2 : ". $prdNewImg2 . "<br>";
    
    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_edit_form.php?id=$base64Encoded";
    $locationSuccess = "Location: ../product_show.php";


    validateFormUpdateProduct($prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice,$prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus, $locationError);

}else {
    header('Location: ../error_not_result.php');
    exit;
}
