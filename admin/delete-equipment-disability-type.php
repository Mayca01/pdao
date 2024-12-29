<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["id"]) && $_GET['equipment_id']) {
    
    $id = $_GET["id"];
    $equipId = $_GET["equipment_id"];

    $deleteEquipment = $inventoryFacade->deleteDisabilityTypesEquipmentById($id);
    if ($deleteEquipment) {
        header("Location: equipment-disability-types.php?equipment_id=".$equipId."&msg=Disability type has been deleted successfully!");
    }
}
