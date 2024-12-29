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
                                    <h6 class="m-0 font-weight-bold"><span class="fas fa-fw fa-edit"></span> Update PWD</h6>
                                </div>
                                <form action="update-pwd-action.php" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h1 class="h3 my-4 fw-normal text-center">Personal Information</h1>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="firstname" name="first_name" placeholder="First Name" value="<?= $users["first_name"] ?>">
                                                            <label for="firstname">First Name</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="lastname" name="last_name" placeholder="Last Name" value="<?= $users["last_name"] ?>">
                                                            <label for="lastname">Last Name</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control" id="age" name="age" placeholder="Age" value="<?= $users["age"] ?>">
                                                            <label for="age">Age</label>
                                                        </div>
                                                        <div>
                                                            <select class="form-select py-3" name="barangay">
                                                                <option value="None" <?= $selectedBarangay === 'None' ? 'selected' : '' ?>>--- Select Barangay ---</option>
                                                                <option value="Barangay 1" <?= $selectedBarangay === 'Barangay 1' ? 'selected' : '' ?>>Barangay 1</option>
                                                                <option value="Barangay 2" <?= $selectedBarangay === 'Barangay 2' ? 'selected' : '' ?>>Barangay 2</option>
                                                                <option value="Barangay 3" <?= $selectedBarangay === 'Barangay 3' ? 'selected' : '' ?>>Barangay 3</option>
                                                                <option value="Barangay 4" <?= $selectedBarangay === 'Barangay 4' ? 'selected' : '' ?>>Barangay 4</option>
                                                                <option value="Barangay 5" <?= $selectedBarangay === 'Barangay 5' ? 'selected' : '' ?>>Barangay 5</option>
                                                                <option value="Barangay 6" <?= $selectedBarangay === 'Barangay 6' ? 'selected' : '' ?>>Barangay 6</option>
                                                                <option value="Barangay 7" <?= $selectedBarangay === 'Barangay 7' ? 'selected' : '' ?>>Barangay 7</option>
                                                                <option value="Barangay 8" <?= $selectedBarangay === 'Barangay 8' ? 'selected' : '' ?>>Barangay 8</option>
                                                                <option value="Barangay Alegria" <?= $selectedBarangay === 'Barangay Alegria' ? 'selected' : '' ?>>Barangay Alegria</option>
                                                                <option value="Barangay Amatugan" <?= $selectedBarangay === 'Barangay Amatugan' ? 'selected' : '' ?>>Barangay Amatugan</option>
                                                                <option value="Barangay Antipolo" <?= $selectedBarangay === 'Barangay Antipolo' ? 'selected' : '' ?>>Barangay Antipolo</option>
                                                                <option value="Barangay Apalan" <?= $selectedBarangay === 'Barangay Apalan' ? 'selected' : '' ?>>Barangay Apalan</option>
                                                                <option value="Barangay Bagasawe" <?= $selectedBarangay === 'Barangay Bagasawe' ? 'selected' : '' ?>>Barangay Bagasawe</option>
                                                                <option value="Barangay Bakyawan" <?= $selectedBarangay === 'Barangay Bakyawan' ? 'selected' : '' ?>>Barangay Bakyawan</option>
                                                                <option value="Barangay Bangkito" <?= $selectedBarangay === 'Barangay Bangkito' ? 'selected' : '' ?>>Barangay Bangkito</option>
                                                                <option value="Barangay Bulwang" <?= $selectedBarangay === 'Barangay Bulwang' ? 'selected' : '' ?>>Barangay Bulwang</option>
                                                                <option value="Barangay Kabangkalan" <?= $selectedBarangay === 'Barangay Kabangkalan' ? 'selected' : '' ?>>Barangay Kabangkalan</option>
                                                                <option value="Barangay Kalangahan" <?= $selectedBarangay === 'Barangay Kalangahan' ? 'selected' : '' ?>>Barangay Kalangahan</option>
                                                                <option value="Barangay Kamansi" <?= $selectedBarangay === 'Barangay Kamansi' ? 'selected' : '' ?>>Barangay Kamansi</option>
                                                                <option value="Barangay Kan-an" <?= $selectedBarangay === 'Barangay Kan-an' ? 'selected' : '' ?>>Barangay Kan-an</option>
                                                                <option value="Barangay Kanlunsing" <?= $selectedBarangay === 'Barangay Kanlunsing' ? 'selected' : '' ?>>Barangay Kanlunsing</option>
                                                                <option value="Barangay Kansi" <?= $selectedBarangay === 'Barangay Kansi' ? 'selected' : '' ?>>Barangay Kansi</option>
                                                                <option value="Barangay Caridad" <?= $selectedBarangay === 'Barangay Caridad' ? 'selected' : '' ?>>Barangay Caridad</option>
                                                                <option value="Barangay Carmelo" <?= $selectedBarangay === 'Barangay Carmelo' ? 'selected' : '' ?>>Barangay Carmelo</option>
                                                                <option value="Barangay Cogon" <?= $selectedBarangay === 'Barangay Cogon' ? 'selected' : '' ?>>Barangay Cogon</option>
                                                                <option value="Barangay Colonia" <?= $selectedBarangay === 'Barangay Colonia' ? 'selected' : '' ?>>Barangay Colonia</option>
                                                                <option value="Barangay Daan Lungsod" <?= $selectedBarangay === 'Barangay Daan Lungsod' ? 'selected' : '' ?>>Barangay Daan Lungsod</option>
                                                                <option value="Barangay Fortaliza" <?= $selectedBarangay === 'Barangay Fortaliza' ? 'selected' : '' ?>>Barangay Fortaliza</option>
                                                                <option value="Barangay Ga-ang" <?= $selectedBarangay === 'Barangay Ga-ang' ? 'selected' : '' ?>>Barangay Ga-ang</option>
                                                                <option value="Barangay Gimama-a" <?= $selectedBarangay === 'Barangay Gimama-a' ? 'selected' : '' ?>>Barangay Gimama-a</option>
                                                                <option value="Barangay Jagbuaya" <?= $selectedBarangay === 'Barangay Jagbuaya' ? 'selected' : '' ?>>Barangay Jagbuaya</option>
                                                                <option value="Barangay Kabkaban" <?= $selectedBarangay === 'Barangay Kabkaban' ? 'selected' : '' ?>>Barangay Kabkaban</option>
                                                                <option value="Barangay Kaba-o" <?= $selectedBarangay === 'Barangay Kaba-o' ? 'selected' : '' ?>>Barangay Kaba-o</option>
                                                                <option value="Barangay Kampoot" <?= $selectedBarangay === 'Barangay Kampoot' ? 'selected' : '' ?>>Barangay Kampoot</option>
                                                                <option value="Barangay Kaorasan" <?= $selectedBarangay === 'Barangay Kaorasan' ? 'selected' : '' ?>>Barangay Kaorasan</option>
                                                                <option value="Barangay Libo" <?= $selectedBarangay === 'Barangay Libo' ? 'selected' : '' ?>>Barangay Libo</option>
                                                                <option value="Barangay Lusong" <?= $selectedBarangay === 'Barangay Lusong' ? 'selected' : '' ?>>Barangay Lusong</option>
                                                                <option value="Barangay Macupa" <?= $selectedBarangay === 'Barangay Macupa' ? 'selected' : '' ?>>Barangay Macupa</option>
                                                                <option value="Barangay Mag-alwa" <?= $selectedBarangay === 'Barangay Mag-alwa' ? 'selected' : '' ?>>Barangay Mag-alwa</option>
                                                                <option value="Barangay Mag-antoy" <?= $selectedBarangay === 'Barangay Mag-antoy' ? 'selected' : '' ?>>Barangay Mag-antoy</option>
                                                                <option value="Barangay Mag-atubang" <?= $selectedBarangay === 'Barangay Mag-atubang' ? 'selected' : '' ?>>Barangay Mag-atubang</option>
                                                                <option value="Barangay Maghan-ay" <?= $selectedBarangay === 'Barangay Maghan-ay' ? 'selected' : '' ?>>Barangay Maghan-ay</option>
                                                                <option value="Barangay Manga" <?= $selectedBarangay === 'Barangay Manga' ? 'selected' : '' ?>>Barangay Manga</option>
                                                                <option value="Barangay Marmol" <?= $selectedBarangay === 'Barangay Marmol' ? 'selected' : '' ?>>Barangay Marmol</option>
                                                                <option value="Barangay Molobolo" <?= $selectedBarangay === 'Barangay Molobolo' ? 'selected' : '' ?>>Barangay Molobolo</option>
                                                                <option value="Barangay Montealegre" <?= $selectedBarangay === 'Barangay Montealegre' ? 'selected' : '' ?>>Barangay Montealegre</option>
                                                                <option value="Barangay Putat" <?= $selectedBarangay === 'Barangay Putat' ? 'selected' : '' ?>>Barangay Putat</option>
                                                                <option value="Barangay San Juan" <?= $selectedBarangay === 'Barangay San Juan' ? 'selected' : '' ?>>Barangay San Juan</option>
                                                                <option value="Barangay Sandayong" <?= $selectedBarangay === 'Barangay Sandayong' ? 'selected' : '' ?>>Barangay Sandayong</option>
                                                                <option value="Barangay Santo Niño" <?= $selectedBarangay === 'Barangay Santo Niño' ? 'selected' : '' ?>>Barangay Santo Niño</option>
                                                                <option value="Barangay Siotes" <?= $selectedBarangay === 'Barangay Siotes' ? 'selected' : '' ?>>Barangay Siotes</option>
                                                                <option value="Barangay Sumon" <?= $selectedBarangay === 'Barangay Sumon' ? 'selected' : '' ?>>Barangay Sumon</option>
                                                                <option value="Barangay Tumugpa" <?= $selectedBarangay === 'Barangay Tumugpa' ? 'selected' : '' ?>>Barangay Tumugpa</option>
                                                                <option value="Barangay Tominjao" <?= $selectedBarangay === 'Barangay Tominjao' ? 'selected' : '' ?>>Barangay Tominjao</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?= $users["address"] ?>">
                                                            <label for="address">Address</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Occupation" value="<?= $users["occupation"] ?>">
                                                            <label for="occupation">Occupation</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="contactPerson" name="contact_person" placeholder="Contact Person" value="<?= $users["contact_person"] ?>">
                                                            <label for="contactPerson">Contact Person</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="contactNumber" name="contact_number" placeholder="Contact Number" value="<?= $users["contact_number"] ?>">
                                                            <label for="contactNumber">Contact Number</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" name="disability" class="form-control" value="<?= $users["disability"] ?>" readonly />
                                                            <label>Disability</label>
                                                        </div>
                                                        <!-- <input type="hidden" name="old_medical_information" value="//$users["medical_information"]"> -->
                                                        <input type="hidden" name="pwd_id" value="<?= $users["id"] ?>">
                                                    </div>
                                                </div>
                                                <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="update">Update</button>
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