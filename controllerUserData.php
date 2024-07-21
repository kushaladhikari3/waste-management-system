<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "connection.php";
$email = "";
$name = "";
$errors = array();

// If user signup button
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered already exists!";
    }
    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $status = "verified"; // Mark the user as verified by default
        $insert_data = "INSERT INTO usertable (name, email, password, status) 
                        VALUES ('$name', '$email', '$encpass', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if ($data_check) {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header('location: index.html'); // Redirect to main page after signup
            exit();
        } else {
            $errors['db-error'] = "Failed while inserting data into the database!";
        }
    }
}

// If user click login button
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header('location: index.html'); // Redirect to main page after login
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It looks like you're not a member yet! Click on the bottom link to signup.";
    }
}

// If user click change password button
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    } else {
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if ($run_query) {
            $_SESSION['info'] = "Your password changed. Now you can login with your new password.";
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

// If login now button click
if (isset($_POST['login-now'])) {
    header('Location: login-user.php');
}
?>