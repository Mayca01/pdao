<?php

include realpath(__DIR__ . '/app/layout/header.php'); // Include the header layout

// Initialize an array to hold validation errors
$invalid = [];

// Check if the form was submitted
if (isset($_POST["sign_up"])) {
    // Retrieve form data
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $age = $_POST["age"];
    $barangay = $_POST["barangay"];
    $address = $_POST["address"];
    $occupation = $_POST["occupation"];
    $contactPerson = $_POST["contact_person"];
    $contactNumber = $_POST["contact_number"];
    $email = $_POST["email"];

    // Initialize $disability with a default value if not set
    $disability = isset($_POST["disability"]) ? $_POST["disability"] : [];
    $otherDisability = isset($_POST["otherDisability"]) ? $_POST["otherDisability"] : [];

    $finalDisability = [];//($disability === "others" && !empty($otherDisability)) ? $otherDisability : $disability;
    foreach ($disability as $key => $disabilityValue) {
        if ($disabilityValue === "others" && isset($otherDisability[$key]) && !empty($otherDisability[$key])) {
            $finalDisabilities[] = $otherDisability[$key];  // Use the custom disability if 'others' was selected
        } else {
            $finalDisabilities[] = $disabilityValue;  // Use the selected disability type
        }
    }
    
    $mergedDisabilities = array_filter(array_merge($finalDisabilities, $otherDisability));

    if (isset($_POST['otherDisability'])) {
        // Convert the filtered array to a comma-separated string
        $finalDisability = implode(', ', $mergedDisabilities);
    } else {
        $finalDisability = implode(', ', $finalDisabilities);
    }

    // Handle file upload
    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxFileCount = 10;
    $minFileCount = 1;

    // Array to store the file names
    $uploadedFiles = [];

    // Validate and process file uploads
    if (isset($_FILES["medical_information"]) && !empty($_FILES["medical_information"]["name"][0])) {
        $fileCount = count($_FILES['medical_information']['name']);

        // Validate the number of files
        if ($fileCount < $minFileCount || $fileCount > $maxFileCount) {
            array_push($invalid, "You must upload between {$minFileCount} and {$maxFileCount} files!");
        } else {
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = $_FILES["medical_information"]["name"][$i];
                $fileTmpName = $_FILES["medical_information"]["tmp_name"][$i];
                $fileSize = $_FILES["medical_information"]["size"][$i];
                $fileError = $_FILES["medical_information"]["error"][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Validate file
                if ($fileError === 0) {
                    if (!in_array($fileExt, $allowedExtensions)) {
                        array_push($invalid, "File type {$fileExt} is not supported!");
                    } elseif ($fileSize > 5000000) { // 5MB limit
                        array_push($invalid, "File {$fileName} exceeds 5MB!");
                    } else {
                        // Generate unique file name
                        $uniqueFileName = uniqid('Medcert_', true) . '.' . $fileExt;
                        $fileDestination = './public/img/medical-informations/' . $uniqueFileName;

                        // Move file to the destination and add filename to the list
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            $uploadedFiles[] = $uniqueFileName; // Collect only the filenames
                        } else {
                            array_push($invalid, "Failed to upload file {$fileName}!");
                        }
                    }
                } else {
                    array_push($invalid, "Error uploading file {$fileName}!");
                }
            }
        }
    } else {
        array_push($invalid, "No files uploaded.");
    }

    $username = $_POST["username"];
    $password = trim($_POST["password"]); // Remove whitespace
    $passwordLength = strlen($password);
    $confirmPassword = $_POST["confirm_password"];

    $status = 'Active';

    // Validate form data
    if (empty($firstName)) array_push($invalid, 'First Name should not be empty!');
    if (empty($lastName)) array_push($invalid, 'Last Name should not be empty!');
    if (empty($age)) array_push($invalid, 'Age should not be empty!');
    if ($barangay == 'None') array_push($invalid, 'Barangay should not be empty!');
    if (empty($address)) array_push($invalid, 'Address should not be empty!');
    if (empty($occupation)) array_push($invalid, 'Occupation should not be empty!');
    if (empty($contactPerson)) array_push($invalid, 'Contact Person should not be empty!');
    if (empty($contactNumber)) array_push($invalid, 'Contact Number should not be empty!');
    if (empty($email)) array_push($invalid, 'Email should not be empty!');
    if ($disability === 'None') array_push($invalid, 'Type of Disability should not be empty!');
    if (empty($username)) array_push($invalid, 'Username should not be empty!');
    if (empty($password)) array_push($invalid, 'Password should not be empty!');
    if (empty($confirmPassword)) array_push($invalid, 'Confirm Password should not be empty!');
    if ($passwordLength < 8 || $passwordLength > 15) array_push($invalid, 'Password must be between 8 and 15 characters long!');
    if ($password != $confirmPassword) array_push($invalid, 'Password does not match!');

    // Save to database if no errors
    if (empty($invalid)) {
        $medicalInformation = implode(', ', $uploadedFiles); // Save filenames as a comma-separated string
        $signUp = $userFacade->signUp(
            $firstName, $lastName, $age, $barangay, $address, $occupation,
            $contactPerson, $contactNumber, $email, $finalDisability,
            $medicalInformation, $username, $password, $status
        );

        if ($signUp) {
            header("Location: sign-in.php?success_sign_up=1");
            exit();
        } else {
            array_push($invalid, "Failed to sign up! Please try again.");
        }
    }
}

