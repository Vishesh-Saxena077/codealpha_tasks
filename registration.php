<!-- In secure systems, passwords are hashed using a one-way hashing algorithm like bcrypt, which produces a unique hash for each input password, even if the passwords are identical. 
This is done to protect passwords from being easily discovered in case of a data breach. -->
<?php
session_start();
require_once 'db_con.php';
$msg="";
$err="";
if (isset($_POST['register'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = $connection->query($checkEmailQuery);

    // Check if the hashed password already exists in the database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $checkPasswordQuery = "SELECT * FROM users WHERE pass = '$hashedPassword'";
    $checkPasswordResult = $connection->query($checkPasswordQuery);

    if ($checkResult->num_rows > 0) {
        echo "Email already exists. Please choose a different email.";
    } elseif ($checkPasswordResult->num_rows > 0) {
        echo "Password already exists. Please choose a different password.";
    } else {
        $fname = mysqli_real_escape_string($connection, $_POST['fname']);
        $lname = mysqli_real_escape_string($connection, $_POST['lname']);

        $insert_data = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'pass' => $hashedPassword, // Store the hashed password
        ];

        $cols = implode(',', array_keys($insert_data));
        $vals = implode("','", array_values($insert_data));

        $sql = "INSERT INTO users ($cols) VALUES ('$vals')";

        $insert = $connection->query($sql);

        if ($insert) {
            $msg= "Registration successful!";
        } else {
            $err= "Registration failed.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="d-flex justify-content-center align-items-center flex-column" style="height: 100vh;">
    <form action="#" method="post" enctype="multipart/form-data" autocomplete="off" id="loginForm" class="col-lg-6 p-5 bg-light">
        <div>
            <h1>Registration</h1>
            <hr>
        </div>

        <!-- printing the error or success message -->
        <?php if($msg!= ""){?>
            <div class="alert alert-success" role="alert">
                <?php echo $msg; ?>
            </div>
            <?php    }?>
            <?php if($err!= ""){?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $err; ?>
                </div>
            <?php    }?>
        <div class="mb-3">
            <label for="exampleFormControlInput3" class="form-label fw-bold">Full Name</label>
            <div class="col-12 p-2 gap-2 row">
                <input type="text" name="fname" class="form-control p-3" id="" placeholder="First Name">
                <input type="text" name="lname" class="form-control p-3" id="" placeholder="Last Name">
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label fw-bold">Email</label>
            <input type="email" name="email" class="form-control p-3" id="exampleFormControlInput1" placeholder="name@example.com">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control p-3" id="exampleFormControlInput2" placeholder="Password">
        </div>
        <a href="login.php">Login</a>
        <div class="col-12 d-flex justify-content-center">
            <button type="submit" name="register" class="btn btn-primary p-3 col-4 fw-bold">
                Sign Up
            </button>
        </div>
        </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
