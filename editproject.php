<?php
include 'layout/header.php';
include 'db.php';

if (!isset($_GET['pid']) || empty($_GET['pid'])) {
    echo "<div class='alert alert-danger'>Project ID is missing!</div>";
    exit; 
}

$project_id = (int) $_GET['pid']; // Ensure project_id is an integer

// Fetch the existing project details
$project = null;
if ($project_id) {
    $projectQuery = "SELECT p.*, c.cname 
                     FROM project p 
                     JOIN category c ON p.category_id = c.cid 
                     WHERE p.pid = $project_id";
    $projectResult = mysqli_query($conn, $projectQuery);

    if ($projectResult && mysqli_num_rows($projectResult) > 0) {
        $project = mysqli_fetch_assoc($projectResult);
    } else {
        echo "<div class='alert alert-danger'>Project not found!</div>";
        exit; 
    }
}

// Fetch categories, managers, and staff for dropdowns
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
                <h3 class="mx-auto">Edit Project</h3>
            </div>
            <div class="card-body">
                <?php if ($project): ?>
                    <form id="editProjectForm" method="POST" enctype="multipart/form-data" action="update_project.php?pid=<?= $project['pid']; ?>">
                    <input type="hidden" name="pid" value="<?= $project['pid']; ?>" />
                        <!-- Project Name -->
                        <div class="mb-3">
                            <label for="project-name" class="form-label">Project Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-project-name" class="input-group-text"><i
                                        class="ri-pencil-line ri-20px"></i></span>
                                <input type="text" class="form-control" id="project-name" name="project_name"
                                       placeholder="Project Name" value="<?= $project['pname'] ?>" required />
                            </div>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php while ($category = mysqli_fetch_assoc($categoryResult)) { ?>
                                    <option value="<?= $category['cid']; ?>" <?= ($category['cid'] == $project['category_id']) ? 'selected' : ''; ?>>
                                        <?= $category['cname']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="project-description" class="form-label">Description</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-description" class="input-group-text"><i
                                        class="ri-edit-box-line ri-20px"></i></span>
                                <textarea id="project-description" name="description" class="form-control"
                                          placeholder="Project Description" required style="height: 100px"><?= $project['description']; ?></textarea>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="mb-3">
                            <label for="project-timeline" class="form-label">Timeline</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-timeline" class="input-group-text"><i
                                        class="ri-calendar-line ri-20px"></i></span>
                                <input type="date" id="project-timeline" name="timeline" class="form-control"
                                       value="<?= $project['timeline']; ?>" required />
                            </div>
                        </div>

                        <div class="row">
                            <!-- Manager Dropdown -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="manager" class="form-label">Manager</label>
                                    <select id="manager" name="manager" class="form-select" required>
                                        <option value="">Select Manager</option>
                                        <?php while ($manager = mysqli_fetch_assoc($managerResult)) { ?>
                                            <option value="<?= $manager['id']; ?>" <?= ($manager['id'] == $project['manager_id']) ? 'selected' : ''; ?>>
                                                <?= $manager['fname'] . ' ' . $manager['lname']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Staff Dropdown -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="staff" class="form-label">Staff</label>
                                    <select id="staff" name="staff" class="form-select" required>
                                        <option value="">Select Staff</option>
                                        <?php while ($staff = mysqli_fetch_assoc($staffResult)) { ?>
                                            <option value="<?= $staff['id']; ?>" <?= ($staff['id'] == $project['staff_id']) ? 'selected' : ''; ?>>
                                                <?= $staff['fname'] . ' ' . $staff['lname']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 mt-3">Update Project</button>
                    </form>
                    <br><br>
                    <div id="message"></div>
                <?php else: ?>
                    <div class="alert alert-danger">Project not found or invalid project ID!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
include 'layout/footer.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        $('#editProjectForm').on('submit', function(event) {
            event.preventDefault(); // Prevent normal form submission

            var formData = new FormData(this); // Prepare the form data

            $.ajax({
                url: 'update_project.php',
                type: 'POST',
                data: formData,
                contentType: false, // Don't set content type manually
                processData: false, // Don't process the data
                success: function(response) {
                    $('#message').html(response); // Show success or error message
                    setTimeout(function () {
                        window.location.href = 'allproject.php';
                    }, 2000);

                },
                error: function() {
                    $('#message').html('An error occurred while updating the project.');
                }
            });
        });
    });
</script>
