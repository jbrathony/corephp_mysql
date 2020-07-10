
function generateUserList(userList) {
    console.log('here is generate user list', userList);
    var htmlString = '';
    userList.map(user => {
        htmlString += '<option value="' + user.id + '">' + user.username + '</option>';
    });
    console.log('html string', htmlString);
    return htmlString;
}

// add user

$('#add_user').click(function () {
    var username = $("#username").val();
    var password = $("#pwd").val();
    var user_access_level = $("#user_access_level").val();

    if (!password || !username) {
        $.alert("Please type all fields", {
            title: "Oops",
            position: ['top-right', [-0.42, 0.01]],
            type: "danger"
        });
        return;
    }

    var url = base_url + "php/add_del_user.php";

    var submitData = {
        username: username,
        password: password,
        user_access_level: user_access_level
    };

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                $.alert(result.message, {
                    title: "Success",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "success"
                });

                // refresh user list
                var htmlString = generateUserList(result.data);
                $("#username_list").children().remove();
                $("#username_list").append(htmlString);
            } else {
                $.alert(result.message, {
                    title: "Oops",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "danger"
                });
            }
        },
        error: function (e) {
            console.log("user register error!", e)
        }
    });

});

// delete user

$('#del_user').click(function () {
    var user_id = $("#username_list").val();

    var url = "http://exceltomysql.com/php/add_del_user.php";
    var submitData = {
        user_id: user_id
    };

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.type === 'success') {
                $.alert(result.message, {
                    title: "Success",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "success"
                });
                // refresh user list
                var htmlString = generateUserList(result.data);
                $("#username_list").children().remove();
                $("#username_list").append(htmlString);
            } else {
                $.alert(result.message, {
                    title: "Oops",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "danger"
                });
            }
        },
        error: function (e) {
            console.log("user delete error!", e)
        }
    });

});