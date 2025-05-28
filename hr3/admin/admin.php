<?php
include_once '../connections.php';

if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'];
}

$theme = $_SESSION['theme'] ?? 'light';

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="icon" href="nav/logo.png">

    <!-- Theme loader -->
    <script>
        const savedTheme = localStorage.getItem("theme") || "light";
        document.documentElement.setAttribute("data-bs-theme", savedTheme);
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="nav/tm.css">

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="nav/theme.js"></script>
</head>

<style>
    .light-theme {
        background-color: #f8f9fa;
        color: #212529;
    }

    .dark-theme {
        background-color: #343a40;
        color: #f8f9fa;
    }
</style>

<body>
<?php if (isset($_SESSION['flash'])): ?>
    <script>
        alert("<?= $_SESSION['flash'] ?>");
    </script>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<?php include_once 'nav/nav.php'; ?>
