<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('./vendor/autoload.php'); // TCPDF, Read Excel
require_once('./php/inventory_backend.php');

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
    <link rel="stylesheet" href="assets/style/inventory.css">
</head>

<body>

    <div class="container-fluid">
        <div class="header text-center">
            <h1>MASTER DATABASE OF INVENTORY</h1>
            <div class="toolbar">
                <div>
                    <!-- Back page -->
                    <a href="<?php echo $base_url . "admin_navigation.php"; ?>">
                        <i class="fa fa-arrow-left" style="font-size:36px"></i>
                    </a>
                </div>
                <div style="display: inherit;">
                    <!-- Logout -->
                    <a href="<?php echo $base_url . "logout.php"; ?>">
                        <i class="fa fa-sign-out" style="font-size:36px"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter form -->
        <div class="row" style="justify-content: center;">
            <div class="col-md-4">
                <form class="needs-validation" method="POST" novalidate>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" name="productname_filter" placeholder="SEARCH PRODUCT" value="<?php echo $productname_filter; ?>" required>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">SEARCH</button>
                        </div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <form method="POST">
                    <div class="input-group mb-5">
                        <select class="form-control" name="edited_item" id="edited_item" required>
                            <option value="" selected disabled>
                                APPLY FILTER
                            </option>
                            <?php
                            foreach ($edited_items as $edited_item) {
                                $selected = (isset($_POST['edited_item']) && $_POST['edited_item'] == $edited_item['id']) ? 'selected' : '';
                                echo '<option value="' . $edited_item['id'] . '" ' . $selected . '> ' . $edited_item['item_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">SEARCH</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-2">
                <form method="GET">
                    <div class="input-group mb-5">
                        <button class="btn btn-success" type="submit">ALL PRODUCTS</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Filter result -->
        <div class="row mr-1 ml-1 mb-5">
            <!-- save changes && download as pdf -->
            <div class="col-md-12 dropdown_area mb-1">

                <input type="button" class="btn btn-success mr-1" id="save_changes" value="SAVE CHANGES">

                <input type="button" class="btn btn-success mr-1" data-toggle="collapse" data-target="#add_product_form" id="add_product" value="ADD PRODUCT">

                <form class="form-inline" action="php/export.php" method="POST">
                    <input type="hidden" name="download_inventory_list" value="1">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" id="creating_packing_slip" value="DOWNLOAD INVENTORY LIST">
                    </div>
                </form>

            </div>

            <div class="collapse col-md-12" id="add_product_form">
                <div class="form-group text-center border border-warning rounded p-2 mb-1 mt-3" style="display: inline-flex;">
                    <input type="text" class="form-control mr-3" name="add_product_name" id="add_product_name" placeholder="PRODUCT NAME">
                    <input type="text" class="form-control mr-3" name="add_quantity" id="add_quantity" placeholder="QUANTITY IN HAND">
                    <select class="form-control mr-3" name="add_category" id="add_category">
                        <?php
                        foreach ($category_list as $category) {
                        ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo $category['category_name']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="text" class="form-control mr-3" name="add_cost" id="add_cost" placeholder="COST">
                    <button class="btn btn-success" id="add_product_btn">ADD</button>
                </div>
            </div>



            <div class="table-responsive filter-result-area">
                <table class="table table-hover table-bordered" id="order_table">
                    <thead>
                        <tr>
                            <th style="width: 15%"><input type="text" class="form-control" id="filter0" onkeyup="tblFilter(0)" placeholder="DATE CHANGE MADE"></th>
                            <th style="width: 30%"><input type="text" class="form-control" id="filter1" onkeyup="tblFilter(1)" placeholder="PRODUCT NAME"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter2" onkeyup="tblFilter(2)" placeholder="QUANTITY IN HAND"></th>
                            <th style="width: 15%"><input type="text" class="form-control" id="filter3" onkeyup="tblFilter(3)" placeholder="CATEGORY"></th>
                            <th style="width: 10%"><input type="text" class="form-control" id="filter4" onkeyup="tblFilter(4)" placeholder="COST"></th>
                            <th style="width: 15%">EDIT PRODUCT</th>
                            <th style="width: 5%">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($filter_result)) {
                        ?>
                            <?php
                            foreach ($filter_result as $row) {
                            ?>
                                <tr class="<?php if ($row['edit_flag']) echo "edited" ?>">
                                    <td><?php echo $row['date']; ?></td>
                                    <td>
                                        <input type="text" value="<?php echo $row['item_name']; ?>" class="form-control product_name" data-key="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $row['quantity_in_hand']; ?>" class="form-control quantity" data-key="<?php echo $row['id']; ?>">
                                    </td>
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
                                        <input type="text" value="<?php echo $row['cost']; ?>" class="form-control cost" data-key="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <a class="btn btn-default delete" data-key="<?php echo $row['id']; ?>">DELETE</a>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['edit_flag']) {
                                            echo '<input type="checkbox" class="check_status" data-key="' . $row['id'] . '">';
                                        }
                                        ?>
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
    <script src="assets/script/inventory.js"></script>
    <script>
        // filter by thead
        function tblFilter(index) {
            var input, filter, tr, td, i, txtValue, selectObject, inputObject;
            input = $("#filter" + index).val();
            filter = input.toUpperCase();
            tr = $("#order_table tr");

            // filter in select box
            if (index == 3) {
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
            } else if (index == 1 || index == 2 || index == 4) { // filter in input text
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