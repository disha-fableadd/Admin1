$(document).ready(function () {
    $("#demo1").hide();
    $("#demo2").hide();
    $("#demo3").hide();
    $("#demo4").hide();
    $("#demo5").hide();
    $("#demo6").hide();
    $("#demo7").hide();
    $("#demo8").hide();
    $("#demo9").hide();
    $("#demo10").hide();


    $('#addUserForm').on('submit', function (e) {
        e.preventDefault();

        var isValid = true;

        function showError(elementId, inputGroup) {
            $(elementId).show();
            $(inputGroup).parent().css("margin-bottom", "0");
            isValid = false;
        }

        function hideError(elementId, inputGroup) {
            $(elementId).hide();
            $(inputGroup).parent().css("margin-bottom", "1rem");
        }


        var fname = $("#fname").val().trim();
        if (fname === "") {
            showError("#demo1", "#fname");
        } else {
            hideError("#demo1", "#fname");
        }

        var lname = $("#lname").val().trim();
        if (lname === "") {
            showError("#demo2", "#lname");
        } else {
            hideError("#demo2", "#lname");
        }

        var gender = $("input[name='gender']:checked").val();
        if (!gender) {
            showError("#demo3", "input[name='gender']");
        } else {
            hideError("#demo3", "input[name='gender']");
        }

        var role = $("#type").val();
        if (role === "") {
            showError("#demo4", "#type");
        } else {
            hideError("#demo4", "#type");
        }

        var email = $("#email").val().trim();
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        if (!emailPattern.test(email)) {
            $("#demo5").text("enter valid email address");
            showError("#demo5", "#email");
        } else {
            hideError("#demo5", "#email");
        }

        var contact = $("#contact").val().trim();
        if (contact === "" || isNaN(contact) || contact.length !== 10) {
            if (contact.length !== 10) {
                showError("#demo6", "#contact", "***Only 10 digits are allowed***");
            } else {
                showError("#demo6", "#contact", "***Contact number is required***");
            }
        } else {
            hideError("#demo6", "#contact");
        }

        var image = $("#image").val();
        var validExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.jfif)$/i;
        if (image === "") {
            showError("#demo7", "#image", "***Profile image is required***");
        } else if (!validExtensions.exec(image)) {
            showError("#demo7", "#image", "***Please upload a valid image file***");
            alert("Allowed formats: .jpg, .jpeg, .png, .gif, .webp, .jfif");
        } else {
            hideError("#demo7", "#image");
        }

        var password = $("#password").val().trim();
        if (password === "") {
            showError("#demo8", "#password", "***Password is required***");
        } else {
            hideError("#demo8", "#password");
        }

        var confirmpassword = $("#confirm-password").val().trim();
        if (confirmpassword === "") {
            showError("#demo9", "#confirm-password", "***Confirm password is required***");
        } else if (confirmpassword !== password) {
            showError("#demo9", "#confirm-password", "***Passwords do not match***");
        } else {
            hideError("#demo9", "#confirm-password");
        }

        var address = $("#address").val().trim();
        if (address === "") {
            showError("#demo10", "#address", "***Address is required***");
        } else {
            hideError("#demo10", "#address");
        }

        if (isValid) {
            var formData = new FormData(this);

            $.ajax({
                url: 'insert_user.php',  
                type: 'POST',
                data: formData,
                contentType: false,  
                processData: false,  
                success: function (response) {
                    if(response === 'success') {
                        $('#message').html('<div class="alert alert-success">User added successfully!</div>');
                        setTimeout(function () {
                            window.location.href = 'alluser.php';
                        }, 2000);
    
                    } else {
                        $('#message').html('<div class="alert alert-danger">Error: ' + response + '</div>');
                    }
                },
                error: function () {
                    $('#message').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                }
            });
        }
    });
});


