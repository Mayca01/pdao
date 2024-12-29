<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

$userId = "";
$firstName = "";
$lastName = "";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (isset($_SESSION['first_name'])) {
    $firstName = $_SESSION['first_name'];
}

if (isset($_SESSION['last_name'])) {
    $lastName = $_SESSION['last_name'];
}

if (isset($_GET['msg'])) {
    array_push($success, $_GET['msg']);
}

$assistanceByIdData = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $fetchAssistanceById = $assistanceFacade->fetchAssistanceDataById($id);
    if ($fetchAssistanceById) {
        $assistanceByIdData = $fetchAssistanceById;
    }
 }

$uploadDir = ".././public/img/requirements/uploads/" . $firstName . "_" . $lastName . "/";

if (!is_dir($uploadDir)) {
mkdir($uploadDir, 0777, true);
}

if (isset($_POST["update_apply_assistance"])) {
    $existingFiles = $_POST['existing_files'] ?? []; // Existing files from the form
    $uploadedFiles = []; // Array to store new uploaded files

    // Process newly uploaded files
    if (isset($_FILES["requirements"]) && !empty($_FILES["requirements"]["name"][0])) {
        $totalFiles = count($_FILES["requirements"]["name"]);

        // Validate and upload files
        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['requirements']['name'][$i];
            $fileTmpName = $_FILES['requirements']['tmp_name'][$i];
            $fileType = $_FILES['requirements']['type'][$i];

            // Validate file type
            if (!in_array($fileType, ["image/jpeg", "image/png", "application/pdf"])) {
                array_push($invalid, "Invalid file type for file: $fileName. Only images and PDFs are allowed.");
                continue;
            }

            // Save file to directory
            $uniqueFileName = basename($fileName); // For unique naming, add uniqid() if needed
            $fileDest = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpName, $fileDest)) {
                $uploadedFiles[] = $uniqueFileName;
            } else {
                array_push($invalid, "Failed to upload file: $fileName.");
            }
        }
    }

    // Merge existing and newly uploaded files
    $allFiles = array_merge($existingFiles, $uploadedFiles);

    // Debugging
    error_log("Existing Files: " . print_r($existingFiles, true));
    error_log("Uploaded Files: " . print_r($uploadedFiles, true));
    error_log("Merged Files: " . print_r($allFiles, true));

    // Save to the database
    $uploadedFileNames = implode(", ", $allFiles); // Convert array to string for database
    $assistanceId = htmlspecialchars($_POST["assistance_id"]);
    $assistance = htmlspecialchars($_POST["assistance"]);
    $status = 'Pending';
    $reason = htmlspecialchars($_POST["reason"]);

    $addAssistance = $assistanceFacade->updateAssistance(
        $assistanceId,
        $uploadedFileNames
    );

    if ($addAssistance) {
        header("Location: assistance.php?msg=Assistance has been updated successfully!");
        exit;
    } else {
        array_push($invalid, "Failed to save assistance data to the database.");
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
        <li class="nav-item active">
            <a class="nav-link" href="assistance.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assistance</span>
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
                            <?php
                                $checkValidUser = $userFacade->checkValidatedUser($userId);
                                $userValidated = $checkValidUser[0]['user_validated'] ?? null;

                                if ($userValidated !== null) {

                                if ($assistanceByIdData === '') {
                                    header("Location: assistance.php");
                                }

                                foreach($assistanceByIdData as $row):
                            ?>
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Apply Assistance</h6>
                            </div>
                            <div class="card-body">
                                <form action="update-apply-assistance.php" method="post" enctype="multipart/form-data">
                                    <div class="form-floating my-3"> 
                                        <input type="text" name="assistance" class="form-control" value="<?= trim($row['assistance']) ?>" readonly="">
                                        <label for="assistance">Type of Assistance</label>
                                    </div>
                                    <div class="form-floating">
                                        <textarea name="reason" id="reason" class="form-control" style="height: auto; resize: none;" readonly=""><?= trim($row['reason']) ?>
                                        </textarea>
                                        <label for="reason"> Reason </label>
                                    </div>
                                    <div class="form-floating my-3">
                                        <input type="file" name="requirements[]" id="requirements" class="form-control" multiple accept="image/*,.pdf">
                                        <label for="requirements" style="padding-top: 10px;"> Requirements</label>
                                        <div id="file-names" class="mt-2 text-muted">
                                            <?php 
                                            if (!empty($row['uploaded_requirements'])) {
                                                $files = explode(", ", $row['uploaded_requirements']);
                                                foreach ($files as $file) {
                                                    echo "<div class='file-item d-inline-flex align-items-center'>
                                                             <span>$file</span>
                                                          </div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <small class="form-text text-muted">
                                            *** Please upload the file(s) with the name format: <strong>MedCert.pdf</strong> (e.g., Medical Certificate).
                                        </small>
                                    </div>
                                    <input type="hidden" name="assistance_id" value="<?= $row['id'] ?>">
                                    <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="update_apply_assistance">Apply Assistance</button>
                                </form>
                            </div>
                            <?php endforeach; ?>
                            <?php } else { ?>
                            <div class="card-header">
                                <span class="fas fa-fw fa-exclamation"></span> <strong> Not Validated Account</strong>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="col-sm-10">
                                        <div class="alert alert-danger"> 
                                            Sorry, You cannot request an assistance at this moment because your application has not been approved. Thank you.
                                        </div>
                                        <a href="index.php" class="btn btn-sm btn-primary"><span class="fas fa-fw fa-arrow-left"></span> Go Back</a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
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
<!-- <script type="text/javascript">
    $(document).ready(function () {
    let uploadedFiles = [];
    let existingFiles = [];

    // Initialize existing files (from PHP)
    $('#file-names .file-item').each(function () {
        const filename = $(this).find('span').text();
        if (!existingFiles.includes(filename)) {
            existingFiles.push(filename);
        }
    });

    // Handle new file uploads
    $('#requirements').on('change', function () {
        const files = Array.from(this.files);
        files.forEach(file => {
            if (!uploadedFiles.some(f => f.name === file.name)) {
                uploadedFiles.push(file); // Avoid duplicates
            }
        });
        updateFileList();
    });

    // Update the file list display
    function updateFileList() {
        // Display new uploaded files
        const newFileListHTML = uploadedFiles.map((file, index) =>
            `<div class="file-item d-inline-flex align-items-center">
                <span>${file.name}</span>
                <button type="button" class="btn btn-sm btn-danger ml-2 remove-file-btn" data-index="${index}" data-type="new">
                    <i class="fa fa-trash"></i>
                </button>
            </div>`
        ).join('');

        // Display existing files
        const existingFileListHTML = existingFiles.map((file, index) =>
            `<div class="file-item d-inline-flex align-items-center">
                <span>${file}</span>
                <button type="button" class="btn btn-sm btn-danger ml-2 remove-file-btn" data-index="${index}" data-type="existing">
                    <i class="fa fa-trash"></i>
                </button>
                <input type="hidden" name="existing_files[]" value="${file}">
            </div>`
        ).join('');

        $('#file-names').html(newFileListHTML + existingFileListHTML);

        // Add remove functionality
        $('.remove-file-btn').on('click', function () {
            const index = $(this).data('index');
            const type = $(this).data('type');
            if (type === 'new') {
                uploadedFiles.splice(index, 1);
            } else if (type === 'existing') {
                existingFiles.splice(index, 1);
            }
            updateFileList();
        });
    }

    // Handle form submission
    $('form').on('submit', function () {
        // Add hidden inputs for remaining uploaded files
        uploadedFiles.forEach(file => {
            const input = `<input type="hidden" name="requirements[]" value="${file.name}">`;
            $('#file-names').append(input);
        });
    });
});

</script> -->
<script type="text/javascript">
    $(document).ready(function () {
        let uploadedFiles = [];
        let existingFiles = [];

        // Initialize existing files (from PHP)
        $('#file-names .file-item').each(function () {
            const filename = $(this).find('span').text();
            if (!existingFiles.includes(filename)) {
                existingFiles.push(filename);
            }
        });

        // Handle new file uploads
        $('#requirements').on('change', function () {
            const files = Array.from(this.files);
            files.forEach(file => {
                if (!uploadedFiles.some(f => f.name === file.name)) {
                    uploadedFiles.push(file); // Avoid duplicates
                }
            });
            updateFileList();
        });

        // Update the file list display
        function updateFileList() {
            // Display new uploaded files with remove button
            const newFileListHTML = uploadedFiles.map((file, index) =>
                `<div class="file-item d-inline-flex align-items-center">
                    <span>${file.name}</span>
                    <button type="button" class="btn btn-sm btn-danger ml-2 remove-file-btn" data-index="${index}" data-type="new">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`
            ).join('');

            // Display existing files without remove button
            const existingFileListHTML = existingFiles.map((file, index) =>
                `<div class="file-item d-inline-flex align-items-center">
                    <span>${file}</span>
                    <input type="hidden" name="existing_files[]" value="${file}">
                </div>`
            ).join('');

            $('#file-names').html(newFileListHTML + existingFileListHTML);

            // Add remove functionality for new files only
            $('.remove-file-btn').on('click', function () {
                const index = $(this).data('index');
                const type = $(this).data('type');
                if (type === 'new') {
                    uploadedFiles.splice(index, 1);
                } else if (type === 'existing') {
                    existingFiles.splice(index, 1);
                }
                updateFileList();
            });
        }

        // Handle form submission
        $('form').on('submit', function () {
            // Add hidden inputs for remaining uploaded files
            uploadedFiles.forEach(file => {
                const input = `<input type="hidden" name="requirements[]" value="${file.name}">`;
                $('#file-names').append(input);
            });
        });
    });
</script>
