<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["information_id"]) && isset($_GET["image"])) {
    $informationId = $_GET["information_id"];
    $image = $_GET["image"];
    $deleteInformation = $informationsFacade->deleteInformation($informationId);
    if ($deleteInformation) {
        unlink($image);
        header("Location: informations.php?msg=Information has been deleted successfully!");
    }
}
