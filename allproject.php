<?php
session_start();
if (!isset($_SESSION['user_id'])  ) {
  header('Location: login.php');
  exit();
}
include 'layout/header.php';
?>


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Project Details</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered" id="projectTable">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Timeline</th>
                            <th>Manager</th>
                            <th>Staff</th>
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
    function fetchProjects() {
        $.ajax({
            url: 'fetch_projects.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data.length > 0) {
                    var projects = response.data;
                    var html = '';
                    $.each(projects, function (index, project) {
                        html += '<tr data-id="' + project.pid + '">';
                        html += '<td>' + project.project_name + '</td>';
                        html += '<td>' + project.category_name + '</td>';
                        html += '<td>' + project.description + '</td>';
                        html += '<td>' + project.timeline + '</td>';
                        html += '<td>' + project.manager_name + '</td>';
                        html += '<td>' + project.staff_names + '</td>';
                        html += '<td>';
                        html += '<button class="btn btn-sm btn-primary edit-btn"><a href="editproject.php?pid=' + project.pid + '" class="text-white">Edit</a></button> ';
                        html += '<button class="btn btn-sm btn-danger delete-btn" data-id="' + project.pid + '">Delete</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#projectTable tbody').html(html);
                } else {
                    $('#projectTable tbody').html('<tr><td colspan="7" class="text-center">No projects found.</td></tr>');
                }

            },
            error: function (xhr, status, error) {
                console.error('Error fetching projects:', error);
                $('#projectTable tbody').html('<tr><td colspan="6" class="text-center text-danger">An error occurred while fetching projects.</td></tr>');
            }
        });
    }

    $(document).ready(function () {
        fetchProjects();


        $(document).on("click", ".delete-btn", function () {
            const pid = $(this).data("id");

            if (confirm("Are you sure you want to delete this project?")) {
                $.ajax({
                    url: "delete_project.php",
                    type: "POST",
                    data: { pid: pid },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            fetchProjects();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert("An error occurred while trying to delete the project.");
                    }
                });
            }
        });
    });
</script>