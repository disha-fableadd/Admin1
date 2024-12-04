<?php


include 'layout/header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y">
    <h2 class="text-center my-5"> Change Password</h2>

    <div class="card shadow-lg p-4 mt-4">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="text-white">Change Password</h4>
        </div>
        <div class="card-body mt-5">
            <form id="changePasswordForm">
                <div class="form-group mb-3">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <div id="password_feedback" class="text-danger"></div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>


</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#changePasswordForm').on('submit', function (e) {
            e.preventDefault();
            const formData = {
                current_password: $('#current_password').val(),
                new_password: $('#new_password').val(),
                confirm_password: $('#confirm_password').val(),
            };

            $.ajax({
                url: 'change_password.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#password_feedback').removeClass('text-danger').addClass('text-success').text(response.message);
                        $('#changePasswordForm')[0].reset();
                        setTimeout(function () {
                            window.location.href = 'profile1.php';
                        }, 2000);
                    } else {
                        $('#password_feedback').removeClass('text-success').addClass('text-danger').text(response.message);
                    }
                },
                error: function () {
                    $('#password_feedback').addClass('text-danger').text('An error occurred. Please try again.');
                },
            });
        });
    });
</script>

<?php
// Include footer layout
include 'layout/footer.php';
?>