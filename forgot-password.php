<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the header file for the layout
include realpath(__DIR__ . '/app/layout/header.php');

// Include Composer's autoloader
require 'vendor/autoload.php';

//Send reset password link
function sendPasswordResetLink($email, $resetToken) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'makoto.romtevs22@gmail.com';
        $mail->Password   = 'gujq zhbw lxhf tvxn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('makoto.romtevs22@gmail.com', 'PDAO Information System');
        $mail->addAddress($email);

        // Email content
        // Determine the base URL dynamically
        $localIp = getHostByName(getHostName()); // Get the local IP address
        $resetLink = "http://$localIp/pdao/reset-password.php?token=" . urlencode($resetToken);
        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
            <h3>Password Reset Request</h3>
            <p>Click the link below to reset your password:</p>
            <a href='$resetLink'>Reset Password</a>
            <p>If you did not request this, please ignore this email.</p>
        ";
        $mail->AltBody = "Click this link to reset your password: $resetLink";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle the Forgot Password Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $user = $userFacade->getUserByEmail($email);
    if ($user) {
        // Generate a unique reset token and save it in the database
        $resetToken = bin2hex(random_bytes(16)); // Generate a secure token
        $userFacade->saveResetToken($email, $resetToken);

        // Send the password reset link
        $sendResult = sendPasswordResetLink($email, $resetToken);
        if ($sendResult === true) {
            $msg = "Password reset email has been sent to your email address.";
            header("location: forgot-password.php?msg=".$msg."&type=success");
            exit;
        } else {
            header("location: forgot-password.php?msg=". urlencode($sendResult));
            exit;
        }
    } else {
        header("Location: forgot-password.php?msg=Email address not found");
        exit;
    }
}

//Fetch message response
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;
$msgType = isset($_GET['type']) ? $_GET['type'] : null;


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
                    $verifyEquipmentByUserId = $equipmentsFacade->verifyEquipmentByUserId($userId);
                    $fetchPWDById = $userFacade->fetchPWDById($userId);
                    if ($verifyEquipmentByUserId == 0) {
                        foreach ($fetchPWDById as $user) {
                            $disability = $user["disability"];
                            if ($disability == 'Hearing Impairment') {
                                $equipments = 'Hearing Aids';
                            } elseif ($disability == 'Intellectual Disability') {
                                $equipments = 'Communication Tools';
                            } elseif ($disability == 'Cerebral Palsy') {
                                $equipments = 'Wheelchairs';
                            } elseif ($disability == 'Speech Disorder') {
                                $equipments = 'Communication Boards';
                            } elseif ($disability == 'Autism') {
                                $equipments = 'Therapeutic Equipment';
                            } elseif ($disability == 'Traumatic Brain Injury') {
                                $equipments = 'Wheelchairs';
                            } elseif ($disability == 'Blindness') {
                                $equipments = 'White Cane';
                            } elseif ($disability == 'Physical Disability') {
                                $equipments = 'Wheelchairs';
                            } elseif ($disability == 'Vision Impairment') {
                                $equipments = 'White Cane';
                            } elseif ($disability == 'Handicap') {
                                $equipments = 'Prosthetic Limbs';
                            } elseif ($disability == 'Mental Illness') {
                                $equipments = 'Journals and Planners';
                            } elseif ($disability == 'Multiple Sclerosis') {
                                $equipments = 'Therapeutic Equipment';
                            } elseif ($disability == 'Epilepsy') {
                                $equipments = 'Bed Safety Rails';
                            } elseif ($disability == 'Mobility Impairments') {
                                $equipments = 'Wheelchairs, Canes, Crutches';
                            } elseif ($disability == 'Muscular Dystrophy') {
                                $equipments = 'Wheelchairs, Walkers';
                            } elseif ($disability == 'Neurological Disorder') {
                                $equipments = 'Wheelchairs, Canes, Walkers';
                            } elseif ($disability == 'Orthopedic Impairment') {
                                $equipments = 'Wheelchairs, Canes, Walkers';
                            } elseif ($disability == 'Spinal Cord Injury') {
                                $equipments = 'Wheelchairs, Walkers';
                            } elseif ($disability == 'Tourette Syndrome') {
                                $equipments = 'Therapeutic Aids';
                            } elseif ($disability == 'Arthritis') {
                                $equipments = 'Canes, Walkers';
                            } elseif ($disability == 'Developmental Disability') {
                                $equipments = 'Wheelchairs, Walkers';
                            } elseif ($disability == 'Dwarfism') {
                                $equipments = 'Wheelchairs, Walkers';
                            }
                        }

                        // Insert to equipments table
                        $assignEquipment = $equipmentsFacade->assignEquipment($userId, $equipments);
                        if ($assignEquipment) {
                            header('Location: client/index.php');
                        }
                    } else {
                        header('Location: client/index.php');
                    }
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
                <div class="card">
                    <div class="card-body">
                        <h1 class="h3 my-4 fw-normal text-center">Forgot Password</h1>
                        <?php include("errors.php") ?>
                        <?php if ($msg): ?>
                            <div class="alert alert-<?php echo $msgType === 'success' ? 'success' : 'danger'; ?>">
                                <?php echo htmlspecialchars($msg); ?>
                            </div>
                            <a href="forgot-password.php" class="w-100 btn btn-lg btn-primary my-3"> 
                                <span class="fas fa-fw fa-arrow-left"></span> Return
                            </a>
                        <?php else: ?>
                            <form action="forgot-password.php" method="post">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" required />
                                    <label for="email">Email</label>
                                </div>
                                <button class="w-100 btn btn-lg btn-primary my-3" type="submit">Submit</button>
                                <p class="text-center">Already had an account? <a href="sign-in.php" class="text-decoration-none">Sign In</a></p>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</main>

<?php include realpath(__DIR__ . '/app/layout/footer.php') ?>