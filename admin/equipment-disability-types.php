<?php
include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_SESSION["first_name"])) {
    $firstName = $_SESSION["first_name"];
}
if (isset($_SESSION["last_name"])) {
    $lastName = $_SESSION["last_name"];
}
if (isset($_GET["msg"])) {
    array_push($success, $_GET["msg"]);
}

$uri_query_segment = null;

if (isset($_GET["equipment_id"]) && is_numeric($_GET["equipment_id"]))
{
    $uri_query_segment = htmlspecialchars($_GET["equipment_id"],  ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['disability-type-save'])) {
    $equipmentId = htmlspecialchars($_POST['equipmentId']);
    $disabilityType = htmlspecialchars($_POST['disabilityTypes']);

    $result = $inventoryFacade->addDisabilityTypes($equipmentId, $disabilityType);
    if ($result) {
        header("Location: equipment-disability-types.php?equipment_id=".$equipmentId."&msg= Disability types added successfully.");
    }
}

if (isset($_POST['edit_disability-type-save'])) {
    $equipmentId = htmlspecialchars($_POST['equipmentId']);
    $eqpDisabilityId = htmlspecialchars($_POST['eqpDisabilityId']);
    $editDisabilityType = htmlspecialchars($_POST['edit_disability_types']);
    $result = $inventoryFacade->updateDisabilityTypes($eqpDisabilityId, $editDisabilityType);
    if ($result) {
        header("Location: equipment-disability-types.php?equipment_id=".$equipmentId."&msg= Disability types updated successfully.");
    }
}

?>
<div id="wrapper">
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #315a39;">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="sidebar-brand-text mx-3">PDAO</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-fw fa-user"></i>
                <span>Admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pwd.php">
                <i class="fas fa-solid fa-user"></i>
                <span>PWD</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="assistance.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assistance</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="equipments.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assign Equipments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="inventory-equipments.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Inventory Equipments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="informations.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Announcements</span>
            </a>
        </li>
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $firstName . ' ' . $lastName ?></span>
                            <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?= $firstName . '+' . $lastName ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
              
                <?php include('.././errors.php') ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex align-items-center">
                                <a href="inventory-equipments.php" class="mr-2"> <!-- Adds spacing between link and heading -->
                                    <span class="fas fa-fw fa-arrow-left"></span> Back
                                </a>
                                <span class="mx-2"> | </span>
                                <h6 class="m-0 font-weight-bold"> <span class="fas fa-fw fa-wheelchair"></span> Equipment Disability Types </h6>
                            </div>
                            <div class="card-body">
                                <?php 
                                    // Fetch equipment data
                                    $equipmentData = $inventoryFacade->fetchInventoryDataByEquipId($uri_query_segment);
                                    foreach ($equipmentData as $eqp): 
                                ?>
                                <?php 
                                    // Check if edit mode is triggered
                                    if (isset($_GET['equipment_id']) && isset($_GET['edit_type'])):
                                        $editEqpDisabilityType = $inventoryFacade->getEqpDisabilityTypeById($_GET['edit_type']);
                                        $disabilityType = "";
                                        foreach ($editEqpDisabilityType as $row) {
                                            $eqpDisabilityId = $row['id'];
                                            $disabilityType = $row['disability'];
                                        }
                                ?>
                                        <form action="equipment-disability-types.php" id="edit-equipment-form" method="post" class="d-flex flex-wrap align-items-center">
                                            <input type="hidden" name="equipmentId" value="<?= htmlspecialchars($eqp['equipment_id']) ?>" />
                                            <input type="hidden" name="eqpDisabilityId" value="<?= htmlspecialchars($eqpDisabilityId) ?>" />
                                            <div class="form-group mr-3 mb-2">
                                                <label for="edit-disability-types" class="sr-only">Disability Types</label>  
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="edit_disability_types" 
                                                    name="edit_disability_types" 
                                                    value="<?= htmlspecialchars($disabilityType) ?>" 
                                                    placeholder="Disability Types" 
                                                    autocomplete="off" 
                                                    required>
                                            </div>
                                            <button type="submit" name="edit_disability-type-save" class="btn btn-primary mb-2">
                                                <span class="fas fa-fw fa-save"></span> Update
                                            </button>
                                            <a href="equipment-disability-types.php?equipment_id=<?= $uri_query_segment ?>" class="btn btn-danger mb-2 mx-1">
                                                <span class="fas fa-fw fa-times"></span> Cancel
                                            </a>
                                        </form>
                                    
                                <?php else: ?>
                                    <form action="equipment-disability-types.php" id="new-equipment-form" method="post" class="d-flex flex-wrap align-items-center">
                                        <input type="hidden" name="equipmentId" value="<?= htmlspecialchars($eqp['equipment_id']) ?>" />
                                        <div class="form-group mr-3 mb-2">
                                            <label for="disability-types" class="sr-only">Disability Types</label>  
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="disability-types" 
                                                name="disabilityTypes" 
                                                placeholder="Disability Types" 
                                                autocomplete="off" 
                                                required>
                                        </div>
                                        <button type="submit" name="disability-type-save" class="btn btn-primary mb-2">
                                            <span class="fas fa-fw fa-plus"></span> Add
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <hr style="padding:0; margin:0; border: 1px solid #ccc;" />

                                <!-- Table displaying disability types -->
                                <div class="table-responsive mt-2">
                                    <table class="table" class="display" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Disability Types</th>
                                                <th>Equipment Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                                $disabilityEquipment = $inventoryFacade->getDisabilityTypesEquimentById($uri_query_segment);
                                                foreach ($disabilityEquipment as $disabilityEquip): 
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($disabilityEquip['disability']) ?></td>
                                                <td><?= htmlspecialchars($disabilityEquip['equipment_name']) ?></td>
                                                <td>
                                                    <a 
                                                        href="equipment-disability-types.php?equipment_id=<?= $uri_query_segment ?>&edit_type=<?= $disabilityEquip['id'] ?>" 
                                                        class="btn btn-sm btn-primary outline">
                                                        <span class="fas fa-fw fa-edit"></span> Edit
                                                    </a>
                                                    <a 
                                                        href="delete-equipment-disability-type.php?id=<?= $disabilityEquip['id'] ?>&equipment_id=<?= $disabilityEquip['equipment_id'] ?>" 
                                                        class="delete-disability-type btn btn-danger outline btn-sm">
                                                        <span class="fas fa-fw fa-times"></span> Remove
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; PDAO 2024</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../sign-out.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php include realpath(__DIR__ . '/../app/layout/admin-footer.php') ?>

<script type="text/javascript">
    $(document).ready(function() {

        $('.delete-disability-type').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            var conf = confirm("Are you sure want to delete this data?");

            if (conf) {
                window.location.href = url;
            } else {
                return false;
            }
        });
    });
</script>