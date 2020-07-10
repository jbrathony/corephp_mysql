<?php

require_once('./php/envionment.php');
require_once('./php/db.php');

$base_url = $_ENV["base_url"];

if (!isset($_SESSION)) {
    session_start();
};

if (empty($_SESSION['roleid'])) {
    header("Location: " . $base_url);
}

function resultToArray($result)
{
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

// get user access level
$get_user_access_level_query = "SELECT * FROM userrole";
$user_access_level_result = $mysqli->query($get_user_access_level_query);
$user_access_levels = resultToArray($user_access_level_result);


// get users
$get_user_query = "SELECT * FROM users";
$users_result = $mysqli->query($get_user_query);
$users = resultToArray($users_result);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/style/custom.css">
    <link rel="stylesheet" href="assets/style/admin_navigation.css">
</head>

<body>

    <div class="container-fluid">

        <div class="jumbotron text-center">
            <h1>ADMIN NAVIGATION</h1>
            <div class="logout-btn">
                <a href="<?php echo $base_url . "logout.php"; ?>">
                    <i class="fa fa-sign-out" style="font-size:36px"></i>
                </a>
            </div>
        </div>

        <div class="container">

            <div class="row">
                <div class="col-md-3">
                    <div class="sidenav">
                        <a href="<?php echo $base_url . "admin_navigation.php"; ?>">
                            ADMIN PANEL
                        </a>
                        <a href="#inventory_file">
                            INVENTORY FILE
                        </a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control mb-5 mr-sm-4" id="username" placeholder="USERNAME">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mb-5 mr-sm-4" id="pwd" placeholder="PASSWORD">
                            </div>
                            <div class="form-group">
                                <select class="form-control mb-5 mr-sm-4" id="user_access_level">
                                    <?php
                                    foreach ($user_access_levels as $user_access_level) {
                                    ?>
                                        <option value="<?php echo $user_access_level['id']; ?>">
                                            <?php echo $user_access_level['role_name']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="button" class="btn btn-success mb-5" id="add_user">ADD USER</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-inline">
                            <div class="form-group">
                                <select class="form-control mb-2 mr-sm-4" id="username_list">
                                    <?php
                                    foreach ($users as $user) {
                                    ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo $user['username']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success mb-2" id="del_user">DELETE USER</button>
                        </div>
                    </div>

                </div>
            </div>



        </div>
    </div>

    <script>
        var base_url = '<?php echo $base_url; ?>';
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="assets/script/alert.js"></script>
    <script src="assets/script/add_del_user.js"></script>

</body>

</html>