<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["pwd_id"]) && isset($_GET["pwd_medical_information"])) {
    $pwdId = $_GET["pwd_id"];
    $medicalInformation = '../' . $_GET["pwd_medical_information"];
    $deletePwd = $userFacade->deletePwd($pwdId);
    if ($deletePwd) {
        unlink($medicalInformation);
        header("Location: pwd.php?msg=PWD has been deleted successfully!");
    }
}
