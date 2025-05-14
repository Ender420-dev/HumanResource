<?php 
    $Connection = mysqli_connect ("localhost:3307","root","","hr2");
        if(mysqli_connect_errno()){
            echo"failed to connect in mySQL:" .mysqli_connect_error();
        }else{
            echo"";
        }

?>