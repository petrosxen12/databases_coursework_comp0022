<?php
// Include config file
require_once "../dbConnect.php";

// Define variables and initialize with empty values
$email_err = $password = $confirm_password = $email =  "";
$password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Enter a valid email address";
    }

    $email = trim($_POST["email"]);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare a select statement
        $email_err = "";
        $sql = "SELECT AccountID FROM Accounts WHERE email = (?)";

        // Set parameters
        $param_email = array(trim($_POST["email"]));
        $stmt = sqlsrv_query($conn, $sql, $param_email);

        if ($stmt === false) {
            print_r(sqlsrv_errors());
            $email_err = "Oops! Something went wrong. Please try again later.";
        } else {
            /* store result */
            if (sqlsrv_num_rows($stmt) == 1) {
                $email_err = "This email is already taken.";
            } else {
                $email = trim($_POST["email"]);
            }
        }
        // Close statement
        sqlsrv_free_stmt($stmt);
    }

    $email = trim($_POST["email"]);

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO Accounts VALUES (?, ?)";
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $param_email = $email;

        $params = array($param_email, $param_password);
        $stmt = sqlsrv_query($conn, $sql, $params);
        // Attempt to execute the prepared statement
        if ($stmt === false) {
            // Redirect to login page
            $password_err = "Something went wrong. Please try again later.";
            print_r(sqlsrv_errors());
        } else {
            header("location: login.html");
        }

        // Close statement
        sqlsrv_free_stmt($stmt);
    }

    // Close connection
    sqlsrv_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Page Title - SB Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter email">
                                            <span class="help-block"><?php echo $email_err; ?></span>
                                        </div>

                                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter password">
                                            <span class="help-block"><?php echo $password_err; ?></span>
                                        </div>

                                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Reenter password">
                                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                        </div>



                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <input type="reset" class="btn btn-default" value="Reset">
                                        </div>
                                        <p>Already have an account? <a href="login.php">Login here</a>.</p>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="register.html">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2019</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>