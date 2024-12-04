<?php
session_start();
if (!isset($_SESSION['user_id']) ) {
  header('Location: login.php');
  exit();
}
include 'layout/header.php';
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Category Details</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered" id="projectTable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        <!-- Rows will be appended here via AJAX -->
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
    $(document).ready(function () {
        fetchCategories();

        function fetchCategories() {
            $.ajax({
                url: 'fetch_category.php',  
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var rows = '';
                    $.each(data, function (index, category) {
                        rows += '<tr>';
                        rows += '<td>' + (index + 1) + '</td>';
                        rows += '<td>' + category.cname + '</td>';
                        rows += '<td>' + category.description + '</td>';
                        rows += '<td><button class="btn btn-danger delete-btn" data-id="' + category.id + '">Delete</button></td>';
                        rows += '</tr>';
                    });
                    $('#categoryTableBody').html(rows);
                }
            });
        }


        $(document).on('click', '.delete-btn', function () {
            var categoryId = $(this).data('id');
            var row = $(this).closest('tr');  
            
            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: 'delete_category.php', 
                    type: 'POST',
                    data: { id: categoryId },
                    success: function (response) {
                        alert(response);
                        row.remove(); 
                    }
                });
            }
        });
    });
</script>