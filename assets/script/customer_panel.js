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

var url = base_url + "/php/customer_panel_backend.php";
var ToBeUpdatedData = [];


// update check_status
$(".check_status").change(function () {
    console.log('asdf')
    var updatedStatus = 0;

    if (this.checked) {
        updatedStatus = 1;
    }
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedStatus,
        selectedRow: selectedRow,
        field: 'check_status',
        save_changes: 1
    }

    callAjax(url, submitData);

});

