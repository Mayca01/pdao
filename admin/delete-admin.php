<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["user_id"])) {
    $userId = $_GET["user_id"];
    $deleteAdmin = $userFacade->deletePwd($userId);
    if ($deleteAdmin) {
        header("Location: admin.php?msg=Admin has been deleted successfully!");
    }
}
