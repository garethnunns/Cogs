<?php
   include("database_connection.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {  
      
      $loginUsername = ($db,$_POST['username']);
      $loginPassword = ($db,$_POST['password']); 
      
      $sql = "SELECT idEmp FROM login WHERE username = '$loginUsername' and passcode = '$loginPassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result); 
		
      if($count == 1) {
         session_register("loginUsername");
         $_SESSION['user'] = $loginUsername;
         
         header("refresh:0; url=home");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
	
	
	
