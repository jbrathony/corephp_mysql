$('#login-btn').click(function () {
    var username = $("#username").val();
    var password = $("#pwd").val();

    if (!password) {
        $.alert("Please type the password", {
            title: "Oops",
            position: ['top-right', [-0.42, 0.01]],
            type: "danger"
        });
        return;
    }

    var url = "http://exceltomysql.com/php/login.php";
    var submitData = {
        username: username,
        password: password
    };

    $.ajax({
        url: url,
        data: submitData,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            console.log('logininfo', result.data)
            if (result.type === 'success') {
                if (result.data === '1') { // admin logged
                    window.location = "admin_navigation.php";
                } else { // customer logged
                    window.location = "customer_navigation.php";
                }
                return false;
            } else {
                $.alert(result.message, {
                    title: "Oops",
                    position: ['top-right', [-0.42, 0.01]],
                    type: "danger"
                });
                // alert(result.message);
            }
        },
        error: function (e) {
            console.log("Couldn't retrieve login information", e)
        }
    });

});