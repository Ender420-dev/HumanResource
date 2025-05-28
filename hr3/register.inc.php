<?php
session_start();
$Connection = mysqli_connect ("localhost:3307","root","","hr3");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $Email = htmlspecialchars($_POST["email"]);
    $Password = htmlspecialchars($_POST["password"]);
    $cpassword = htmlspecialchars($_POST["cpassword"]);

    // Check if passwords match
    if ($cpassword === $Password) {
        // Check if any fields are empty
        if (empty($Email) || empty($Password) || empty($cpassword)) {
            $_SESSION['errorpo'] = "All fields are required";
            header("location: register.php");
            $Connection->close();
            exit();
        } else {
            // Update query to remove account_type
            $query = "INSERT INTO login (full_name,email, password) 
                      VALUES ('$name', '$Email', '$Password')";
            $query_run = mysqli_query($Connection, $query);

            // Check if the query ran successfully
            if ($query_run) {
                $_SESSION['status'] = "Account Successfully Registered";
                header("location: index.php");
                $Connection->close();
                exit();
        
            } else {
                echo "Something went wrong!";
            }
        }
    } else {
        $_SESSION['errorpo'] = "Password did not match!";
        header("location: register.php");
        $Connection->close();
        exit();
    }
}
?>
