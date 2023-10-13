<?php
session_start();
require_once 'db_con.php';

$current_date = new \DateTime();
echo "Current Date and Time : ".$current_date->format('Y-m-d h:i:s');

// Check and update lockout time
$getUserQuery = "SELECT * FROM users";
$userResult = $connection->query($getUserQuery);

while ($user = $userResult->fetch_assoc()) {
    $lockoutTime = new \DateTime($user['lockouttime']);
    $diff = $current_date->diff($lockoutTime);

    // Check if the difference is exactly 24 hours
    if ($diff->days == 1 && $diff->h == 0 && $diff->i == 0 && $diff->s == 0) {
        // If it's exactly 24 hours, reset the lockout time and login attempts
        $resetQuery = "UPDATE users SET lockouttime = '00:00:00', login_attempts = 0 WHERE email = '{$user['email']}'";
        if ($connection->query($resetQuery)) {
            echo "Lockout time reset for user: {$user['email']}<br>";
        } else {
            echo "Error resetting lockout time for user: {$user['email']}<br>";
        }
    }
}

if (isset($_POST['resetButton'])) {
    // Reset login_attempts and lockouttime for all users
    $resetQuery = "UPDATE users SET lockouttime = '00:00:00', login_attempts = 0";
    if ($connection->query($resetQuery)) {
        echo "Lockout times and login attempts reset for all users<br>";
    } else {
        echo "Error resetting lockout times and login attempts: " . mysqli_error($connection) . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lockout Time Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <div class="container">

    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
        <tbody>
            <?php
                $getUserQuery = "SELECT * FROM users WHERE lockouttime != ''";
                $userResult = $connection->query($getUserQuery);

                // Check if there are any results
                if ($userResult->num_rows > 0) {
                    $counter = 1;
                    while ($user = $userResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . $user['email'] . "</td>";
                        echo "<td>" . $user['lockouttime'] . "</td>";
                        echo "<td>" . $user['login_attempts'] . "</td>";
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    <form method="post">
        <input type="submit" name="resetButton" class="btn btn-warning p-3 fw-bold" value="Reset Lockout Times and Login Attempts">
    </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>


<!-- if (isset($_POST['resetButton'])) {
    // Reset login_attempts and lockouttime for all users
    $resetQuery = "UPDATE users SET lockouttime = '00:00:00', login_attempts = 0";
    if ($connection->query($resetQuery)) {
        $insert = 'SELECT * FROM `users` WHERE lockouttime != "" ';
        echo "Lockout times and login attempts reset for all users<br>";
    } else {
        echo "Error resetting lockout times and login attempts: " . mysqli_error($connection) . "<br>";
    }
} -->