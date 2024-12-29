<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["id"]) && $_GET["equipment"]) {
    $id = $_GET["id"];
    $equipment = $_GET["equipment"];
    $deleteEquipment = $equipmentsFacade->deleteEquipment($id, $equipment);
    if ($deleteEquipment) {
        header("Location: equipments.php?msg=Equipment has been deleted successfully!");
    }
}
