<?php
session_start();
$Connection = mysqli_connect ("localhost:3307","root","","hr2");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = htmlspecialchars($_POST["email"]);
    $Password = htmlspecialchars($_POST["password"]);
    $cpassword = htmlspecialchars($_POST["cpassword"]);
    $user_role = htmlspecialchars($_POST["user_role"]);

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
            $query = "INSERT INTO login (email, password , Account_Type) 
                      VALUES ('$Email', '$Password', '$user_role')";
            $query_run = mysqli_query($Connection, $query);

            // Check if the query ran successfully
            if ($query_run) {
                $_SESSION['status'] = "Account Successfully Registered";
                header("location: loginDefault.php");
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
