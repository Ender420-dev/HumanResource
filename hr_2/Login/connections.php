<?php 
    $Connection = mysqli_connect ("localhost:3306","root","","hr2");
        if(mysqli_connect_errno()){
            echo"failed to connect in mySQL:" .mysqli_connect_error();
        }else{
            echo"";
        }

?>