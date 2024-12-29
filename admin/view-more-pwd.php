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

if (isset($_GET["pwd_id"])) {
    $pwdId = $_GET["pwd_id"];
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
        <li class="nav-item active">
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
                        <?php
                        $fetchUsers = $userFacade->fetchPWDById($pwdId);
                        foreach ($fetchUsers as $users) {
                            $selectedBarangay = $users["barangay"];
                            $selectedDisability = $users["disability"];
                        ?>
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex align-items-center">
                                    <a href="pwd.php" class="mr-2"> <!-- Adds spacing between link and heading -->
                                        <span class="fas fa-fw fa-arrow-left"></span> Back
                                    </a>
                                    <span class="mx-2"> | </span>
                                    <h6 class="m-0 font-weight-bold">PWD Information</h6>
                                </div>
                                <form action="update-pwd-action.php" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!-- Username Field -->
                                                        <div class="form-floating mb-2">
                                                            <input type="text" class="form-control" id="occupation" name="occupation" 
                                                                   placeholder="Occupation" value="<?= htmlspecialchars($users['occupation'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                   disabled>
                                                            <label for="contact-person">Occupation</label>
                                                        </div>
                                                        <div class="form-floating mb-2">
                                                            <input type="text" class="form-control" id="contact-person" name="contact-person" 
                                                                   placeholder="Username" value="<?= htmlspecialchars($users['contact_person'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                   disabled>
                                                            <label for="contact-person">Contact Person</label>
                                                        </div>
                                                         <div class="form-floating mb-2">
                                                            <input type="text" class="form-control" id="contact-number" name="contact-number" 
                                                                   placeholder="Contact Number" value="<?= htmlspecialchars($users['contact_number'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                   disabled>
                                                            <label for="contact-person">Contact Number</label>
                                                        </div>
                                                        <div class="form-floating mb-2">
                                                            <input type="text" class="form-control" id="username" name="username" 
                                                                   placeholder="Username" value="<?= htmlspecialchars($users['username'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                   disabled>
                                                            <label for="username">Username</label>
                                                        </div>

                                                        <!-- User Status Field -->
                                                        <div class="form-floating mb-2">
                                                            <input type="text" class="form-control" id="userStatus" name="user_status" 
                                                                   placeholder="User Status" 
                                                                   value="<?= empty($users['status']) ? '-' : htmlspecialchars($users['status'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                   disabled>
                                                            <label for="userStatus">User Status</label>
                                                        </div>
                                                        <table class="table" display="display" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td>User Validation : 
                                                                        <?php if (!empty($users['user_validated'])): ?>
                                                                            <a href="#" 
                                                                               class="btn btn-secondary btn-sm <?= $users['user_validated'] === 1 ? 'disabled' : '' ?>" 
                                                                               <?= $users['user_validated'] === 1 ? 'onclick="return false;"' : '' ?>>
                                                                                Validated
                                                                            </a>
                                                                            <a href="pwd.php?updUserId=<?= htmlspecialchars($users['id'], ENT_QUOTES, 'UTF-8') ?>&canclVlidate=1" 
                                                                               class="btn btn-danger btn-sm">
                                                                                Cancel
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a href="pwd.php?userId=<?= htmlspecialchars($users['id'], ENT_QUOTES, 'UTF-8') ?>" 
                                                                               class="btn btn-primary btn-sm validateBtn">
                                                                                Validate
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
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
        $('.validateBtn').click(function(e) {
            e.preventDefault();

            const url = $(this).attr('href');

            const conf = confirm('Are you sure you want to cancel this validation?');
            if (conf) {
                window.location.href = url;
            } else {
                return false;
            }
        });
    });
</script>