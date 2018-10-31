<?php 
//start session and connect
session_start();
include('connection.php');
//get user_id and email sent through Ajax
$user_id = $_SESSION['user_id'];
$newemail = $_POST['email'];

//check if new email exists
$sql = "SELECT * FROM users WHERE email='$newemail'";

$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);
if($count>0){
    
    echo "<div class=''>There is already as user registered with that email! Please choose another one!</div>"; exit;
}

//get the current email
//get username and email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";

$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);

if($count == 1){
     
     $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    $email = $row['email'];
    
}else{
    
    echo "There was an error retrieving the  email from the database!";
    
    exit;
    
}

//create a unique activation code in the users table
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));

//insert new activation code in the users table
$sql = "UPDATE users SET activation2='$activationKey' WHERE user_id='$user_id'";

$result = mysqli_query($link,$sql);
if(!$result){
    
    echo "<div class='alert alert-danger'>There was an error inserting the user details in the database.</div>"; 
    
    exit;
    
}else{
    
//send email with link to activatenewemail.php with current email, new email and activation code
    
$message = "Please Click on this link to prove that you own this email:\n\n";
    
$message .= "http://localhost/vigne_full_web_development/WebDevelopment/WEBSITES/10.OnlineNotesApp/activatenewemail.php?email=". urlencode($email)."&newemail=".urlencode($newemail). "&key=$activationKey";
    
$sent_email = @mail($newemail, 'Email Update for you Online Notes App', $message, 'From:'.'mwalilanyira@gmail.com');
    
if($sent_email){
    
   echo "<div class=\"alert alert-success\">A email has been sent to $newemail. Please Click on the  link to prove you own that email address.</div>";
    
}
    
}



?>