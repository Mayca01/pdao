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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Inventory Equipments Information</h6>
                                <div class="d-flex">
                                    <a href="add-inv-equipment.php" class="btn btn-primary btn-sm ml-2" role="button">
                                      <span class="fas fa-fw fa-plus"></span> New Equipment
                                    </a>
                                    <a href="inv-new-equipment-order.php" class="btn btn-info btn-sm ml-2" role="button">
                                      <span class="fas fa-fw fa-book"></span> New Supply
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" class="display" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th> Equipment Name</th>
                                                <th> Stocks</th>
                                                <th class="exclude"> Remarks</th>
                                                <th class="exclude">History</th>
                                                <th class="exclude"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                $fetchInventory = $inventoryFacade->fetchInventoryEquipment();
                                                foreach ($fetchInventory as $inv) : 
                                                    $available = "text-success";
                                                    $unavailable = "text-danger";
                                            ?>
                                                <tr>
                                                    <td> <?= $inv["equipment_name"] ?></td>
                                                    <td> <?= $inv["stocks"] ?></td>
                                                    <td>
                                                        <?= 
                                                            ($inv["stocks"] === 0 
                                                                ? '<span class="' . $unavailable . '">Unavailable</span>' 
                                                                : '<span class="' . $available . '">Available</span>'
                                                            ) 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="equipment-released-history.php?id=<?= $inv['equipment_id'] ?>" class="btn btn-primary btn-sm"> View</a>
                                                    </td>
                                                    <td>
                                                        <a href="equipment-disability-types.php?equipment_id=<?= $inv["equipment_id"]?>" class="btn btn-primary btn-sm">
                                                            <span class="fas fa-fw fa-wheelchair"></span> Disability Types
                                                        </a>
                                                        <a href="update-inv-equipment.php?equipment_id=<?= $inv["equipment_id"] ?>" class="btn btn-primary btn-sm">
                                                            <span class="fas fa-fw fa-edit"></span> Update
                                                        </a>
                                                        <a href="delete-inv-equipment.php?equipment_id=<?= $inv["equipment_id"] ?>" data-id="<?= $inv["equipment_id"] ?>" class="delete-inv-equipment btn btn-danger btn-sm">
                                                            <span class="fas fa-fw fa-trash"></span> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
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
        $('.delete-inv-equipment').click(function(e) {
            e.preventDefault();
            
            var deleteUrl = $(this).attr('href'); 

            var conf = confirm("Are you sure you want to delete this data?");

            if (conf) {
                window.location.href = deleteUrl;
            } else {
                return false;
            }
        });
    });
</script>