?>

<style>
    html,
    body {
        background-image: linear-gradient(to left, #95c759, #315a39);
        background-size: cover;
        background-position: center;
        height: 100vh;
    }

    .form {
        display: flex;
        align-items: center;
    }

    .valid {
        color: green;
    }

    .invalid {
        color: red;
    }

    .is-invalid {
        border-color: red;
    }

</style>

<main class="form">
    <div class="container py-5">
        <h1 class="display-5 mb-5 fw-normal text-light text-center">Sign Up</h1>
        <?php include("errors.php") ?>
        <div class="card">
            <form action="sign-up.php" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="progress my-3">
                                <div class="progress-bar" role="progressbar" style="width: 33%;" id="progressBar"></div>
                            </div>
                            <div class="p-info">
                                <div class="card">
                                    <div class="card-body">
                                        <h1 class="h3 my-4 fw-normal text-center">Personal Information</h1>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="firstname" name="first_name" placeholder="First Name" autocomplete="off" />
                                                    <label for="firstname">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="lastname" name="last_name" placeholder="Last Name" autocomplete="off" />
                                                    <label for="lastname">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="number" class="form-control form-control-sm" id="age" name="age" placeholder="Age" autocomplete="off" />
                                                    <label for="age">Age</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                      <select class="form-select py-3" name="barangay">
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
                                                    <label for="barangay">Barangay</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Address" autocomplete="off" />
                                                    <label for="address">Address</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="occupation" name="occupation" placeholder="Occupation" autocomplete="off" />
                                                    <label for="occupation">Occupation</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="contactPerson" name="contact_person" placeholder="Contact Person" autocomplete="off" />
                                                    <label for="contactPerson">Contact Person</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="contactNumber" name="contact_number" placeholder="Contact Number" autocomplete="off" />
                                                    <label for="contactNumber">Contact Number</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-floating mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email" autocomplete="off" />
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="btn btn-primary btn-md" id="next-btn">
                                                Next<span class="fas fa-fw fa-arrow-right"><span>
                                            </button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disability Information -->
                            <div class="d-info" style="display:none;">
                                <div class="card my-3">
                                    <div class="card-body">
                                        <h1 class="h3 my-4 fw-normal text-center">Disability Information</h1>
                                        <div id="disabilityContainer">
                                            <div class="form-floating mb-2">
                                                <select class="form-select disability-field" id="disability" name="disability[]">
                                                    <option value="">--- Select a Disability ---</option>
                                                    <option value="Hearing Impairment (Left)"> Hearing Impairment (Left)</option>
                                                    <option value="Hearing Impairment (Right)"> Hearing Impairment (Right)</option>
                                                    <option value="Blindness"> Blindness</option>
                                                    <option value="Paralysis"> Paralysis</option>
                                                    <option value="Stroke"> Stroke</option>
                                                    <option value="Amputation (Both Feet)"> Amputation (Both Feet)</option>
                                                    <option value="Amputation (Right/Left)"> Amputation (Right/Left)</option>
                                                    <option value="Cerebral Palsy"> Cerebral Palsy</option>
                                                    <option value="others"> Others</option>
                                                    option
                                                    <!-- Add other disability options here -->
                                                </select>
                                                <label for="disability">Type of Disability</label>
                                            </div>
                                            <div id="otherDisabilityField" class="form-floating mb-2" style="display: none;">
                                                <input type="text" class="form-control" id="otherDisability" name="otherDisability" placeholder="Specify your disability" autocomplete="off">
                                                <label for="otherDisability">Please Specify</label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm" id="addDisabilityButton">Add Another Disability</button>
                                        <div class="my-3">
                                            <label for="medicalInformation" class="form-label">Medical Information (Upload 5 to 10 files in PDF, JPG, JPEG, or PNG format)</label>
                                            <input type="file" class="form-control" id="medicalInformation" name="medical_information[]" multiple accept="image/*,.pdf" />
                                            <ul id="fileList" class="mt-2"></ul> <!-- Container for displaying file names -->
                                            <small id="fileRequirements" class="text-danger" style="display:none;">Please upload between 5 and 10 valid files (PDF, JPG, JPEG, or PNG).</small>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="btn btn-danger btn-sm" id="d-info-prev-btn"> 
                                               <span class="fas fa-fw fa-arrow-left"></span> Previous
                                            </button>
                                            <button class="btn btn-primary btn-sm" id="d-info-next-btn"> 
                                               <span class="fas fa-fw fa-arrow-right"></span> Next
                                            </button>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                            
                            <!-- Account Information -->
                            <div class="a-info" style="display:none;">
                                <div class="card my-3">
                                    <div class="card-body">
                                        <h1 class="h3 my-4 fw-normal text-center">Account Information</h1>
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Username">
                                            <label for="username">Username</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password">
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="password" class="form-control form-control-sm" id="confirmPassword" name="confirm_password" placeholder="Confirm Password">
                                            <label for="confirmPassword">Confirm Password</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="btn btn-md btn-danger" id="a-info-prev-btn">
                                               <span class="fas fa-fw fa-arrow-left"></span> Previous
                                            </button>
                                            <button class="btn btn-md btn-primary" type="submit" name="sign_up">
                                               <span class="fas fa-fw fa-check"></span> Sign Up
                                            </button>
                                        </div>
                                    </div>
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
                            </div>
                            <p class="text-center mt-2">Already had an account? <a href="sign-in.php" class="text-decoration-none">Sign In</a></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include realpath(__DIR__ . '/app/layout/footer.php') ?>

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


    function validatePersonalInfo() {
    let valid = true;
    ['#firstname', '#lastname', '#age', '#email', '#address', '#occupation', '#contactPerson', '#contactNumber'].forEach(id => {
        if (!$(id).val()) {
            valid = false;
            $(id).addClass('is-invalid');
        } else {
            $(id).removeClass('is-invalid');
        }
    });

    if ($('#barangay').val() === "") {
        valid = false;
        $('#barangay').addClass('is-invalid');
    } else {
        $('#barangay').removeClass('is-invalid');
    }
    return valid;
}

function validateDisabilityInfo() {
    const disability = $('#disability').val();
    if (disability === "None") {
        $('#disability').addClass('is-invalid');
        return false;
    }
    $('#disability').removeClass('is-invalid');
    return true;
}

function updateProgressBar(step) {
    const progress = [33, 66, 100];
    if ($('#progressBar').length) {
        $('#progressBar').css('width', progress[step - 1] + '%').attr('aria-valuenow', progress[step - 1]);
    }
}

$(document).on("click", "#next-btn", function (e) {
    e.preventDefault();
    if (validatePersonalInfo()) {
        $('.d-info').fadeIn();
        $('.p-info').hide();
        updateProgressBar(2);
    } else {
        alert("Please fill in all required fields!");
    }
});

$(document).on("click", "#d-info-next-btn", function (e) {
    e.preventDefault();
    if (validateDisabilityInfo()) {
        $('.a-info').fadeIn();
        $('.d-info').hide();
        updateProgressBar(3);
    } else {
        alert("Please select a disability type.");
    }
});

$(document).on("click", "#d-info-prev-btn", function (e) {
    e.preventDefault();
    $('.p-info').fadeIn();
    $('.d-info').hide();
    updateProgressBar(1);
});

$(document).on("click", "#a-info-prev-btn", function (e) {
    e.preventDefault();
    $('.d-info').fadeIn();
    $('.a-info').hide();
    updateProgressBar(2);
});

$('#disability').on('change', function () {
    const selectedValue = $(this).val();
    if (selectedValue === 'others') {
        // Show the "Other Disability" input field
        $('#otherDisabilityField').fadeIn();
    } else {
        // Hide the "Other Disability" input field and clear its value
        $('#otherDisabilityField').fadeOut();
        $('#otherDisability').val('');
    }
});

function validateDisabilityInfo() {
    const disability = $('#disability').val();
    const otherDisability = $('#otherDisability').val();

    if (disability === "") {
        $('#disability').addClass('is-invalid');
        return false;
    } else {
        $('#disability').removeClass('is-invalid');
    }

    // If "Others" is selected, ensure the input is filled
    if (disability === "others" && otherDisability.trim() === "") {
        $('#otherDisability').addClass('is-invalid');
        return false;
    } else {
        $('#otherDisability').removeClass('is-invalid');
    }

    return true;
}

// Validate file upload and display file names
$(document).on('change', '#medicalInformation', function (e) {
    e.preventDefault();

    const files = $(this)[0].files;
    const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
    const fileListContainer = $('#fileList'); // The container for displaying file names
    const tempFiles = []; // Temporary storage for valid files
    let validFiles = true;

    fileListContainer.empty(); // Clear the previous file list

    if (files.length < 1 || files.length > 10) {
        validFiles = false;
        $('#fileRequirements').fadeIn();
    } else {
        $('#fileRequirements').fadeOut();

        Array.from(files).forEach((file, index) => {
            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                validFiles = false;
                $('#fileRequirements').fadeIn();
            } else {
                tempFiles.push(file);

                // Add valid file names with remove buttons to the list
                fileListContainer.append(`
                    <li id="file-${index}">
                        ${file.name} 
                        <button type="button" class="btn btn-danger btn-sm remove-file" data-index="${index}">
                            <span class="fas fa-fw fa-trash"></span>
                        </button>
                    </li>
                `);
            }
        });
    }

    if (!validFiles) {
        $(this).val(''); // Clear invalid files
        fileListContainer.empty(); // Clear the file list on validation failure
    }

    // Store the valid files in a global variable for submission
    window.validFiles = tempFiles;
});

