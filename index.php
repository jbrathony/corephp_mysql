<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('./vendor/autoload.php');
require_once('./php/db.php');
require_once('./php/import.php');
require_once('./php/filter.php');

?>


<!DOCTYPE html>
<html>

<head>

  <head>
    <title>Customer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style/custom.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="assets/script/custom.js"></script>
  </head>
</head>

<body>

  <div class="container-fluid">

    <div class="jumbotron text-center">
      <h1>CUSTOMER</h1>
      <p>Import Excel File into MySQL Database & Export Excel from MySQL</p>
    </div>

    <div class="container">

      <div class="form-group text-center border border-warning rounded p-4 mb-5">

        <div style="display: inline-flex;">
          <!-- Import excel -->
          <div>
            <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
              <label>Choose Excel File</label>
              <input type="file" name="file" id="file" accept=".xls,.xlsx">
              <button type="submit" name="import" class="btn btn-primary">Import</button>
            </form>
          </div>

          <!-- Export excel -->
          <div class="ml-3">
            <form action="./export.php" method="post">
              <button type="submit" name="export" class="btn btn-success">Export</button>
            </form>
          </div>
        </div>

        <div id="response" class="<?php if (!empty($type)) {
                                    echo $type . " display-block";
                                  } ?>">
          <?php if (!empty($message)) {
            echo $message;
          } ?>
        </div>


      </div>

      <!-- Filter form -->
      <form class="needs-validation" method="POST" novalidate>
        <div class="input-group mb-5">
          <input type="text" class="form-control" name="filter" placeholder="Search" value="<?php echo $filterParam; ?>" required>
          <div class="input-group-append">
            <button class="btn btn-success" type="submit">Filter</button>
          </div>
          <div class="invalid-feedback">Please fill out this field.</div>
        </div>
      </form>

      <!-- Filter result -->
      <div class="table-responsive filter-result-area">
        <label>Filter Result: </label>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Customer Username</th>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>Customer Note</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (empty($result)) {
              echo "0";
            } else {
            ?>
              <?php
              foreach ($result as $row) {
              ?>
                <tr>
                  <td><?php echo $row['customer_username']; ?></td>
                  <td><?php echo $row['item_name']; ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td><?php echo $row['customer_note']; ?></td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>

</body>

</html>