<?php

function increasePoints($conn,$userId,$points){
    $sql = "INSERT IGNORE INTO points (userId) VALUES (?)";
    $stmt = prepared_query($conn,$sql,[$userId],"i");
    mysqli_stmt_close($stmt);
    
    $sql = "UPDATE points SET points = points + ? WHERE userId = ?";
    $stmt = prepared_query($conn,$sql,[$points,$userId],"ii");
    if ($stmt === false) {
        error(mysqli_error($conn) + " Please contact rdevcca@gmail.com");
    }
    
    mysqli_stmt_close($stmt);
}


?>