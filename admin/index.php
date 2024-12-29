<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_SESSION["first_name"])) {
    $firstName = $_SESSION["first_name"];
}

if (isset($_SESSION["last_name"])) {
    $lastName = $_SESSION["last_name"];
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
        <li class="nav-item active">
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
                <form action="view-all-pwd-by-barangay.php" method="GET" target="_blank" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <select class="form-select" name="barangay" required>
                            <option value="">--- Select Barangay ---</option>
                            <option value="Barangay 1">Barangay 1</option>
                            <option value="Barangay 2">Barangay 2</option>
                            <option value="Barangay 3">Barangay 3</option>
                            <option value="Barangay 4">Barangay 4</option>
                            <option value="Barangay 5">Barangay 5</option>
                            <option value="Barangay 6">Barangay 6</option>
                            <option value="Barangay 7">Barangay 7</option>
                            <option value="Barangay 8">Barangay 8</option>
                            <option value="Barangay Alegria">Barangay Alegria</option>
                            <option value="Barangay Amatugan">Barangay Amatugan</option>
                            <option value="Barangay Antipolo">Barangay Antipolo</option>
                            <option value="Barangay Apalan">Barangay Apalan</option>
                            <option value="Barangay Bagasawe">Barangay Bagasawe</option>
                            <option value="Barangay Bakyawan">Barangay Bakyawan</option>
                            <option value="Barangay Bangkito">Barangay Bangkito</option>
                            <option value="Barangay Bulwang">Barangay Bulwang</option>
                            <option value="Barangay Kabangkalan">Barangay Kabangkalan</option>
                            <option value="Barangay Kalangahan">Barangay Kalangahan</option>
                            <option value="Barangay Kamansi">Barangay Kamansi</option>
                            <option value="Barangay Kan-an">Barangay Kan-an</option>
                            <option value="Barangay Kanlunsing">Barangay Kanlunsing</option>
                            <option value="Barangay Kansi">Barangay Kansi</option>
                            <option value="Barangay Caridad">Barangay Caridad</option>
                            <option value="Barangay Carmelo">Barangay Carmelo</option>
                            <option value="Barangay Cogon">Barangay Cogon</option>
                            <option value="Barangay Colonia">Barangay Colonia</option>
                            <option value="Barangay Daan Lungsod">Barangay Daan Lungsod</option>
                            <option value="Barangay Fortaliza">Barangay Fortaliza</option>
                            <option value="Barangay Ga-ang">Barangay Ga-ang</option>
                            <option value="Barangay Gimama-a">Barangay Gimama-a</option>
                            <option value="Barangay Jagbuaya">Barangay Jagbuaya</option>
                            <option value="Barangay Kabkaban">Barangay Kabkaban</option>
                            <option value="Barangay Kaba-o">Barangay Kaba-o</option>
                            <option value="Barangay Kampoot">Barangay Kampoot</option>
                            <option value="Barangay Kaorasan">Barangay Kaorasan</option>
                            <option value="Barangay Libo">Barangay Libo</option>
                            <option value="Barangay Lusong">Barangay Lusong</option>
                            <option value="Barangay Macupa">Barangay Macupa</option>
                            <option value="Barangay Mag-alwa">Barangay Mag-alwa</option>
                            <option value="Barangay Mag-antoy">Barangay Mag-antoy</option>
                            <option value="Barangay Mag-atubang">Barangay Mag-atubang</option>
                            <option value="Barangay Maghan-ay">Barangay Maghan-ay</option>
                            <option value="Barangay Manga">Barangay Manga</option>
                            <option value="Barangay Marmol">Barangay Marmol</option>
                            <option value="Barangay Molobolo">Barangay Molobolo</option>
                            <option value="Barangay Montealegre">Barangay Montealegre</option>
                            <option value="Barangay Putat">Barangay Putat</option>
                            <option value="Barangay San Juan">Barangay San Juan</option>
                            <option value="Barangay Sandayong">Barangay Sandayong</option>
                            <option value="Barangay Santo Niño">Barangay Santo Niño</option>
                            <option value="Barangay Siotes">Barangay Siotes</option>
                            <option value="Barangay Sumon">Barangay Sumon</option>
                            <option value="Barangay Tumugpa">Barangay Tumugpa</option>
                            <option value="Barangay Tominjao">Barangay Tominjao</option>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
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
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">View All PWD</h6>
                            </div>
                            <div class="card-body">
                                <a href="view-all-pwd.php" class="btn btn-primary w-100" target="_blank">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-body" id="card-body-custom-scrollbar">
                                <table class="table-custom" width="100%">
                                <thead>
                                    <tr>
                                        <th>BARANGAY</th>
                                        <th>TOTAL PWD</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php
                                            $barangays = [
                                                "Barangay 1",
                                                "Barangay 2",
                                                "Barangay 3",
                                                "Barangay 4",
                                                "Barangay 5",
                                                "Barangay 6",
                                                "Barangay 7",
                                                "Barangay 8",
                                                "Barangay Alegria",
                                                "Barangay Amatugan",
                                                "Barangay Antipolo",
                                                "Barangay Apalan",
                                                "Barangay Bagasawe",
                                                "Barangay Bakyawan",
                                                "Barangay Bangkito",
                                                "Barangay Bulwang",
                                                "Barangay Kabangkalan",
                                                "Barangay Kalangahan",
                                                "Barangay Kamansi",
                                                "Barangay Kan-an",
                                                "Barangay Kanlunsing",
                                                "Barangay Kansi",
                                                "Barangay Caridad",
                                                "Barangay Carmelo",
                                                "Barangay Cogon",
                                                "Barangay Colonia",
                                                "Barangay Daan Lungsod",
                                                "Barangay Fortaliza",
                                                "Barangay Ga-ang",
                                                "Barangay Gimama-a",
                                                "Barangay Jagbuaya",
                                                "Barangay Kabkaban",
                                                "Barangay Kaba-o",
                                                "Barangay Kampoot",
                                                "Barangay Kaorasan",
                                                "Barangay Libo",
                                                "Barangay Lusong",
                                                "Barangay Macupa",
                                                "Barangay Mag-alwa",
                                                "Barangay Mag-antoy",
                                                "Barangay Mag-atubang",
                                                "Barangay Maghan-ay",
                                                "Barangay Manga",
                                                "Barangay Marmol",
                                                "Barangay Molobolo",
                                                "Barangay Montealegre",
                                                "Barangay Putat",
                                                "Barangay San Juan",
                                                "Barangay Sandayong",
                                                "Barangay Santo Niño",
                                                "Barangay Siotes",
                                                "Barangay Sumon",
                                                "Barangay Tumugpa",
                                                "Barangay Tominjao"
                                            ];
                                            foreach ($barangays as $barangay) {
                                                echo "<tr>";
                                                echo "<td class='scrollable-td'>$barangay</td>";
                                                echo "<td style='max-height: 50px; overflow-y: auto;'>" . $userFacade->fetchBarangay($barangay) . "</td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
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