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

$uploadDir = ".././public/img/requirements/uploads/" . $firstName . "_" . $lastName . "/";

// Ensure upload directory exists
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        array_push($invalid, "Failed to create upload directory.");
    }
}

if (isset($_POST["apply_assistance"])) {
    $pwdId = htmlspecialchars($_POST["pwd_id"]);
    $assistance = htmlspecialchars($_POST["assistance"]);
    $status = 'Pending';
    $reason = htmlspecialchars($_POST["reason"]);

    // File uploading
    if (isset($_FILES["requirements"]) && !empty($_FILES["requirements"]["name"][0])) {
        $uploadedFiles = [];
        $totalFiles = count($_FILES["requirements"]["name"]);

        // Limit number of files upload (2 - 4 files)
        if ($totalFiles < 1 || $totalFiles > 4) {
            array_push($invalid, "Please upload between 2 and 4 files only.");
        } else {
            for ($i = 0; $i < $totalFiles; $i++) {
                // Retrieve file details
                $fileName = $_FILES['requirements']['name'][$i];
                $fileTmpName = $_FILES['requirements']['tmp_name'][$i];
                $fileSize = $_FILES['requirements']['size'][$i];
                $fileError = $_FILES['requirements']['error'][$i];
                $fileType = mime_content_type($fileTmpName);

                // Valid file types (image or PDF)
                if (!in_array($fileType, ["image/jpeg", "image/png", "application/pdf"])) {
                    array_push($invalid, "Invalid file type for file: $fileName. Only JPEG, PNG, and PDF files are allowed.");
                    continue;
                }

                // Generate a unique file name
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = "MedCert_" . uniqid() . "." . $fileExtension;

                // Set the file destination path
                $fileDest = $uploadDir . $uniqueFileName;

                // Move uploaded file to the desired folder
                if (move_uploaded_file($fileTmpName, $fileDest)) {
                    $uploadedFiles[] = $uniqueFileName; // Save the unique file name
                } else {
                    array_push($invalid, "Failed to upload file: $fileName.");
                }
            }

            // Save uploaded file names to the database
            if (!empty($uploadedFiles)) {
                $uploadedFileNames = implode(", ", $uploadedFiles); // Convert array to comma-separated string

                $addAssistance = $assistanceFacade->addAssistance(
                    $pwdId,
                    $assistance,
                    $status,
                    $reason,
                    $uploadedFileNames
                );

                if ($addAssistance) {
                    header("Location: assistance.php?msg=Assistance has been added successfully!");
                    exit;
                } else {
                    array_push($invalid, "Failed to save assistance data to the database.");
                }
            }
        }
    } else {
        array_push($invalid, "No files selected for upload.");
    }
}


/*$uploadDir = ".././public/img/requirements/uploads/" . $firstName . "_" . $lastName . "/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_POST["apply_assistance"])) {
    $pwdId = htmlspecialchars($_POST["pwd_id"]);
    $assistance = htmlspecialchars($_POST["assistance"]);
    $status = 'Pending';
    $reason = htmlspecialchars($_POST["reason"]);

    //File uploading
    if (isset($_FILES["requirements"]) && !empty($_FILES["requirements"]["name"][0])) {
        $uploadedFiles = [];
        $totalFiles = count($_FILES["requirements"]["name"]);

            // Limit number of files upload (2 - 4 files)
            if ($totalFiles < 1 || $totalFiles > 4) {
                array_push($invalid, "Please upload between 2 and 4 files only.");
            } else {
                for ($i = 0; $i < $totalFiles; $i++) {
                    // Check if file is an image or PDF
                    $fileName = $_FILES['requirements']['name'][$i];
                    $fileTmpName = $_FILES['requirements']['tmp_name'][$i];
                    $fileSize = $_FILES['requirements']['size'][$i];
                    $fileError = $_FILES['requirements']['error'][$i];
                    $fileType = $_FILES['requirements']['type'][$i];
                        
                    // Valid file types (image or PDF)
                    if (!in_array($fileType, ["image/jpeg", "image/png", "application/pdf"])) {
                        array_push($invalid, "Invalid file type for file: $fileName. Only images and PDFs are allowed.");
                        continue;
                    }

                    // Generate a unique file name in the format MedCert_<autogenerated number>.jpg/pdf
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uniqueFileName = "MedCert_" . uniqid() . "." . $fileExtension;

                    // Define the upload directory
                    //$uploadDir = ".././public/img/requirements/uploads/";

                    // Set the file destination path
                    $fileDest = $uploadDir . $uniqueFileName;

                    // Move uploaded file to the desired folder
                    if (move_uploaded_file($fileTmpName, $fileDest)) {
                        $uploadedFiles[] = $uniqueFileName; // Save the unique file name
                    } else {
                        array_push($invalid, "Failed to upload file: $fileName.");
                    }
                }
            }

            // Save uploaded file names to the database
            if (!empty($uploadedFiles)) {
                $uploadedFileNames = implode(", ", $uploadedFiles); // Convert array to comma-separated string
                // echo "<pre>" . $uploadedFileNames . "</pre>";
                // exit;

                $addAssistance = $assistanceFacade->addAssistance(
                    $pwdId,
                    $assistance,
                    $status,
                    $reason,
                    $uploadedFileNames
                );

                if ($addAssistance) {
                    header("Location: assistance.php?msg=Assistance has been added successfully!");
                    exit;
                } else {
                    array_push($invalid, "Failed to save assistance data to the database.");
                }
            }
        }*/

    /*if (isset($_FILES["requirements"]) && !empty($_FILES["requirements"]["name"][0])) {
        $uploadedFiles = [];
        $totalFiles = count($_FILES["requirements"]["name"]);
    
        // Limit number of files upload (2 - 4 files)
        if ($totalFiles < 1 || $totalFiles > 4) {
            array_push($invalid, "Please upload between 2 and 4 files only.");
        } else {
        
        for ($i = 0; $i < $totalFiles; $i++) {
            // Check if file is an image or PDF
            $fileName = $_FILES['requirements']['name'][$i];
            $fileTmpName = $_FILES['requirements']['tmp_name'][$i];
            $fileSize = $_FILES['requirements']['size'][$i];
            $fileError = $_FILES['requirements']['error'][$i];
            $fileType = $_FILES['requirements']['type'][$i];
                
            // Valid file types (image or PDF)
            if (!in_array($fileType, ["image/jpeg", "image/png", "application/pdf"])) {
                array_push($invalid, "Invalid file type for file: $fileName. Only images and PDFs are allowed.");
            
                continue;
            }

            // Move uploaded file to the desired folder
            //$uploadDir = ".././public/img/requirements/uploads/";
            $uniqueFileName =  basename($fileName); //uniqid() . '_' . if want to unique tge name
            $fileDest = $uploadDir . $uniqueFileName;
            //$fileDest = $uploadDir . $uniqueFileName;

                if (move_uploaded_file($fileTmpName, $fileDest)) {
                    $uploadedFiles[] = $uniqueFileName; // Save the unique file name
                } else {
                    array_push($invalid, "Failed to upload file: $fileName.");
                }
            }
        }
            
        // Save uploaded file names to the database
        if (!empty($uploadedFiles)) {
            $uploadedFileNames = implode(", ", $uploadedFiles); // Convert array to comma-separated string
              //  echo "<pre>" . $uploadedFileNames . "</pre>";
            //exit;
            $addAssistance = $assistanceFacade->addAssistance(
                $pwdId,
                $assistance,
                $status,
                $reason,
                $uploadedFileNames
            );

            if ($addAssistance) {
                header("Location: assistance.php?msg=Assistance has been added successfully!");
                exit;
            } else {
                array_push($invalid, "Failed to save assistance data to the database.");
            }
        }
    }*/