// Remove file from the list
$(document).on('click', '.remove-file', function () {
    const indexToRemove = $(this).data('index');
    window.validFiles.splice(indexToRemove, 1); // Remove file from global array

    // Rebuild the input file element and the list
    const dataTransfer = new DataTransfer();
    window.validFiles.forEach(file => dataTransfer.items.add(file));
    $('#medicalInformation')[0].files = dataTransfer.files;

    $(this).parent().remove();
});

/*$(document).ready(function () {
        // Get the container for the disability fields
        const disabilityContainer = $('#disabilityContainer');
        const addDisabilityButton = $('#addDisabilityButton');
        
        addDisabilityButton.on('click', function () {
            const newDisabilityField = $('<div class="mb-2"></div>');
            newDisabilityField.html(`
                <select class="form-select disability-field" name="disability[]">
                    <option value="">--- Select a Disability ---</option>
                    <option value="Hearing Impairment (Left)">Hearing Impairment (Left)</option>
                    <option value="Hearing Impairment (Right)">Hearing Impairment (Right)</option>
                    <option value="Blindness">Blindness</option>
                    <option value="Paralysis">Paralysis</option>
                    <option value="Stroke">Stroke</option>
                    <option value="Amputation (Both Feet)">Amputation (Both Feet)</option>
                    <option value="Amputation (Right/Left)">Amputation (Right/Left)</option>
                    <option value="Cerebral Palsy">Cerebral Palsy</option>
                    <option value="others">Others</option>
                </select>
                <div class="form-floating mb-2 otherDisabilityField" style="display: none;">
                    <input type="text" class="form-control" name="otherDisability" id="otherDisability" placeholder="Specify your disability" autocomplete="off">
                    <label>Please Specify</label>
                </div>
            `);
            disabilityContainer.append(newDisabilityField);

            // Add event listener to show/hide "Others" field for each newly added disability field
            const newDisabilitySelect = newDisabilityField.find('.disability-field');
            newDisabilitySelect.on('change', function () {
                const otherDisabilityField = newDisabilityField.find('.otherDisabilityField');
                if (newDisabilitySelect.val() === 'others') {
                    otherDisabilityField.show();
                } else {
                    otherDisabilityField.hide();
                }
            });
        });
    });*/
    $(document).ready(function () {
    // Get the container for the disability fields
    const disabilityContainer = $('#disabilityContainer');
    const addDisabilityButton = $('#addDisabilityButton');
    
    // Function to add a new disability field
    addDisabilityButton.on('click', function () {
        const newDisabilityField = $('<div class="mb-2"></div>');
        newDisabilityField.html(`
            <select class="form-select disability-field" name="disability[]">
                <option value="">--- Select a Disability ---</option>
                <option value="Hearing Impairment (Left)">Hearing Impairment (Left)</option>
                <option value="Hearing Impairment (Right)">Hearing Impairment (Right)</option>
                <option value="Blindness">Blindness</option>
                <option value="Paralysis">Paralysis</option>
                <option value="Stroke">Stroke</option>
                <option value="Amputation (Both Feet)">Amputation (Both Feet)</option>
                <option value="Amputation (Right/Left)">Amputation (Right/Left)</option>
                <option value="Cerebral Palsy">Cerebral Palsy</option>
                <option value="others">Others</option>
            </select>
            <div class="form-floating mb-2 otherDisabilityField" style="display: none;">
                <input type="text" class="form-control" name="otherDisability[]" placeholder="Specify your disability" autocomplete="off">
                <label>Please Specify</label>
            </div>
        `);
        disabilityContainer.append(newDisabilityField);

        // Add event listener to show/hide "Others" field for each newly added disability field
        const newDisabilitySelect = newDisabilityField.find('.disability-field');
        const otherDisabilityField = newDisabilityField.find('.otherDisabilityField');
        
        newDisabilitySelect.on('change', function () {
            if (newDisabilitySelect.val() === 'others') {
                otherDisabilityField.show();
                otherDisabilityField.find('input').attr('required', true); // Make the "Specify" field required
            } else {
                otherDisabilityField.hide();
                otherDisabilityField.find('input').val(''); // Clear the "Specify" field
                otherDisabilityField.find('input').attr('required', false); // Remove required attribute
            }
        });
    });

    // Before form submission, remove "others" values from the disability fields
    $('form').on('submit', function () {
        $('.disability-field').each(function () {
            if ($(this).val() === 'others') {
                $(this).val(''); // Remove "others" from being posted
            }
        });
    });
});

</script>