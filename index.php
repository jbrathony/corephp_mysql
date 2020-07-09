<?php

require_once('./php/login.php');

?>


<!DOCTYPE html>
<html>

<head>
    <title>Customer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style/custom.css">
    <link rel="stylesheet" href="assets/style/login.css">
</head>

<body>
    <div class="container">
        <div class="imgcontainer">
            <img src="assets/img/img_avatar2.jpg" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <div class="form-group">
                <label for="uname"><b>Username</b></label>
                <select name="username" id="username" class="form-control">
                    <?php
                    foreach ($user_list as $user) { ?>
                        <option value="<?php echo $user['username']; ?>">
                            <?php echo $user['username']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="pwd" id="pwd" required>
            </div>

            <button id="login-btn">Login</button>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="assets/script/alert.js"></script>
    <script src="assets/script/custom.js"></script>
    <script src="assets/script/login.js"></script>

</body>

</html>