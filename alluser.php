<?php

include 'layout/header.php';
?>


<div class="container-xxl flex-grow-1 container-p-y  ">
    <div class="card ">
        <h5 class="card-header">Manager Details</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered" id="userTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Role</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Image</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>



            </div>
        </div>
    </div>
</div>





<?php

include 'layout/footer.php';
?>


<script>
    $.ajax({
        url: 'fetch_users.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response); 
            if (response.status === 'success') {
                var users = response.data;
                var html = '';

                $.each(users, function (index, user) {
                    html += '<tr>';
                    html += '<td>' + user.fname + '</td>';
                    html += '<td>' + (user.role == 1 ? 'Admin' : (user.role == 2 ? 'Manager' : 'Staff')) + '</td>';
                    html += '<td>' + user.gender + '</td>';
                    html += '<td>' + user.email + '</td>';
                    html += '<td>' + user.contact + '</td>';
                    html += '<td><img src="uploads/' + (user.profileimage ? user.profileimage : 'default.png') + '" alt="' + user.fname + '\'s Image" class="img-thumbnail" style="width:50px; height:50px; border-radius:50%"></td>';
                    html += '<td>' + user.address + '</td>';
                    html += '<td><button class="btn btn-sm btn-primary edit-btn"><a href="edituser.php?id=' + user.uid + '" class="text-white">Edit</a></button> <button class="btn btn-sm btn-danger delete-btn" data-id="' + user.id + '">Delete</button></td>';
                    html += '</tr>';
                });

                $('#userTable tbody').html(html); 
            } else {
                $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function (xhr, status, error) {
            $('#responseMessage').html('<div class="alert alert-danger">An error occurred: ' + error + '</div>');
        }
    });

    $(document).on("click", ".delete-btn", function () {
    const userid = $(this).data("id"); 
    // Confirm deletion
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            url: "delete_user.php", 
            type: "POST",
            data: { userid: userid },  
            dataType: "json",  
            success: function (response) {
                if (response.success) {
                    location.reload();  
                } else {
                    alert(response.message);  
                }
            },
            error: function () {
                alert("An error occurred while trying to delete the user.");
            }
        });
    }
});


</script>