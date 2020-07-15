<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('./vendor/autoload.php'); // TCPDF, Read Excel
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
                                $selected = (isset($_POST['date_list']) && $_POST['date_list'] == $date['date']) ? 'selected' : '';
                                echo '<option value="' . $date['date'] . '" ' . $selected . '> ' . $date['date'] . '</option>';
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
                                $selected = (isset($_POST['customer_list']) && $_POST['customer_list'] == $customer_name['customer_name']) ? 'selected' : '';
                                echo '<option value="' . $customer_name['customer_name'] . '" ' . $selected . '> ' . $customer_name['customer_name'] . '</option>';
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
            <!-- save changes && download as pdf -->
            <div class="col-md-12 dropdown_area mb-1">

                <form class="form-inline" method="POST">
                    <div class="form-group">
                        <input type="button" class="btn btn-success mr-1" id="save_changes" value="SAVE CHANGES">
                    </div>
                    <input type="hidden" name="create_invoice" value="1">
                    <input type="hidden" name="date_list" value="<?php echo isset($_POST['date_list']) ? $_POST['date_list'] : ''; ?>">
                    <input type="hidden" name="customer_list" value="<?php echo isset($_POST['customer_list']) ? $_POST['customer_list'] : ''; ?>">
                    <input type="hidden" name="filter_error" value="<?php echo isset($_POST['filter_error']) ? $_POST['filter_error'] : ''; ?>">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success mr-1" id="create_invoice" value="DOWNLOAD INVOICE">
                    </div>
                </form>

                <form class="form-inline" method="POST">
                    <input type="hidden" name="creating_packing_slip" value="1">
                    <input type="hidden" name="date_list" value="<?php echo isset($_POST['date_list']) ? $_POST['date_list'] : ''; ?>">
                    <input type="hidden" name="customer_list" value="<?php echo isset($_POST['customer_list']) ? $_POST['customer_list'] : ''; ?>">
                    <input type="hidden" name="filter_error" value="<?php echo isset($_POST['filter_error']) ? $_POST['filter_error'] : ''; ?>">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" id="creating_packing_slip" value="DOWNLOAD PACKING SLIP">
                    </div>
                </form>

            </div>

            <div class="table-responsive filter-result-area">
                <table class="table table-hover table-bordered" id="order_table">
                    <thead>
                        <tr>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter0" onkeyup="tblFilter(0)" placeholder="DATE"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter1" onkeyup="tblFilter(1)" placeholder="CUSTOMER NOTE"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter2" onkeyup="tblFilter(2)" placeholder="CUSTOMER NAME"></th>
                            <th style="width: 15%"><input type="text" class="form-control" id="filter3" onkeyup="tblFilter(3)" placeholder="PRODUCT NAME"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter4" onkeyup="tblFilter(4)" placeholder="QUANTITY"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter5" onkeyup="tblFilter(5)" placeholder="CATEGORY"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter6" onkeyup="tblFilter(6)" placeholder="QUANTITY FULFILED"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter7" onkeyup="tblFilter(7)" placeholder="LOCATION"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter8" onkeyup="tblFilter(8)" placeholder="ORDERING"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter9" onkeyup="tblFilter(9)" placeholder="COST"></th>
                            <th style="width: 5%">CHECKED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($filter_result)) {
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
                                    <td><?php echo $row['cost']; ?></td>
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
    <script>
        // filter by thead
        function tblFilter(index) {
            var input, filter, tr, td, i, txtValue, selectObject, inputObject;
            input = $("#filter" + index).val();
            filter = input.toUpperCase();
            tr = $("#order_table tr");

            // filter in select box
            if (index == 5 || index == 7 || index == 8) {
                $('#order_table tbody tr').each(function() {
                    td = $(this).find('td:eq(' + index + ')');
                    selectObject = td.find("select option:selected");
                    if (selectObject) {
                        txtValue = selectObject.text();
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            $(this).css("display", "");
                        } else {
                            $(this).css("display", "none");
                        }
                    }
                });
            } else if (index == 6) {
                $('#order_table tbody tr').each(function() {
                    td = $(this).find('td:eq(' + index + ')');
                    inputObject = td.find("input");
                    if (inputObject) {
                        txtValue = inputObject.val();
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            $(this).css("display", "");
                        } else {
                            $(this).css("display", "none");
                        }
                    }
                });
            } else {
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[index];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }

        }
    </script>
</body>

</html>