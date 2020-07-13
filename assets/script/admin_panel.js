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

// update category
$(".category").change(function () {
    if (confirm("Are you sure?")) {
        var selectedCategory = $(this).children("option:selected").val();
        var selectedRow = $(this).data("key");
        var submitData = {
            selectedCategory: selectedCategory,
            selectedRow: selectedRow
        }

        callAjax(url, submitData);
    }
});

// update location
$(".location").change(function () {
    if (confirm("Are you sure?")) {
        var selectedLocation = $(this).children("option:selected").val();
        var selectedRow = $(this).data("key");
        var submitData = {
            selectedLocation: selectedLocation,
            selectedRow: selectedRow
        }

        callAjax(url, submitData);
    }
});

// update ordering
$(".order_type").change(function () {
    if (confirm("Are you sure?")) {
        var selectedOrderType = $(this).children("option:selected").val();
        var selectedRow = $(this).data("key");
        var submitData = {
            selectedOrderType: selectedOrderType,
            selectedRow: selectedRow
        }

        callAjax(url, submitData);
    }
});

// update quantity fulfiled
$(".qty_fulfiled").focusout(function () {
    if (confirm("Are you sure?")) {
        var updatedQtyFulfiled = $(this).val();
        var selectedRow = $(this).data("key");
        var submitData = {
            updatedQtyFulfiled: updatedQtyFulfiled,
            selectedRow: selectedRow
        }

        callAjax(url, submitData);
    }
});

// update check_status
$(".check_status").change(function () {
    if (confirm("Are you sure?")) {
        var updatedStatus = 0;

        if (this.checked) {
            updatedStatus = 1;
        } 
        var selectedRow = $(this).data("key");
        var submitData = {
            updatedStatus: updatedStatus,
            selectedRow: selectedRow
        }

        callAjax(url, submitData);
    }
});