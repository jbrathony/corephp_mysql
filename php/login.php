<?php
if (!isset($_SESSION)) {
    session_start();
};
require_once('db.php');

// Get user list
$user_list_query = "SELECT username FROM users";
$user_list = $mysqli->query($user_list_query);

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $user_info = $mysqli->query($login_query);

    if ($user_info->num_rows > 0) {
        $response['type'] = "success";
        $response['message'] = "Successfully login!";
        while ($row = $user_info->fetch_assoc()) {
            $response['data'] = $row['roleid'];

            // Save to session username, roleid
            $_SESSION['roleid'] = $row['roleid'];
            $_SESSION['username'] = $row['username'];
        }
    } else {
        $response['type']  = "error";
        $response['message'] = "Login credentials are incorrect!";
        $response['data'] = null;
    }

    echo json_encode($response);
}
