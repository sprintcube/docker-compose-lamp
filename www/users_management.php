<?php
require_once 'login.php';
require_once 'utils.php';
require_once 'db/user-functions.php';
require_once 'db/loans-functions.php';
require_once 'page-components/loan-management.php';
require_once 'page-components/user-management.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");
session_start();

if (!is_logged_in() || !is_allowed_user_role([ROLE_SUPER_ADMIN])) {
    header("Location: /index.php");
}
function is_admin_or_superadmin($u_row) {
    return in_array($u_row['role'], [ROLE_ADMIN, ROLE_SUPER_ADMIN]);
}

$is_searching = isset($_GET['q']);
$param_user_search_query = $is_searching ? $_GET['q'] : false;
$param_users_page_num = isset($_GET['upage']) ? $_GET['upage'] : 1;
$param_admins_page_num = isset($_GET['apage']) ? $_GET['apage'] : 1;

$number_of_users = count_users($conn);
$number_of_pages = (int) ceil($number_of_users / DEFAULT_USERS_PAGE_SIZE);

$users_info = $is_searching 
    ? search_users_info($conn, $param_user_search_query, $param_users_page_num)
    : get_all_users_info($conn, $param_users_page_num);
$admins_info = array_filter($users_info, "is_admin_or_superadmin");

$users_rendered = render_users_list($users_info);
$admins_rendered = render_users_list($admins_info);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content />
    <meta name="author" content />
    <title>Device Library</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="index.php">StadinAO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <?php if (!is_logged_in()) { ?>
                            <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                            <li class="nav-item"><a class="nav-link" href="loginpage.html">Log in</a></li>
                        <?php } else { ?>
                            <li class="nav-item"><a class="nav-link" href="profile-page.php">Profile page</a></li>
                            <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN]))  {?>
                                <li class="nav-item"><a class="nav-link" href="users_management.php">Users management</a></li>
                            <?php } ?>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page content-->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <?php echo get_user_search_form($param_user_search_query); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-m-12">
                        <div class="container px-5">
                            <h2 class="fw-bolder my-4">Users list: </h2>
                            <?php 
                                echo render_users_pagination_page_links($param_users_page_num, $number_of_pages, DEFAULT_USERS_PAGE_SIZE);
                                echo $users_rendered; 
                                echo render_users_pagination_page_links($param_users_page_num, $number_of_pages, DEFAULT_USERS_PAGE_SIZE);
                            ?>
                            <!-- <ul class="list-group list-group-flush">
                                <li class="list-group-item">Full name: <?php echo $user_data['name'] ?></li>
                                <li class="list-group-item">Username: <?php echo $user_data['username']; ?></li>
                                <li class="list-group-item">Email: <?php echo $user_data['email'] ?></li>
                            </ul> -->
                        </div>
                    </div>
                    <div class="col-lg-6 col-m-12">
                        <div class="container px-5">
                            <h2 class="fw-bolder my-4">Administrators: </h2>
                            <?php echo $admins_rendered; ?>
                            <!-- <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
                                    <i class="bi bi-bell"></i>
                                    <strong class="me-auto mx-2">Administrator</strong>
                                    <small>11 mins ago</small>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Hello, world! This is a toast message.
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <!-- Footer-->
    <footer class="bg-dark py-5 mt-auto">
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script type="module" src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>