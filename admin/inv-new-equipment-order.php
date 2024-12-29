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

$user_id = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['new-equipment-order-save'])) {
    $equipment = htmlspecialchars($_POST['equipment']);
    $order_qty = htmlspecialchars($_POST['order_qty']);
    $arrive_date = htmlspecialchars($_POST['arrive_date']);

    $newOrder = $inventoryFacade->newEquipmentOrderSave($equipment, $order_qty, $arrive_date);
    if ($newOrder) {
        $msg = "New order successfully added.";
        header("Location: inv-new-equipment-order.php?msg=".$msg);
    }
}

if (isset($_POST['edit-equipment-order-save'])) {
    $id = $_POST['order_id'];
    $equipment = htmlspecialchars($_POST['equipment']);
    $order_qty = htmlspecialchars($_POST['order_qty']);
    $arrive_date = htmlspecialchars($_POST['arrive_date']);

    $newOrder = $inventoryFacade->updateEquipmentOrderSave($id, $equipment, $order_qty, $arrive_date);
    if ($newOrder) {
        $msg = "New order successfully updated.";
        header("Location: inv-new-equipment-order.php?msg=".$msg);
    }
}

if (isset($_GET['orderno'])) {
    $id = $_GET['orderno'];

    $rcvdOrder = $inventoryFacade->recivedEquipmentOrder($id, $user_id);
    if ($rcvdOrder) {
        $msg = "Order equipment recived successfully.";
        header("Location: inv-new-equipment-order.php?msg=".$msg);
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
                                <h6 class="m-0 font-weight-bold">New Equipment Order Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <form action="inv-new-equipment-order.php" id="new-equipment-form" method="post" class="d-flex flex-wrap align-items-center">
                                    <?php 
                                        $id = "";
                                        $equipment = "";
                                        $qty = "";
                                        $date_arrived = "";
                                        if (isset($_GET['edit_orderno'])) {
                                            $id = $_GET['edit_orderno'];
                                            $getData = $inventoryFacade->getNewEquipmentOrder($id);
                                            foreach ($getData as $row) {
                                                $id = $row['id'];
                                                $equipment = $row['equipment_id'];
                                                $qty = $row['qty'];
                                                $date_arrived = $row['expected_arrived_date'];
                                            }
                                        }
                                    ?>
                                    <?php if (isset($_GET['edit_orderno'])): ?>
                                        <input type="hidden" id="arrive_date" name="order_id" value="<?= $id ?>"/>
                                    <?php endif; ?>
                                    <div class="form-group mr-3 mb-2 position-relative">
                                        <select class="form-control" id="equipment" name="equipment" placeholder="Equipment" autocomplete="off" required>
                                            <option value="">--- Select Equipment ---</option>
                                            <?php 
                                                $equipmentList = $inventoryFacade->fetchInventoryEquipment();
                                                foreach($equipmentList as $row): 
                                            ?>
                                                <option value="<?= $row['equipment_id'] ?>" <?php if ($row['equipment_id'] == $equipment) echo 'selected'; ?>>
                                                    <?= $row['equipment_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="equipment" class="position-absolute" style="top: -15px; border: 1px solid #ccc; background-color: #fff; padding: 2px 5px 2px 5px; left: 10px; font-size: 0.8rem;"><strong> Equipment</strong></label>
                                    </div>
                                    <div class="form-group mr-3 mb-2 position-relative">
                                        <input type="number" class="form-control" id="order_qty" name="order_qty" value="<?php echo isset($qty) ? htmlspecialchars($qty, ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Enter Quantity" autocomplete="off" min="0" required>
                                        <label for="order_qty" class="position-absolute" style="top: -15px; border: 1px solid #ccc; background-color: #fff; padding: 2px 5px 2px 5px; left: 10px; font-size: 0.8rem;"><strong> Quantity</strong></label>
                                    </div>
                                    <div class="form-group mr-3 mb-2 position-relative">
                                        <input type="date" class="form-control" id="arrive_date" name="arrive_date" value="<?= isset($_GET['edit_orderno'])  ? htmlspecialchars($date_arrived, ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Expected Arrive Date" title="Expected Arrive Date" autocomplete="off" min="0" required>
                                        <label for="arrive_date" class="position-absolute" style="top: -15px; border: 1px solid #ccc; background-color: #fff; padding: 2px 5px 2px 5px; left: 10px; font-size: 0.8rem;"><strong> Date of Arrival</strong></label>
                                    </div>
                                    <button type="submit" name="<?= isset($_GET['edit_orderno']) ? 'edit-equipment-order-save' : 'new-equipment-order-save'; ?>" class="btn btn-primary mb-2">
                                        <?= isset($_GET['edit_orderno'])  ? 'Update' : 'New Item' ?>
                                    </button>

                                    <!-- History Button positioned next to New Supply button -->
                                    <a href="order-recieved-history.php" class="btn btn-info btn-sm ml-auto mb-2">
                                        <span class="fas fa-fw fa-book"></span> History
                                    </a>
                                </form>
                                </div>

                                <hr style="padding:0; margin:0; border: 1px solid #ccc;" />

                                <div class="table-responsive mt-2">
                                    <table class="table" class="display" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th> Equipment</th>
                                                <th> Quantity</th>
                                                <th> Date of Arrival</th>
                                                <th> Date Recieved</th>
                                                <th> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                $orderNewEquipmentData = $inventoryFacade->fetchNewOrderEquipmentData();
                                                foreach ($orderNewEquipmentData as $row) :
                                            ?>
                                                <tr>
                                                    <td> <?= $row["equipment_name"] ?></td>
                                                    <td> <?= $row["qty"] ?></td>
                                                    <td> <?= date('M d, Y', strtotime($row["expected_arrived_date"])) ?></td>
                                                    <td> <?= !empty($row["rcvd_date"]) ? date('F j, Y h:i:s a', strtotime($row['rcvd_date'])) : '-' ?> </td>
                                                    <td>
                                                        <a href="inv-new-equipment-order.php?edit_orderno=<?= $row['id']; ?>" class="btn btn-primary btn-sm <?= !empty($row['status']) ? 'disabled' : '' ?>"> 
                                                            <span class="fas fa-fw fa-edit"></span> Edit
                                                        </a>
                                                        <a href="inv-new-equipment-order.php?orderno=<?= $row['id']; ?>" id="rcvBtnOrder" class="btn btn-primary btn-sm <?= !empty($row['status']) ? 'disabled' : '' ?>" data-id="<?= $row['id']; ?>">
                                                            <span class="fas fa-fw fa-check"></span> <?= !empty($row['status']) ? 'Recieved' : 'Recieve Order' ?>
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
        $('#rcvBtnOrder').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href'); 

            var conf = confirm("Are you sure you want to recieve this order?");

            if (conf) {
                window.location.href = url;
            } else {
                return false;
            }
        });
    });
</script>