//}

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
                            ?>
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Apply Assistance</h6>
                            </div>
                            <div class="card-body">
                            <div class="row">
                            <div class="table-responsive" style="display: inline-block; width: 49%;">
                                <table class="table-custom display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-fw fa-info-circle"></i> Requirements for Cash Assistance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PWD ID</td>
                                        </tr>
                                        <tr>
                                            <td>Indigency</td>
                                        </tr>
                                        <tr>
                                            <td>Medical Record/Medical Abstract</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" style="display: inline-block; width: 49%;">
                                <table class="table-custom display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-fw fa-info-circle"></i> Requirements for Food Assistance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PWD ID</td>
                                        </tr>
                                        <tr>
                                            <td>Indigency</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                <form action="apply-assistance.php" method="post" enctype="multipart/form-data">
                                    <div class="form-floating my-3"> 
                                        <select class="form-control" id="assistance" name="assistance" required>
                                            <option value="None">--- Select Assistance Type ---</option>
                                            <option value="Cash Assistance">Cash Assistance</option>
                                            <option value="Food Assistance">Food Assistance</option>
                                        </select>
                                        <label for="assistance">Type of Assistance</label>
                                    </div>
                                    <div class="form-floating my-3">
                                        <textarea name="reason" id="reason" class="form-control" style="resize: none;" required></textarea>
                                        <label for="reason"> Reason </label>
                                    </div>
                                    <div class="form-floating my-3">
                                        <input type="file" name="requirements[]" id="requirements" class="form-control" multiple accept="image/*,.pdf" required>
                                        <label for="requirements" style="padding-top: 10px;"> Requirements</label>
                                        <div id="file-names" class="mt-2 text-muted"></div>
                                        <small class="form-text text-muted">
                                            *** Please upload the file(s) with the name format: <strong>MedCert.pdf</strong> (e.g., Medical Certificate).
                                        </small>
                                    </div>
                                    <input type="hidden" name="pwd_id" value="<?= $userId ?>">
                                    <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="apply_assistance">Apply Assistance</button>
                                </form>
                            </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        let uploadedFiles = [];

        $('#requirements').on('change', function () {
            const files = Array.from(this.files);

            uploadedFiles = [...new Map([...uploadedFiles, ...files].map(file => [file.name, file])).values()];
            updateFileList();
        });

        function updateFileList() {
            const fileListHTML = uploadedFiles.map((file, index) =>
                `<div class="file-item d-inline-flex align-items-center">
                    <span>${file.name}</span>
                    <button type="button" class="btn btn-sm btn-danger ml-2 remove-file-btn" data-index="${index}">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`).join('');
            $('#file-names').html(fileListHTML);

            $('.remove-file-btn').on('click', function () {
                const index = $(this).data('index');
                uploadedFiles.splice(index, 1);
                updateFileList();
            });
        }
    });
</script>