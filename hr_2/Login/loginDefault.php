<?php 
session_start();
include("../../../phpcon/conn.php");
$email = $password = "";
$emailErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password required";
    } else {
        $password = $_POST["password"];
    }

    if ($password && $email) {
        include("Connections.php");
        $check_email = mysqli_query($Connection, "SELECT * FROM login_tbl WHERE email = '$email'");
        $check_email_row = mysqli_num_rows($check_email);
        if ($check_email_row > 0) {
            while ($row = mysqli_fetch_assoc($check_email)) {
                $db_pass = $row["Password"];
                $db_acc_type = $row["user_role"];
                if ($password == $db_pass) {
                    if ($db_acc_type == "admin") {
                        header("Location: ../admin/training_management/training_management.php");
                        exit();
                    } elseif ($db_acc_type == "user") {
                        header("Location: ../employee/training_management/training_management.php");
                        exit();
                    }
                     elseif ($db_acc_type == "trainer") {
                        header("Location: ../trainer/training_management/training_management.php");
                        exit();
                    }
                     else {
                        header("Location: ../Manager/training_management/training_management.php");
                        exit();
                    }
                } else {
                    $passwordErr = "Incorrect password";
                }
            }
        } else {
            $emailErr = "Your email is unregistered";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/logo.jpg">
    <link rel="stylesheet" href="assets/login.css?v=1.0">
</head>
<body>    

<div class="logo">
    <img src="assets/logo.png" alt="Logo" class="logo">
</div>

    <form method="POST" class="login-form" <?php htmlspecialchars("PHP_SELF")?>>
        
    <?php
    if (isset($_SESSION['status'])) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo "<strong>" . $_SESSION['status'] . "!</strong>"; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
    unset($_SESSION['status']); // unset the session variable here
}
?>
        <h1 class="login-title">Login</h1>
        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" name="email" placeholder="Username" value="<?php echo $email; ?>"?>
        </div>
        <span class="errorMessage"> <?php echo $emailErr ?></span>
                      
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
        </div>
        <span class="errorMessage"><?php echo $passwordErr?></span>
        <div class="remember-forgot-box">
            <label for="remember">
                <input type="checkbox" id="remember">
                Remember me
            </label>
            <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="login-btn">Login</button>
        <p class="register">
            Don't have an account?
            <a href="register.php">Register</a>
        </p>
    </form>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>