<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="assets/register.css?v=1.0">
</head>
<body>

<div class="logo">
    <img src="components/logo.png" alt="Logo" class="logo">
</div>
    <form action="register.inc.php" method="POST" class="login-form position-absolute" <?php htmlspecialchars("PHP_SELF")?> style="top: 28%; left: 17%;">
<?php
    if (isset($_SESSION['errorpo'])) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo "<strong>" . $_SESSION['errorpo'] . "!</strong>"; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
    unset($_SESSION['errorpo']); // unset the session variable here
}
?>

        <h1 class="login-title">Sign up</h1>

        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" name="name" placeholder="Full Name" title="Fullname example: Juan Dela Cruz" required>
            
        </div>

        <div class="input-box">
            <i class='bx bxs-envelope'></i>
            <input type="text" name="email" placeholder="email" title="Email example: juan@gmail.com" required>
            
        </div>
                    
        <div class="input-box">
            <i class="fa-solid fa-key"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>


        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="login-btn">Sign up</button>

        <p class="register">
            Already has an account? 
            <a href="index.php">Login</a>
        </p>
    </form>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>