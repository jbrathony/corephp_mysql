// Disable form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


// update EDITED ITEMS list
function updateEditedItemList(edited_items) {
    var optionList = '<option values="" selected disabled> APPLY FILTER </option>';
    edited_items.map(edited_item => {
        optionList += `<option value="${edited_item.id}"> ${edited_item.item_name} </option>`;
    });
    
    $("#edited_item").children().remove();
    $("#edited_item").append(optionList);
}

function updateSaveChanges(url, submitData) {
    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                // updated edited_items_list after success
                console.log('updated result', result);
                updateEditedItemList(result.data);
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

// generate new row for append
function appendNewRow(newData, categoryList) {
    var optionList = '';
    categoryList.map(category => {
        var seletectedFlag = category.id == newData.id ? 'selected="selected"' : '';
        optionList += `<option value="${category.id}" ${seletectedFlag}> ${category.category_name} </option>`;
    });

    var selectCategory = `<select class="form-control category" data-key="${newData.id}">
                        ${optionList}
                    </select>`;

    var newRow = `<tr>
                    <td>${newData.date}</td>
                    <td><input type="text" value="${newData.item_name}" class="form-control product_name" data-key="${newData.id}"></td>
                    <td><input type="text" value="${newData.quantity_in_hand}" class="form-control quantity" data-key="${newData.id}"></td>
                    <td>${selectCategory}</td>
                    <td><input type="text" value="${newData.cost}" class="form-control cost" data-key="${newData.id}"></td>
                    <td><a class="btn btn-default delete" data-key="${newData.id}">DELETE</a></td>
                    <td></td>
                </tr>`;

    $('#order_table tbody').append(newRow);
}


var url = base_url + "/php/inventory_backend.php";
var ToBeUpdatedData = [];

// update product_name
$("body").on("focusout", ".product_name", function () {
    $("#save_changes").fadeIn();
    var updatedProductName = $(this).val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedProductName,
        selectedRow: selectedRow,
        field: 'item_name'
    }

    ToBeUpdatedData.push(submitData);
});

// update quantity in hand
$('#order_table').on('focusout', '.quantity', function () {
    $("#save_changes").fadeIn();
    var updatedQuantity = $(this).val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedQuantity,
        selectedRow: selectedRow,
        field: 'quantity_in_hand'
    }

    ToBeUpdatedData.push(submitData);
});

// update cost
$('#order_table').on('focusout', '.cost', function () {
    $("#save_changes").fadeIn();
    var updatedCost = $(this).val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: updatedCost,
        selectedRow: selectedRow,
        field: 'cost'
    }

    ToBeUpdatedData.push(submitData);
});

// update category
$('#order_table').on('focusout', '.category', function () {
    // $(".category").change(function () {
    $("#save_changes").fadeIn();
    var selectedCategory = $(this).children("option:selected").val();
    var selectedRow = $(this).data("key");
    var submitData = {
        value: selectedCategory,
        selectedRow: selectedRow,
        field: 'category_id'
    }
    ToBeUpdatedData.push(submitData);
});

// save changes
$("#save_changes").click(function () {
    var submitData = {
        save_changes: ToBeUpdatedData
    };
    updateSaveChanges(url, submitData);
    $("#save_changes").fadeOut();
});


/**
 * Delete product
 */
$('body').on('click', '.delete', function () {
    console.log('delete button clicked', selectedRow);

    if (!confirm("Are you sure?")) {
        return;
    }

    var $tr = $(this).closest('tr');
    var selectedRow = $(this).data("key");
    var submitData = {
        delete_product: selectedRow
    };

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                // remove selected table row after call
                $tr.fadeOut();
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

});


/**
 * Add product
 */
$('#add_product_btn').click(function () {
    console.log('add button clicked');

    var product_name = $('#add_product_name').val();
    var quantity = $('#add_quantity').val();
    var category = $('#add_category').val();
    var cost = $('#add_cost').val();

    if (!product_name || !quantity || !category || !cost) {
        $.alert("Please fill out all fields", {
            title: "Oops",
            position: ['top-right', [-0.42, 0.01]],
            type: "danger"
        });
        return;
    }

    var submitData = {
        product_name: product_name,
        quantity: quantity,
        category: category,
        cost: cost,
        product_add: 1
    };

    console.log('product data', submitData);

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                // append added product to table after succeed
                console.log('added result', result);
                var newData = result.data.new_data;
                var categoryList = result.data.category;

                // append new row to the end of table
                appendNewRow(newData, categoryList);

                $.alert(result.message, {
                    title: "Success!",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "success"
                });
                $(".collapse").collapse('hide');
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

});


/**
 * Update EDITED ITEMS
 */
$("body").on("change", ".check_status", function () {
    var checkEditItem = 0;

    if (this.checked) {
        checkEditItem = 1;
    }

    if (!checkEditItem) {
        return;
    }
    var selectedRow = $(this).data("key");

    var submitData = {
        checkEditItem: selectedRow
    };

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                // remove selected table row after call
                updateEditedItemList(result.data);
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
});