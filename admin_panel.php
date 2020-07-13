<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('./vendor/autoload.php');
require_once('./php/import.php');
require_once('./php/admin_panel_backend.php');

if (!isset($_SESSION)) {
    session_start();
};

if (empty($_SESSION['roleid'])) {
    header("Location: " . $base_url);
}

$base_url = $_ENV["base_url"];

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
    <link rel="stylesheet" href="assets/style/admin_panel.css">
</head>

<body>

    <div class="container-fluid">
        <div class="header text-center">
            <h1>ADMIN PANEL</h1>
            <div class="toolbar">
                <div>
                    <!-- Back page -->
                    <a href="<?php echo $base_url . "admin_navigation.php"; ?>">
                        <i class="fa fa-arrow-left" style="font-size:36px"></i>
                    </a>
                </div>
                <div style="display: inherit;">
                    <!-- Import excel -->
                    <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                        <input type="file" name="file" id="file" accept=".xls,.xlsx" required>
                        <button type="submit" name="import" class="btn btn-success mr-sm-4">Import</button>
                    </form>
                    <!-- Logout -->
                    <a href="<?php echo $base_url . "logout.php"; ?>">
                        <i class="fa fa-sign-out" style="font-size:36px"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- display result message forimporting excel  -->
        <?php if (!empty($type)) { ?>
            <div class="alert alert-<?php echo $type; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php if (!empty($message)) {
                    echo $message;
                } ?>
            </div>
        <?php } ?>

        <!-- Filter form -->
        <div class="row text-center border border-warning rounded p-3 mb-5 mr-1 ml-1">
            <div class="col-md-10">
                <form class="form-inline" method="POST">
                    <div class="form-group col-4">
                        <select class="form-control mr-sm-1" id="date_list" name="date_list" required>
                            <option value="" selected disabled>
                                SELECT DATE
                            </option>
                            <?php
                            foreach ($date_list as $date) {
                            ?>
                                <option value="<?php echo $date['date']; ?>">
                                    <?php echo $date['date']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-5">
                        <select class="form-control mr-sm-1" id="customer_list" name="customer_list" required>
                            <option value="" selected disabled>
                                SELECT CUSTOMER NAME
                            </option>
                            <?php
                            foreach ($customer_name_list as $customer_name) {
                            ?>
                                <option value="<?php echo $customer_name['customer_name']; ?>">
                                    <?php echo $customer_name['customer_name']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-2">
                        <button type="submit" class="btn btn-success" id="filter_date_customername">SEARCH</button>
                    </div>
                </form>
            </div>

            <div class="col-md-2">
                <form method="POST">
                    <input type="hidden" value="1" name="filter_error">
                    <button type="submit" class="btn btn-success" id="filter_error">SEE ERROR</button>
                </form>
            </div>
        </div>


        <!-- Filter result -->
        <div class="row mr-1 ml-1 mb-5">
            <!-- <div class="row download_area"> -->
            <div class="col-md-12 dropdown_area mb-1">
                <button type="button" class="btn btn-success mr-1" id="save_changes">
                    SAVE CHANGES
                </button>
                <button type="button" class="btn btn-success mr-1" id="download_invoice">
                    DOWNLOAD INVOICE
                </button>
                <button type="button" class="btn btn-success" id="download_packing_slip">
                    DOWNLOAD PACKING SLIP
                </button>

            </div>
            <!-- </div> -->

            <div class="table-responsive filter-result-area">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>CUSTOMER NOTE</th>
                            <th>CUSTOMER NAME</th>
                            <th>PRODUCT NAME</th>
                            <th>QUANTITY</th>
                            <th>CATEGORY</th>
                            <th>QUANTITY FULFILED</th>
                            <th>LOCATION</th>
                            <th>ORDERING</th>
                            <th>COST</th>
                            <th>CHECKED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($filter_result)) {
                            //     echo "0";
                            // } else {
                        ?>
                            <?php
                            foreach ($filter_result as $row) {
                            ?>
                                <tr>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['customer_note']; ?></td>
                                    <td><?php echo $row['customer_username']; ?></td>
                                    <td><?php echo $row['item_name']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td>
                                        <select class="form-control category" data-key="<?php echo $row['id']; ?>">
                                            <?php
                                            foreach ($category_list as $category) {
                                            ?>
                                                <option value="<?php echo $category['id']; ?>" <?= $row['category_id'] == $category['id'] ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $category['category_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $row['qty_fulfiled']; ?>" class="form-control qty_fulfiled" data-key="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <select class="form-control location" data-key="<?php echo $row['id']; ?>">
                                            <?php
                                            foreach ($location_list as $location) {
                                            ?>
                                                <option value="<?php echo $location['id']; ?>" <?= $row['location_id'] == $location['id'] ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $location['location']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control order_type" data-key="<?php echo $row['id']; ?>">
                                            <?php
                                            foreach ($order_type_list as $order_type) {
                                            ?>
                                                <option value="<?php echo $order_type['id']; ?>" <?= $row['order_type'] == $order_type['id'] ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $order_type['type']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td>
                                        <input type="checkbox" class="check_status" value="<?php echo $row['check_status']; ?>" data-key="<?php echo $row['id']; ?>" <?php echo ($row['check_status'] == 1 ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
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
    <script src="assets/script/admin_panel.js"></script>

</body>

</html>