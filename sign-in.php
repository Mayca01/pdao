<?php

// Include the header file for the layout
include realpath(__DIR__ . '/app/layout/header.php');

// Check if a 'success_sign_up' parameter is present in the URL
if (isset($_GET["success_sign_up"])) {
    // Set a success message for account creation
    $successSignUp = 'Your account has been successfully created. You can now sign in.';

    // If there is a success message, add it to the info array
    if ($successSignUp) {
        array_push($info, $successSignUp);
    }
}

// Check if the form has been submitted with the 'sign_in' button
if (isset($_POST["sign_in"])) {
    // Retrieve username and password from POST data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate that the username is not empty
    if (empty($username)) {
        array_push($invalid, 'Username should not be empty!');
    }

    // Validate that the password is not empty
    if (empty($password)) {
        array_push($invalid, 'Password should not be empty!');
    } else {
        // Verify the username and password using the userFacade
        $verifyUsernameAndPassword = $userFacade->verifyUsernameAndPassword($username, $password);

        // Attempt to sign in the user
        $signIn = $userFacade->signIn($username, $password);

        // Check if the username and password verification was successful
        if ($verifyUsernameAndPassword > 0) {
            // Fetch the user details from the sign-in query result
            while ($row = $signIn->fetch(PDO::FETCH_ASSOC)) {
                // Check user type and redirect accordingly
                if ($row['user_type'] == 1) {
                    // Redirect to admin dashboard
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    header('Location: admin/index.php');
                } else {
                    // Redirect to regular user dashboard
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];

                    $userId = $row["id"];
                    //header('Location: client/index.php');
                    /*$verifyEquipmentByUserId = $equipmentsFacade->verifyEquipmentByUserId($userId);
                    $fetchPWDById = $userFacade->fetchPWDById($userId);
                    if ($verifyEquipmentByUserId == 0) {
                        foreach ($fetchPWDById as $user) {
                            $disability = $user["disability"];
                            if ($disability == 'Hearing Impairment (Left)') {
                                $equipments = 'Hearing Aids';
                            
                            } else if ($disability == 'Hearing Impairment (Right)') {
                                $equipments = 'Hearing Aids';
                            
                            } elseif ($disability == 'Blindness'){
                                $equipments = 'Cane';

                            } elseif ($disability == 'Paralysis') {
                                $equipments = 'Wheelchairs';
                            
                            } elseif ($disability == 'Stroke') {
                                $equipments = 'Wheelchairs';
                            
                            } elseif ($disability == 'Amputation (Both Feet)') {
                                $equipments = 'Wheelchairs, ';

                            } elseif ($disability == 'Amputation (Right/Left)') {
                                $equipments = 'Wheelchairs, Crutches, Walkers';
                            
                            } elseif ($disability == 'Cerebral Palsy') {
                                $equipments = 'Wheelchairs';
                            }
                        }*/

                        //INSERT TO EQUIPMENTS TABLE
                        //$assignDefaultEquipment = $equipmentsFacade->assignDefaultEquipment($userId);
                        //IF ($assignDefaultEquipment) {
                            HEADER('LOCATION: CLIENT/INDEX.PHP');
                        //}
                   /* } else {
                        header('Location: client/index.php');
                    }*/
                }
            }
        } else {
            // Add an error message if username or password is incorrect
            array_push($invalid, "Incorrect username or password!");
        }
    }
}

?>

<style>
    html,
    body {
        background-image: url("./public/img/hero-bg.jpg");
        background-size: cover;
        background-position: center;
        height: 100%;
    }

    .form {
        display: flex;
        align-items: center;
        height: 100vh;
    }
</style>

<main class="form">
    <div class="container">
        <h1 class="display-5 mb-3 fw-normal text-light text-center">Persons with Disabilites Affairs Office (PDAO) Information System</h1>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="card" style="background-color: #f7f7f;">
                    <div class="card-body">
                        <h1 class="h3 my-4 fw-normal text-center">Sign In</h1>
                        <?php include("errors.php") ?>
                        <form action="sign-in.php" method="post">
                            <div class="form-floating mb-2">
                            <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Username">
                            <label for="username">Username</label>
                            </div>
                            <div class="form-floating">
                            <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password">
                            <label for="password">Password</label>
                            </div>
                            <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="sign_in">Sign In</button>
                            <p class="text-center m-0">Don't have an account? <a href="sign-up.php" class="text-decoration-none">Sign Up</a></p>
                            <p class="text-center"><a href="forgot-password.php" class="text-decoration-none">Forgot Password</a></p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</main>

<?php include realpath(__DIR__ . '/app/layout/footer.php') ?>