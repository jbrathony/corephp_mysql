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
            <h1>CUSTOMER PANEL</h1>
            <div class="logout-btn">
                <a href="<?php echo $base_url . "logout.php"; ?>">
                    <i class="fa fa-sign-out" style="font-size:36px"></i>
                </a>
            </div>
        </div>

        <div class="container">


        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="assets/script/alert.js"></script>

</body>

</html>