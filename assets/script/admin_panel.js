function callAjax(url, submitData) {
    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                $.alert(result.message, {
                    title: "Success!",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "success"
                });
            } else {
                $.alert(result.message, {
                    title: "Oops",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "danger"
                });
            }
        },
        error: function (e) {
            console.log("Couldn't retrieve result", e)
        }
    });
}

var url = base_url + "/php/admin_panel_backend.php";
var ToBeUpdatedData = [];

// update category
$(".category").change(function () {
    var selectedCategory = $(this).children("option:selected").val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: selectedCategory,
        selectedRow: selectedRow,
        field: 'category_id'
    }
    ToBeUpdatedData.push(submitData);
});

// update location
$(".location").change(function () {
    var selectedLocation = $(this).children("option:selected").val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: selectedLocation,
        selectedRow: selectedRow,
        field: 'location_id'
    }

    ToBeUpdatedData.push(submitData);
});

// update ordering
$(".order_type").change(function () {
    var selectedOrderType = $(this).children("option:selected").val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: selectedOrderType,
        selectedRow: selectedRow,
        field: 'order_type'
    }

    ToBeUpdatedData.push(submitData);
});

// update quantity fulfiled
$(".qty_fulfiled").focusout(function () {
    var updatedQtyFulfiled = $(this).val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedQtyFulfiled,
        selectedRow: selectedRow,
        field: 'qty_fulfiled'
    }

    ToBeUpdatedData.push(submitData);
});

// update check_status
$(".check_status").change(function () {
    var updatedStatus = 0;

    if (this.checked) {
        updatedStatus = 1;
    }
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedStatus,
        selectedRow: selectedRow,
        field: 'check_status'
    }

    ToBeUpdatedData.push(submitData);
});

// save changes
$("#save_changes").click(function () {
    console.log('save change clicked', ToBeUpdatedData);
    var submitData = {
        save_changes: ToBeUpdatedData
    };
    callAjax(url, submitData);
});