<?php

require_once('db.php');

// add user

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_access_level = $_POST['user_access_level'];

    $add_user_query = "INSERT INTO users (username, password, roleid) VALUES ('$username', '$password', '$user_access_level')";
    $insert_result = $mysqli->query($add_user_query);

    if ($insert_result === TRUE) {
        $response['type'] = "success";
        $response['message'] = "Registered successfully!";
        $response['data'] = [];

        // Get user list
        $user_list_query = "SELECT id, username FROM users";
        $user_list = $mysqli->query($user_list_query);

        while ($row = $user_list->fetch_assoc()) {
            $user_data['id'] = $row['id'];
            $user_data['username'] = $row['username'];

            array_push($response['data'], $user_data);
        }
    } else {
        $response['type']  = "error";
        $response['message'] = "Username is already exist!";
        $response['data'] = null;
    }

    echo json_encode($response);
}


// delete user

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $user_del_query = "DELETE FROM users WHERE id='$user_id'";
    $delete_result = $mysqli->query($user_del_query);

    if ($delete_result === TRUE) {
        $response['type'] = "success";
        $response['message'] = "User deleted successfully!";
        $response['data'] = [];

        // Get user list
        $user_list_query = "SELECT id, username FROM users";
        $user_list = $mysqli->query($user_list_query);
        while ($row = $user_list->fetch_assoc()) {
            $user_data['id'] = $row['id'];
            $user_data['username'] = $row['username'];

            array_push($response['data'], $user_data);
        }
    } else {
        $response['type']  = "error";
        $response['message'] = "User delete error!";
        $response['data'] = null;
    }
    echo json_encode($response);
}
