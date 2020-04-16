<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (getenv("env") == true) {
        header("location: /");
        exit;
    }
    header("location: ../index.php");
    exit;
}

// Include config file
require_once "../dbConnect.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
$showerror = "none";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT * FROM Accounts WHERE email=(?)";
        $param_email = trim($_POST["email"]);
        $params = array($param_email);
        $conn = connectToDB();
        $stmt = sqlsrv_query($conn, $sql, $params, array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        // Attempt to execute the prepared statement
        if ($stmt !== false) {
            // Check if email exists, if yes then verify password
            $vals = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            print($vals['AccountID']);

            $returnedrows = sqlsrv_num_rows($stmt);

            // print(gettype($returnedrows));

            if ($returnedrows === false) {
                print("Error calculating rows");
                print_r(sqlsrv_errors());
            }

            if ($returnedrows == 1) {
                // print("INSIDE");

                $hashed_password = $vals['Password'];

                // print($hashed_password);
                // print($password);

                if (password_verify($password, $hashed_password)) {
                    // print("Password Match");
                    // Password is correct, so start a new session

                    $id = $vals['AccountID'];
                    $email = $vals['Email'];

                    session_start();

                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["email"] = $email;

                    // Redirect user to welcome page
                    header("location: ../index.php");
                    exit();
                } else {
                    // Display an error message if password is not valid
                    $password_err = "The password you entered was not valid.";
                    $showerror = "block";
                }
            } else {
                // Display an error message if email doesn't exist
                $email_err = "No account found with that email.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter email">
                                            <span class="help-block"><?php echo $email_err; ?></span>
                                        </div>
                                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                                            <div class="alert alert-danger" role="alert" style="display: <?php echo $showerror; ?>;">
                                                <span class="help-block"><?php echo $password_err; ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="Login">
                                        </div>
                                        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
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