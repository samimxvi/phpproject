<?php  

	if(isset($_POST['register'])){

    if(!isset($_SESSION))
		session_start();

	include('connection.php');
		
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $mobile_number = mysqli_real_escape_string($conn,$_POST['mobile_number']);
    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);
		
    if($password == $confirm_password){
        if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email_address)){
            if(preg_match("/^[6-9]\d{9}$/", $mobile_number)){

                $sql_email = "SELECT email_address FROM user WHERE email_address='$email_address'";
                $result_email = mysqli_query($conn,$sql_email);

                $sql_mobile = "SELECT mobile_number FROM user WHERE mobile_number='$mobile_number'";
                $result_mobile = mysqli_query($conn,$sql_mobile);

                if(mysqli_num_rows($result_email)>0){
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { sweetAlert("Oops...","Email Address '. $email_address.' already exists!","error");';
                    echo '}, 500);</script>';
                }
                else if(mysqli_num_rows($result_mobile)>0){
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { sweetAlert("Oops...","Mobile number '.$mobile_number.' already exists!","error");';
                    echo '}, 500);</script>';
                }else{
                        $hash_password = md5($password);   

                        $sql = "INSERT INTO user (`username`,`password`,`mobile_number`,`email_address`) VALUES('$username','$hash_password','$mobile_number','$email_address')";

                        $result = mysqli_query($conn,$sql);

                        if(!$result)
                            die("Error while updating!!!...").mysqli_error($conn);
                        else{
                                $_SESSION['username']=$username;	
                                $_SESSION['mobile_number']=$mobile_number;	
                                $_SESSION['email_address']=$email_address;	
                                $_SESSION['password']=$password;
                                echo '<script type="text/javascript">
                                setTimeout(function () { 
                                swal({
                                title:"Success",
                                type:"success",
                                text:"Thank you '.$username.'! Your account has been created successfully. Login using your entered credentials",
                                },function(){window.location = "https://www.google.com/"; });
                                }, 500);
                                </script>';
                            }
                }
            }else{
                    //invalid mobile number error message
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { sweetAlert("Oops...","Mobile number '. $mobile_number.' is invalid!","error");';
                    echo '}, 500);</script>';
                }
        }else{
                //email address invalid error messaage
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { sweetAlert("Oops...","Email address '. $email_address.' is invalid!","error");';
                echo '}, 500);</script>';
        }
    }else{
            //password does not match error 
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { sweetAlert("Oops...","The two passwords does not match!","error");';
            echo '}, 500);</script>';
        }
}

if(isset($_POST['login'])){

    session_start();

    include('connection.php');

    $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $hash_password = md5($password);

    $sql = "SELECT * FROM user WHERE email_address = '$email_address' AND password = '$hash_password' ";

    $result=mysqli_query($conn,$sql);

    if(!$result){
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { sweetAlert("Warning...","Error while loggin in!..","warning");';
        echo '}, 500);</script>';
    }
    else
    {
        $row=mysqli_fetch_array($result);
        $username = $row['username'];
        $count=mysqli_num_rows($result);
        if($count==1)
        {
                    if($row['email_address'] == 'admin@gmail.com' && $row['password'] == 'c12b240b5710c6c9ee00ef4529803aac')
                    {
                        $_SESSION['username']=$username;
                        $_SESSION['email_address'] = $email_address;
                        header('location:admin_page.php');
                    }
                    else
                    {
                        $_SESSION['username']=$username;
                        $_SESSION['email_address'] = $email_address;
                        header('location:profile.php');
                    }
                
        }
        else
        {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { sweetAlert("Oops...","Wrong username or Password!...","error");';
                echo '}, 500);</script>';
        }
    }
    }


?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="KEERTHANA KUTEERA LOGO-BLACK-01.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.11/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.11/dist/sweetalert2.min.js"></script>
    </body>
</html>