<?php
require_once 'login.php';
require_once 'utils.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$query = "SELECT * FROM laite";
$result = $conn->query($query);
if (!$result) die("Database access failed");

$deviceModals = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Modern Business - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100" data-bs-backdrop="static">
    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="index.php">StadinAO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="faq.html">FAQ</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-5">
                <div class="row gx-5 align-items-center justify-content-center">
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="my-5 text-center text-xl-start">
                            <h1 class="display-5 fw-bolder text-white mb-2">Device library</h1>
                            <p class="lead fw-normal text-white-50 mb-4">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit!</p>
                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                                <button type="button" class="btn btn-primary btn-custom-stadin btn-lg px-4 me-sm-3" data-bs-toggle="modal" data-bs-target="#addDeviceModal">Add Device</button>
                                <a class="btn btn-outline-light btn-lg px-4" href="#loans">View Loans</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center"><img class="img-fluid rounded-3 my-5" src="./assets/images/logo-white.svg?1" alt="..." /></div>
                </div>
            </div>
        </header>
        <!-- Blog preview section-->
        <section class="py-5">
            <div class="container px-5 my-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <div class="text-center">
                            <h2 class="fw-bolder">Avaliable devices</h2>
                            <p class="lead fw-normal text-muted mb-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                        </div>
                    </div>
                </div>
                <?php
                    $columnCount = 0;

                    echo '<div class="row gx-5">';

                    while ($row = $result->fetch_assoc()) {
                        $deviceModalIdStub = "DeviceModal-" . $row['sn'] . '-' . $columnCount;
                        $loanDeviceModalId = 'loan' . $deviceModalIdStub;
                        $editDeviceModalId = 'edit' . $deviceModalIdStub;

                        $device_id = $row['id'];

                        echo '<div class="col-lg-4 mb-5">';
                            echo '<div class="card h-100 shadow border-0">';
                            echo '<img class="card-img-top" src="https://dummyimage.com/600x350/ced4da/6c757d" alt="..." />';
                            echo "<div class='card-body'>
                                <div class='card-title'>{$row['name']}</div>
                            </div>";
                            echo "<ul class='list-group list-group-flush'>
                                <li class='list-group-item'>SN: {$row['sn']}</li>
                                <li class='list-group-item'>Category: {$row['category']}</li>
                            </ul>";
                            echo "<div class='card-body'>
                                <button data-bs-toggle='modal' data-bs-target='#$editDeviceModalId' class='btn btn-secondary' href=''>Edit</button>
                                <button data-bs-toggle='modal' data-bs-target='#$loanDeviceModalId' class='btn btn-primary' href=''>Loan</button>
                            </div>";
                            $deviceModals .= <<<_END
                                <div class="modal fade" tabindex="-1" aria-hidden="true" id='$editDeviceModalId'>
                                    <div class="modal-dialog  modal-dialog-centered">
                                        <form action="editdevice.php?id=$device_id" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Edit Device</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="#nameInput" class="form-label">Name:</label>
                                                        <input type="text" name="name" value="{$row['name']}" required class="form-control" id="{"nameInput" . $deviceModalIdStub}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="#snInput" class="form-label">Serial Number:</label>
                                                        <input type="text" name="sn" value="{$row['sn']}" required class="form-control" id={"snInput" . $deviceModalIdStub}>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="#categoryInput" class="form-label">Category:</label>
                                                        <input type="text" name="category" value="{$row['category']}" required class="form-control" id={"categoryInput" . $deviceModalIdStub}>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                _END;
                            $deviceModals .= <<<_END
                                <div class='modal' id='$loanDeviceModalId'>
                                </div>
                                _END;
                            echo '</div>';
                        echo '</div>';

                        $columnCount++;

                        if ($columnCount % 3 == 0) {
                            echo '</div>'; // Closing the <div class="row">
                            echo '<div class="row gx-5">';
                        }
                    }

                    if ($columnCount % 3 != 0) {
                        echo '</div>'; // close a single unclosed row
                    }
                    
                ?>
            </div>
        </section>
        <!-- List of loans section-->
        <section class="py-5">
            <div class="container px-5 my-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <div class="text-center">
                            <a id="loans"></a>
                            <h2 class="fw-bolder">Loans Managment</h2>
                            <p class="lead fw-normal text-muted mb-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque fugit ratione dicta mollitia. Officiis ad.</p>
                        </div>
                    </div>
                    <div class="container text-center">
                        <div class="row align-items-start">
                            <div class="col">
                                <?php
                                echo get_loans($conn, LoanQueryType::Active);
                                ?>
                            </div>
                            <div class="col">
                                <?php
                                echo get_loans($conn, LoanQueryType::Overdue);
                                ?>
                            </div>
                            <div class="col">
                                <?php
                                echo get_loans($conn, LoanQueryType::Returned);
                                ?>
                            </div>
                        </div>
                    </div>
        </section>


    </main>
    <!-- Footer-->
    <footer class="bg-dark py-4 mt-auto">
        <div class="container px-5">
            <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                <div class="col-auto">
                    <div class="small m-0 text-white">Copyright &copy; Your Website 2023</div>
                </div>
                <div class="col-auto">
                    <a class="link-light small" href="#!">Privacy</a>
                    <span class="text-white mx-1">&middot;</span>
                    <a class="link-light small" href="#!">Terms</a>
                    <span class="text-white mx-1">&middot;</span>
                    <a class="link-light small" href="#!">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    <div class="modal fade" tabindex="-1" aria-hidden="true" id="addDeviceModal">
        <div class="modal-dialog  modal-dialog-centered">
            <form action="newdevice.php" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Device</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="#nameInput" class="form-label">Name:</label>
                            <input type="text" name="name" required class="form-control" id="nameInput">
                        </div>
                        <div class="mb-3">
                            <label for="#snInput" class="form-label">Serial Number:</label>
                            <input type="text" name="sn" required class="form-control" id="snInput">
                        </div>
                        <div class="mb-3">
                            <label for="#categoryInput" class="form-label">Category:</label>
                            <input type="text" name="category" required class="form-control" id="categoryInput">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Device</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php echo $deviceModals ?>
    <!-- Features section-->
    <section class="py-5" id="features">
        <div class="container px-5 my-5">
            <div class="row gx-5">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h2 class="fw-bolder mb-0">A better way to start building.</h2>
                </div>
                <div class="col-lg-8">
                    <div class="row gx-5 row-cols-1 row-cols-md-2">
                        <div class="col mb-5 h-100">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-collection"></i></div>
                            <h2 class="h5">Featured title</h2>
                            <p class="mb-0">Paragraph of text beneath the heading to explain the heading. Here is just a bit more text.</p>
                        </div>
                        <div class="col mb-5 h-100">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-building"></i></div>
                            <h2 class="h5">Featured title</h2>
                            <p class="mb-0">Paragraph of text beneath the heading to explain the heading. Here is just a bit more text.</p>
                        </div>
                        <div class="col mb-5 mb-md-0 h-100">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                            <h2 class="h5">Featured title</h2>
                            <p class="mb-0">Paragraph of text beneath the heading to explain the heading. Here is just a bit more text.</p>
                        </div>
                        <div class="col h-100">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                            <h2 class="h5">Featured title</h2>
                            <p class="mb-0">Paragraph of text beneath the heading to explain the heading. Here is just a bit more text.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>

<?php

// while ($row = $result->fetch_assoc()) {
//     echo "<div>
//         <strong>{$row['name']}</strong> - SN: {$row['sn']} - Category: {$row['category']}
//         <a href='viewdevice.php?id={$row['id']}'>View</a>
//     </div>";
// }

$result->close();
$conn->close();
?>