<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
}

if (isset($_SESSION["first_name"])) {
    $firstName = $_SESSION["first_name"];
}

if (isset($_SESSION["last_name"])) {
    $lastName = $_SESSION["last_name"];
}

if (isset($_GET["msg"])) {
    array_push($success, $_GET["msg"]);
}

if (isset($_POST["add_admin"])) {
    $userType = 1;
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if (empty($firstName)) {
        array_push($invalid, 'First Name should not be empty!');
    }
    if (empty($lastName)) {
        array_push($invalid, 'Last Name should not be empty!');
    }
    if (empty($email)) {
        array_push($email, 'Email should not be empty!');
    }
    if (empty($username)) {
        array_push($invalid, 'Username should not be empty!');
    }
    if (empty($password)) {
        array_push($invalid, 'Password should not be empty!');
    }
    if ($password != $confirmPassword) {
        array_push($invalid, 'Password does not match!');
    } else {
        $addAdmin = $userFacade->addAdmin($userType, $firstName, $lastName, $email, $username, $password);
        if ($addAdmin) {
            header("Location: admin.php?msg=Admin has been added successfully!");
        }
    }
}

?>

<style>
    .valid {
        color: green;
    }

    .invalid {
        color: red;
    }
</style>

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
        <li class="nav-item active">
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
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Add Admin</h6>
                            </div>
                            <form action="add-admin.php" method="post">
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="firstName" name="first_name" placeholder="First Name" required="" autocomplete="off" />
                                        <label for="firstName">First Name</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Last Name" required="" autocomplete="off" />
                                        <label for="lastName">Last Name</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="" autocomplete="off" />
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required="" autocomplete="off" />
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm Password">
                                        <label for="password">Confirm Password</label>
                                    </div>
                                    <div id="passwordRequirements" class="my-3" style="display: none;">
                                        <h6>Password Requirements:</h6>
                                        <ul>
                                            <li id="length" class="invalid">At least 12 characters long</li>
                                            <li id="number" class="invalid">At least 1 number</li>
                                            <li id="lower" class="invalid">At least 1 lowercase letter</li>
                                            <li id="upper" class="invalid">At least 1 uppercase letter</li>
                                            <li id="emailCheck" class="invalid">Not your email</li>
                                        </ul>
                                    </div>
                                    <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="add_admin">Add Admin</button>
                                </div>
                            </form>
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

<script>
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('username'); // Assuming the username is used as email
    const requirements = {
        length: document.getElementById('length'),
        number: document.getElementById('number'),
        lower: document.getElementById('lower'),
        upper: document.getElementById('upper'),
        emailCheck: document.getElementById('emailCheck'),
    };

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const email = emailInput.value;

        // Show the requirements section when typing in the password
        document.getElementById('passwordRequirements').style.display = 'block';

        // Length requirement
        requirements.length.classList.toggle('valid', password.length >= 12);
        requirements.length.classList.toggle('invalid', password.length < 12);

        // Number requirement
        requirements.number.classList.toggle('valid', /[0-9]/.test(password));
        requirements.number.classList.toggle('invalid', !/[0-9]/.test(password));

        // Lowercase letter requirement
        requirements.lower.classList.toggle('valid', /[a-z]/.test(password));
        requirements.lower.classList.toggle('invalid', !/[a-z]/.test(password));

        // Uppercase letter requirement
        requirements.upper.classList.toggle('valid', /[A-Z]/.test(password));
        requirements.upper.classList.toggle('invalid', !/[A-Z]/.test(password));

        // Email check requirement
        requirements.emailCheck.classList.toggle('valid', password !== email);
        requirements.emailCheck.classList.toggle('invalid', password === email);
    });
</script>