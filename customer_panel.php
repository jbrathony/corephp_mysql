<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('./vendor/autoload.php'); // TCPDF, Read Excel
require_once('./php/import.php');
require_once('./php/customer_panel_backend.php');

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
    <link rel="stylesheet" href="assets/style/customer_panel.css">
</head>

<body>

    <div class="container-fluid">
        <div class="header text-center">
            <h1>CUSTOMER PANEL</h1>
            <div class="toolbar">
                <div style="display: inherit;">
                    <!-- Logout -->
                    <a href="<?php echo $base_url . "logout.php"; ?>">
                        <i class="fa fa-sign-out" style="font-size:36px"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter form -->
        <div class="row text-center border border-warning rounded p-3 mb-5 mr-1 ml-1">
            <div class="col-md-12">
                <form class="form-inline" method="POST">
                    <div class="form-group col-5">
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
        </div>




        <!-- Filter result -->
        <div class="row mr-1 ml-1 mb-5">
            <!-- save changes && download as pdf -->
            <div class="col-md-12 dropdown_area mb-1">

                <form class="form-inline" method="POST" target="_blank">
                    <input type="hidden" name="create_invoice" value="1">
                    <input type="hidden" name="date_list" value="<?php echo isset($_POST['date_list']) ? $_POST['date_list'] : ''; ?>">
                    <input type="hidden" name="customer_list" value="<?php echo isset($_POST['customer_list']) ? $_POST['customer_list'] : ''; ?>">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success mr-1" id="create_invoice" value="DOWNLOAD INVOICE">
                    </div>
                </form>

                <form class="form-inline" method="POST" target="_blank">
                    <input type="hidden" name="creating_packing_slip" value="1">
                    <input type="hidden" name="date_list" value="<?php echo isset($_POST['date_list']) ? $_POST['date_list'] : ''; ?>">
                    <input type="hidden" name="customer_list" value="<?php echo isset($_POST['customer_list']) ? $_POST['customer_list'] : ''; ?>">
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
                            <th style="width: 18%"><input type="text" class="form-control" id="filter2" onkeyup="tblFilter(2)" placeholder="CUSTOMER NAME"></th>
                            <th style="width: 15%"><input type="text" class="form-control" id="filter3" onkeyup="tblFilter(3)" placeholder="PRODUCT NAME"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter4" onkeyup="tblFilter(4)" placeholder="QUANTITY"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter5" onkeyup="tblFilter(5)" placeholder="CATEGORY"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter6" onkeyup="tblFilter(6)" placeholder="QUANTITY FULFILED"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter7" onkeyup="tblFilter(7)" placeholder="ORDERING"></th>
                            <th style="width: 8%"><input type="text" class="form-control" id="filter8" onkeyup="tblFilter(8)" placeholder="COST"></th>
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
                                    <td><?php echo $row['category_id']; ?></td>
                                    <td><?php echo $row['qty_fulfiled']; ?></td>
                                    <td><?php echo $row['order_type']; ?></td>
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
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                ORDER TOTAL: $12.00
                            </td>
                        </tr>
                    </tfoot>
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
    <script src="assets/script/customer_panel.js"></script>
    <script>
        // filter by thead
        function tblFilter(index) {
            var input, filter, tr, td, i, txtValue, selectObject, inputObject;
            input = $("#filter" + index).val();
            filter = input.toUpperCase();
            tr = $("#order_table tbody tr");

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
    </script>
</body>

</html>