<?php
session_start();
// Include your database connections.php file
include("connections.php");

// Initialize variables for form fields and error messages
$email = "";
$password = "";
$emailErr = "";
$passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = htmlspecialchars(trim($_POST["email"])); // Sanitize input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password required";
    } else {
        $password = $_POST["password"]; // Password will be hashed later, no need to sanitize yet
    }

    // Only proceed if there are no validation errors
    if (empty($emailErr) && empty($passwordErr)) {
    try {
        $conn = $conn_hr3; // Assuming your 'login' table is in hr1

        if ($conn) {
            // Changed 'employee_id' to 'id' to match your 'login' table schema
            $sql = "SELECT id, password, Account_Type FROM login WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($password === $user["password"]) {
                    // Storing 'id' from the login table as 'employee_id' in session
                    $_SESSION['employee_id'] = $user['id'];
                    $_SESSION['account_type'] = $user['Account_Type'];
                    $_SESSION['logged_in'] = true;

                    if ($user["Account_Type"] == "admin") {
                        header("Location: admin/");
                        exit();
                    } elseif ($user["Account_Type"] == "manager") {
                        header("Location: /manager/");
                        exit();
                    } else {
                        header("Location: /user/");
                        exit();
                    }
                } else {
                    $passwordErr = "Incorrect password";
                }
            } else {
                $emailErr = "Your email is unregistered";
            }
        } else {
            $_SESSION['status'] = "Database connection error. Please try again later.";
            $_SESSION['message_type'] = "danger";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['status'] = "An unexpected error occurred. Please try again.";
        $_SESSION['message_type'] = "danger";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/login.css?v=1.0">
</head>
<body>
<div class="logo">
    <img src="assets/logo.png" alt="Logo" class="logo">
</div>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form position-absolute" style="top: 28%; right: 17%;">
    <?php if (isset($_SESSION['status'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $_SESSION['status']; ?>!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <h1 class="login-title">Login</h1>
    <div class="input-box">
        <i class='bx bxs-user'></i>
        <input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
    </div>
    <span class="errorMessage text-danger fw-bold"><?php echo $emailErr; ?></span>

    <div class="input-box">
        <i class='bx bxs-lock-alt'></i>
        <input type="password" name="password" placeholder="Password">
    </div>
    <span class="errorMessage text-danger fw-bold"><?php echo $passwordErr; ?></span>

    <div class="remember-forgot-box">
        <label for="remember">
            <input type="checkbox" id="remember"> Remember me
        </label>
        <a href="#">Forgot Password?</a>
    </div>

    <button type="submit" class="login-btn">Login</button>
    <p class="register">
        Don't have an account? <a href="register.php">Register</a>
    </p>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
