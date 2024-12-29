<?php
include realpath(__DIR__ . '/app/layout/header.php');

// Handle POST request to reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_POST['new_password'], $_POST['confirm_password'])) {
    $token = $_POST['token'];
    $newPassword = htmlspecialchars($_POST['new_password']);
    $confirmPassword = htmlspecialchars($_POST['confirm_password']);

    if (strlen($newPassword) < 8 || strlen($newPassword) > 15) {
        array_push($invalid, 'Password must be between 8 and 15 characters long!');
    }

    if ($newPassword == $confirmPassword) {
        $pw = trim($confirmPassword); // Secure password hash

        // Verify the token and update the password
        $email = $userFacade->getEmailByToken($token);
        foreach($email as $row) {
            $email = $row['email'];
        }
        if ($email) {
            $updPw = $userFacade->updatePassword($email, $pw); // Update password in DB
            //$userFacade->invalidateToken($token); // Invalidate the token
            if ($updPw) {
                $msg = "Password reset successfully.";
                header("location: reset-password.php?token=".$token."&res_msg=" . $msg . "&type=success");
                exit;
            }
        } else {
            $msg = "Invalid or expired token.";
            header("location: reset-password.php?token=".$token."&res_msg=" . $msg . "&type=error");
            exit;
        }
    } else {
        $msg = "New password and Confirm password did not match.";
        header("location: reset-password.php?token=".$token."&res_msg=" . $msg . "&type=error");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invalidateTokenBtn'])) {
    $token = htmlspecialchars($_POST['token']);
    $invalidate = $userFacade->invalidateToken($token);
    if ($invalidate) {
        header("location: sign-in.php");
    }
}

// Handle GET request to fetch messages
$msg = isset($_GET['res_msg']) ? htmlspecialchars($_GET['res_msg']) : null;
$msgType = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;
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
    .valid {
        color: green;
    }
    .invalid {
        color: red;
    }
</style>

<main class="form">
    <div class="container">
        <h1 class="display-5 mb-3 fw-normal text-light text-center">
            Persons with Disabilities Affairs Office (PDAO) Information System
        </h1>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <?php include("errors.php") ?>
                        <h1 class="h3 my-4 fw-normal text-center">Reset Password</h1>
                        <?php 
                            // Display the reset password form if a valid token is present
                            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
                                $token = $_GET['token'];

                                // Verify the token
                                $email = $userFacade->getEmailByToken($token);
                                
                                if ($email) {
                        ?>
                            <?php if ($msg): ?>
                                <div class="alert alert-<?php echo $msgType === 'success' ? 'success' : 'danger'; ?>">
                                    <?php echo $msg; ?>
                                </div>
                                <?php if ($msgType === 'error'): ?>
                                    <a href="reset-password.php?token=<?php echo $token; ?>" class="w-100 btn btn-lg btn-primary my-3">
                                        <span class="fas fa-fw fa-arrow-left"></span> Return
                                    </a>
                                <?php else: ?>
                                    <!-- <a href="sign-in.php" class="w-100 btn btn-lg btn-primary my-3">
                                        <span class="fas fa-fw fa-arrow-left"></span> Return Sign-in
                                    </a> -->
                                    <form action="reset-password.php?token=<?php echo $token; ?>" method="post">
                                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                        <button type="submit" class="w-100 btn btn-lg btn-primary my-3" name="invalidateTokenBtn">
                                            <span class="fas fa-fw fa-arrow-left"></span> Return Sign-in
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <form action="reset-password.php?token=<?php echo $token; ?>" method="post">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
                                    <div class="form-floating mb-2">
                                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" autocomplete="off" required />
                                        <label for="new_password">New Password</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" autocomplete="off" required />
                                        <label for="confirm_password">Confirm Password</label>
                                    </div>
                                    <button type="submit" class="w-100 btn btn-lg btn-primary my-3">Reset Password</button>
                                    <div class="card card-body">
                                        <div id="passwordRequirements" class="my-3" style="display: none;">
                                            <h6>Password Requirements:</h6>
                                            <ul>
                                                <li id="length" class="invalid">At least 12 characters long</li>
                                                <li id="number" class="invalid">At least 1 number</li>
                                                <li id="lower" class="invalid">At least 1 lowercase letter</li>
                                                <li id="upper" class="invalid">At least 1 uppercase letter</li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php } else { ?> 
                                <div class='alert alert-danger text-center'>Invalid or expired token.</div>
                                <a href="sign-in.php" class="w-100 btn btn-lg btn-primary my-3">
                                    <span class="fas fa-fw fa-arrow-left"></span> Return Sign In
                                </a>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</main>

<?php include realpath(__DIR__ . '/app/layout/footer.php') ?>
<script type="text/javascript">
    const passwordInput = document.getElementById('new_password');
    const requirements = {
        length: document.getElementById('length'),
        number: document.getElementById('number'),
        lower: document.getElementById('lower'),
        upper: document.getElementById('upper'),
    };

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;

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

    });
</script>
