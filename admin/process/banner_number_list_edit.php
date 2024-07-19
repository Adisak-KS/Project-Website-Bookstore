<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/BannerController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$BannerController = new BannerController($conn);

if (isset($_POST['order'])) {
    $order = $_POST['order'];

    foreach ($order as $item) {
        $bnId = $item['id'];
        $bnNumberList = $item['bn_number_list'];
        $BannerController->updateSortTableList($bnNumberList, $bnId);
    }
    echo json_encode(['status' => 'success']);
} else {
    header('Location: ../error_not_result');
    exit;
}
