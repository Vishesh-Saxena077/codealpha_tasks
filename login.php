<?php
session_start();
require_once 'db_con.php';

$err1 = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Retrieve the user's data, including login_attempts
    $getUserQuery = "SELECT * FROM users WHERE email = '$email'";
    $userResult = $connection->query($getUserQuery);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();

        // Check if the columns exist in the fetched data
        if (isset($user['login_attempts'])) {
   
            $hashedPassword = $user['pass'];
                
            if (password_verify($password, $hashedPassword)) {
                // Password is correct
                if ($user['login_attempts'] < 4) {
                    // Successful login with zero login attempts
                    // echo "Login successful!";
                    $resetQuery = "UPDATE users SET lockouttime = '00:00:00', login_attempts = 0 WHERE email = '{$user['email']}'";
                    $connection->query($resetQuery);
                    $_SESSION['user_data'] = "true";
                    header('Refresh: 1; url=index.php');
                } else {
                    // Successful password but login attempts not zero
                    $err1 = '<div class="alert alert-danger" role="alert"> Too many failed login attempts. Try again after 24 hours </div>';
                }

            } else {
                // Password is incorrect, increment login attempts
                $loginAttempts = $user['login_attempts'] + 1;

                if ($loginAttempts >= 5) {
                    $lockouttime = date("Y-m-d H:i:s"); // Use this format for MySQL DATETIME
                    $updateAttemptsQuery = "UPDATE users SET lockouttime = '$lockouttime' WHERE email = '$email'";
                    
                    // Execute the update query
                    if (mysqli_query($connection, $updateAttemptsQuery)) {
                        $err1 = '<div class="alert alert-danger" role="alert"> Too many failed login attempts. Try again after 24 hours </div>';
                        header('Refresh: 5; url=login.php');
                    } else {
                        echo "Error updating lockout time: " . mysqli_error($connection);
                    }
                } else {
                    // Update login attempts without locking the account
                    $updateAttemptsQuery = "UPDATE users SET login_attempts = $loginAttempts WHERE email = '$email'";
                    $connection->query($updateAttemptsQuery);

                    $err1 = '<div class="alert alert-danger" role="alert"> Incorrect password. Please try again. </div>';
                }
            }
        } else {
            // The columns are missing in the database
            $err1 = '<div class="alert alert-danger" role="alert"> Database configuration issue. Please contact the administrator. </div>';
        }
    } else {
        $err1 = '<div class="alert alert-danger" role="alert"> User not found. Please check your email. </div>';
    }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <form action="#" method="post" enctype="multipart/form-data" autocomplete="off" id="loginForm" class="col-lg-6 p-5 bg-light">
        <div>
            <h1>Login</h1>
            <hr>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control p-3" id="exampleFormControlInput1" placeholder="name@example.com">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control p-3" id="exampleFormControlInput2" placeholder="Password">
        </div>
        <a href="registration.php">Registration</a>
        <div>
            <?php echo $err1; ?>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <button type="submit" name="login" class="btn btn-primary p-3 col-4 fw-bold">
                Login
            </button>
        </div>
        </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
