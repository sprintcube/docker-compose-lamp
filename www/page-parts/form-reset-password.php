
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
        <link href="/css/styles.css" rel="stylesheet" />
        <link href="/css/custom.css" rel="stylesheet" />
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
                            <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="/contact.html">Register</a></li>
                            <li class="nav-item"><a class="nav-link" href="/loginpage.html">Log in</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <section class="py-5">
                <div class="container px-5">
                    <!-- Contact form-->
                    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
                        <div class="text-center mb-5">
                            <div class="feature bg-icons bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person-add"></i></div>
                            <h1 class="fw-bolder">Password reset form</h1>
                            <strong>This link will remain valid for 30 minutes.</strong>
                        </div>
                        <div class="row gx-5 justify-content-center">
                            <div class="col-lg-8 col-xl-6">
                                <form id="contactForm" class="needs-validation" action="/service/onetime.php" method="post">
                                    <input type="hidden" name="sk" value="<?php echo $sk; ?>" />
                                    <div class="form-floating mb-3">
                                        <div class="input-group mb-3">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password..." aria-describedby="passwordHelpBlock" required>
                                            <span class="input-group-text" id="basic-addon2"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
                                        </div>
                                        <div id="passwordHelpBlock" class="form-text mb-3">
                                        Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                                        </div>
                                        <div class="invalid-feedback" class="mb-3">A password is required.</div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <div class="input-group mb-3">
                                            <input type="password" id="passwordCheck" data-for="password" class="form-control" placeholder="Repeat your password..." aria-describedby="passwordHelpBlock" required>
                                            <span class="input-group-text" id="basic-addon2"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
                                        </div>
                                        <div class="invalid-feedback" class="mb-3">Passwords must be identical.</div>
                                    </div>
                                    <div class="d-none" id="submitSuccessMessage">
                                        <div class="text-center mb-3">
                                            <div class="fw-bolder">Your password has been reset. <a href="/loginpage.html">Sign in</a>.</div>
                                        </div>
                                    </div>
                                    <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                                    <div class="d-grid"><button class="btn btn-secondary" id="submitButton" type="submit">Submit</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- Footer-->
        <footer class="bg-dark py-4 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0 text-white">Copyright &copy; Your Website 2023</div></div>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script type="module" src="/js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>
<script lang="text/javascript">
    !function(){
        // const validatePasswordCheck = ({ target }) => {
        //     const { dataset, value } = target;
        //     const { value: otherValue } = document.getElementById(dataset.for);
        //     if (otherValue === value) {
        //         target.setCustomValidity('Passwords do not match.');
        //     } else {
        //         target.setCustomValidity('');
        //     }

        // }
        // const passwordCheckInput = document.querySelector('input#passwordCheck');
        // passwordCheckInput.addEventListener('change', validatePasswordCheck);

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
        const form = document.querySelector('form');
        const passwordInputs = Array.from(document.querySelectorAll('input[type="password"]'));
        const isValidForm = false;

        const validateInputs = () => {
            const uniquePasswords = new Set(passwordInputs.map(({ value }) => value));
            if (uniquePasswords.size > 1) {
                passwordInputs.forEach(input => input.setCustomValidity("Passwords must match"));
                form.classList.add('was-validated')
            } else {
                passwordInputs.forEach(input => input.setCustomValidity(''));
            }
        }

        passwordInputs.forEach(input => input.addEventListener('change', validateInputs));
    }();
</script>