<script>
        $(document).ready(function () {
            $("#submitBtn").click(function (e) {
                e.preventDefault(); // Prevent the default form submission

                let isValid = true;

                // Clear previous errors and messages
                $(".error").text("");
                $("#message").html("");

                // Validate email
                const email = $("#email").val().trim();
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!email || !emailPattern.test(email)) {
                    $("#emailError").text("Please enter a valid email address.");
                    isValid = false;
                }

                // Validate password
                const password = $("#password").val().trim();
                if (!password || password.length < 6) {
                    $("#passwordError").text("Password must be at least 6 characters long.");
                    isValid = false;
                }

                // Proceed with AJAX request if input is valid
                if (isValid) {
                    $.ajax({
                        url: 'log.php', // Adjust to the correct script path
                        type: 'POST',
                        data: {
                            email: email,
                            password: password
                        },
                        dataType: 'json', // Expect JSON response from the server
                        success: function (response) {
                            console.log(response);
                            if (response.success === true) {
                                $("#message").html(`<div class="text-success">${response.message}</div>`);
                                // Optionally redirect after successful login
                                setTimeout(function () {
                                    window.location.href = 'dashboard.php';
                                }, 2000); // Redirect after 2 seconds
                            } else {
                                $("#message").html(`<div class="text-danger">${response.message}</div>`);
                            }
                        }
                       
                    });

                }
            });
        });


    </script>
