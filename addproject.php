<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'layout/header.php';
include 'db.php';



$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($conn, $categoryQuery);


$managerQuery = "SELECT u.id, ui.fname, ui.lname FROM userss u 
                 JOIN user_info ui ON u.id = ui.user_id 
                 WHERE u.role = 2";
$managerResult = mysqli_query($conn, $managerQuery);


$staffQuery = "SELECT u.id, ui.fname, ui.lname FROM userss u 
               JOIN user_info ui ON u.id = ui.user_id 
               WHERE u.role = 3";
$staffResult = mysqli_query($conn, $staffQuery);
?>





<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-9 mx-auto">
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mx-auto">Add Project</h3>
            </div>
            <div class="card-body">
                <form id="addProjectForm" method="POST" enctype="multipart/form-data">
                    <!-- Project Name -->
                    <div class="mb-3">
                        <div class="input-wrapper">
                            <label for="project-name" class="form-label">Project Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-project-name" class="input-group-text"><i
                                        class="ri-pencil-line ri-20px"></i></span>
                                <input type="text" class="form-control" id="project-name" name="project_name"
                                    placeholder="Project Name" />
                            </div>
                            <div class="error-message" style="color:red"></div>
                        </div>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="mb-3">
                        <div class="input-wrapper">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">Select Category</option>
                                <?php while ($category = mysqli_fetch_assoc($categoryResult)) { ?>
                                    <option value="<?= $category['cid']; ?>"><?= $category['cname']; ?></option>
                                <?php } ?>
                            </select>
                            <div class="error-message" style="color:red"></div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <div class="input-wrapper">
                            <label for="project-description" class="form-label">Description</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-description" class="input-group-text"><i
                                        class="ri-edit-box-line ri-20px"></i></span>
                                <textarea id="project-description" name="description" class="form-control"
                                    placeholder="Project Description" style="height: 100px"></textarea>
                            </div>
                            <div class="error-message" style="color:red"></div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-3">
                        <div class="input-wrapper">
                            <label for="project-timeline" class="form-label">Timeline</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-timeline" class="input-group-text"><i
                                        class="ri-calendar-line ri-20px"></i></span>
                                <input type="date" id="project-timeline" name="timeline" class="form-control"
                                    placeholder="Timeline" />
                            </div>
                            <div class="error-message" style="color:red"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Manager Dropdown -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <div class="input-wrapper">
                                    <label for="manager" class="form-label">Manager</label>
                                    <select id="manager" name="manager" class="form-select">
                                        <option value="">Select Manager</option>
                                        <?php while ($manager = mysqli_fetch_assoc($managerResult)) { ?>
                                            <option value="<?= $manager['id']; ?>">
                                                <?= $manager['fname'] . ' ' . $manager['lname']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                
                                <div class="error-message" style="color:red"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Dropdown -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <div class="input-wrapper">
                                    <label for="staff" class="form-label">Staff</label>
                                    <select id="staff" name="staff" class="form-select">
                                        <option value="">Select Staff</option>
                                        <?php while ($staff = mysqli_fetch_assoc($staffResult)) { ?>
                                            <option value="<?= $staff['id']; ?>">
                                                <?= $staff['fname'] . ' ' . $staff['lname']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="error-message" style="color:red"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Add Project</button>
                </form>
                <br><br>
                <div id="message"></div>

            </div>
        </div>
    </div>
</div>


<?php
include 'layout/footer.php';
?>


<script>
    $(document).ready(function () {
        // Function to validate form fields
        function validateForm() {
            let isValid = true;

            // Clear previous error messages
            $(".error-message").text(""); // Clear existing error text

            // Validate Project Name
            const projectName = $("#project-name").val().trim();
            if (projectName === "") {
                $("#project-name").closest(".input-wrapper").find(".error-message").text("Project name is required.");
                isValid = false;
            }

            // Validate Category
            const category = $("#category").val();
            if (category === "") {
                $("#category").closest(".input-wrapper").find(".error-message").text("Please select a category.");
                isValid = false;
            }

            // Validate Description
            const description = $("#project-description").val().trim();
            if (description === "") {
                $("#project-description").closest(".input-wrapper").find(".error-message").text("Description is required.");
                isValid = false;
            }

            // Validate Timeline
            const timeline = $("#project-timeline").val();
            if (timeline === "") {
                $("#project-timeline").closest(".input-wrapper").find(".error-message").text("Timeline is required.");
                isValid = false;
            }

            // Validate Manager
            const manager = $("#manager").val();
            if (manager === "") {
                $("#manager").closest(".input-wrapper").find(".error-message").text("Please select a manager.");
                isValid = false;
            }

            // Validate Staff
            const staff = $("#staff").val();
            if (staff === "") {
                $("#staff").closest(".input-wrapper").find(".error-message").text("Please select staff.");
                isValid = false;
            }

            return isValid;
        }

        // Handle form submission
        $('#addProjectForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            if (validateForm()) {
                var formData = new FormData(this); // Get form data

                $.ajax({
                    url: 'add_project.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            $("#message").html('<div class="alert alert-success">' + response.message + "</div>");

                            setTimeout(function () {
                                window.location.href = 'allproject.php';
                            }, 2000);
                        } else {
                            $("#message").html('<div class="alert alert-danger">' + response.message + "</div>");
                        }
                    },
                    error: function () {
                        $("#message").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    }
                });
            }
        });
    });
</script>