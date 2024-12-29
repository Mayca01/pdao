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

// Check if the form was submitted
if (isset($_POST["add_information"])) {
    // Retrieve form data
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);

    // Initialize an array for error messages
    $invalid = [];

    // Validate form inputs
    if (empty($title)) {
        array_push($invalid, 'Title should not be empty!');
    }
    if (empty($description)) {
        array_push($invalid, 'Description should not be empty!');
    }

    // Handle file uploads
    $files = $_FILES["image"];
    $allowed = ['jpg', 'jpeg', 'png']; // Allowed file extensions
    $uploadDir = '.././public/img/informations/';
    $uploadedFileNames = []; // Array to hold uploaded filenames

    if (!empty($files['name'][0])) {
        $totalFiles = count($files['name']);
        if ($totalFiles > 5) {
            array_push($invalid, 'You can upload a maximum of 5 images.');
        } else {
            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = $files["name"][$i];
                $fileTmpName = $files["tmp_name"][$i];
                $fileSize = $files["size"][$i];
                $fileError = $files["error"][$i];

                // Validate file type and size
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (in_array($fileExt, $allowed)) {
                    if ($fileSize <= 5000000) { // 5MB per file
                        // Generate unique filename starting with MedCert_
                        $fileNameNew = 'MedCert_' . uniqid('', true) . "." . $fileExt;
                        $fileDestination = $uploadDir . $fileNameNew;

                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            $uploadedFileNames[] = $fileNameNew; // Save only the filename
                        } else {
                            array_push($invalid, "Failed to upload $fileName.");
                        }
                    } else {
                        array_push($invalid, "$fileName exceeds the maximum file size of 5MB.");
                    }
                } else {
                    array_push($invalid, "$fileName has an unsupported file type.");
                }
            }
        }
    } else {
        array_push($invalid, "At least one image is required!");
    }

    // If there are no errors, save the information to the database
    if (empty($invalid)) {
        // Convert the array of filenames to a comma-separated string
        $information = implode(', ', $uploadedFileNames);
        $addInformation = $informationsFacade->addInformation($title, $information, $description);

        if ($addInformation) {
            header("Location: informations.php");
            exit();
        } else {
            array_push($invalid, "Failed to save information to the database.");
        }
    }

    // Display errors if any
    if (!empty($invalid)) {
        foreach ($invalid as $error) {
            echo "<p class='text-danger'>$error</p>";
        }
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
                                <h6 class="m-0 font-weight-bold">Assign Equipments</h6>
                            </div>
                            <form action="add-information.php" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                                        <label for="title">Title</label>
                                    </div>
                                    <div class="my-3">
                                        <label for="image" class="form-label">Image <span class="text-muted" style="font-size: 10pt;">(**Please attach files in format such: Ex: Medcert.pdf, Medcert.jpg, Medcert.jpeg, or Medcert.png**)</span></label>
                                        <input type="file" class="form-control" id="image" name="image[]" multiple accept="image/*" required>
                                        <div id="file-list" class="mt-2 d-flex flex-wrap gap-2"></div>
                                    </div>
                                    <div class="form-floating">
                                        <textarea class="form-control p-2" name="description" style="height: 100px; overflow: hidden"></textarea>
                                        <label for="description" class="mb-2">Description</label>
                                    </div>
                                    <input type="hidden" name="pwd_id" value="<?= $userId ?>">
                                    <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="add_information">Add Information</button>
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
<script type="text/javascript">
    const imageInput = document.getElementById('image');
    const fileListContainer = document.getElementById('file-list');

    imageInput.addEventListener('change', (event) => {
        const files = event.target.files;
        fileListContainer.innerHTML = ''; // Clear previous file list

        Array.from(files).forEach((file, index) => {
            // Create a container for the file name and remove button
            const fileItem = document.createElement('div');
            fileItem.classList.add('file-item', 'd-flex', 'align-items-center', 'border', 'px-2', 'py-1', 'rounded');
            fileItem.style.display = "inline-flex";

            // File name
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            fileName.classList.add('me-2', 'text-truncate');

            // Remove button
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Remove';
            removeButton.classList.add('btn', 'btn-sm', 'btn-danger');
            removeButton.type = 'button';
            removeButton.dataset.index = index; // Store index for identification

            // Remove file logic
            removeButton.addEventListener('click', () => {
                const fileArray = Array.from(imageInput.files);
                fileArray.splice(index, 1); // Remove the file at the specified index

                // Create a new DataTransfer object to replace the file list
                const dataTransfer = new DataTransfer();
                fileArray.forEach((remainingFile) => dataTransfer.items.add(remainingFile));
                imageInput.files = dataTransfer.files; // Update the input files

                // Refresh file list
                imageInput.dispatchEvent(new Event('change'));
            });

            // Append file name and button to the file item
            fileItem.appendChild(fileName);
            fileItem.appendChild(removeButton);

            // Append the file item to the container
            fileListContainer.appendChild(fileItem);
        });
    });
</script>