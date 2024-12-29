<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["equipment_id"])) {
    $equipmentId = $_GET["equipment_id"];
    $deleteEquipment = $inventoryFacade->deleteInvEquipment($equipmentId);
    if ($deleteEquipment) {
        header("Location: inventory-equipments.php?msg=Equipment has been deleted successfully!");
    }
}
