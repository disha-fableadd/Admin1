<?php
session_start();
if (!isset($_SESSION['user_id']) ) {
  header('Location: login.php');
  exit();
}
include 'layout/header.php';
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-9 mx-auto">
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mx-auto">Add User</h3>

            </div>
            <div class="card-body">
                <form id="addUserForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fname" class="input-group-text"><i
                                            class="ri-user-line ri-20px"></i></span>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="First Name" aria-label="First Name"
                                        aria-describedby="basic-icon-default-fname" />
                                </div>
                                <span id="demo1" style="color: red;">Please enter first Name</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-lname" class="input-group-text"><i
                                            class="ri-user-line ri-20px"></i></span>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="Last Name" aria-label="Last Name"
                                        aria-describedby="basic-icon-default-lname" />
                                </div>
                                <span id="demo2" style="color: red;">Please enter last Name</span>

                            </div>
                        </div>
                    </div>

                    <!-- Gender (Radio Buttons) -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <div class="input-group input-group-merge">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-male"
                                            value="male">
                                        <label class="form-check-label" for="gender-male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-female"
                                            value="female">
                                        <label class="form-check-label" for="gender-female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-other"
                                            value="other">
                                        <label class="form-check-label" for="gender-other">Other</label>
                                    </div>
                                </div>
                            </div>
                            <span id="demo3" style="color: red;">Please select gender</span>

                        </div>
                    </div>

                    <!-- Type (Manager or Staff) -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Role</label>
                        <select id="type" name="type" class="form-select">
                            <option value="">Select Role</option>
                            <option value="2">Manager</option>
                            <option value="3">Staff</option>
                        </select>
                        <span id="demo4" style="color: red;">Please select role</span>

                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-email" class="input-group-text"><i
                                    class="ri-mail-line ri-20px"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                                aria-label="Email" aria-describedby="basic-icon-default-email" />
                        </div>
                        <span id="demo5" style="color: red;">Please enter email</span>

                    </div>

                    <!-- Contact Number -->
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-contact" class="input-group-text"><i
                                    class="ri-phone-fill ri-20px"></i></span>
                            <input type="text" id="contact" name="contact" class="form-control"
                                placeholder="Contact Number" aria-label="Contact Number"
                                aria-describedby="basic-icon-default-contact" />
                        </div>
                        <span id="demo6" style="color: red;">Please enter contact number</span>

                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" />
                    </div>
                    <span id="demo7" class="error" style="color:red;">select your image</span>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-password" class="input-group-text">
                                        <i class="ri-lock-line ri-20px"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter Password" aria-label="Password"
                                        aria-describedby="basic-icon-default-password" />
                                </div>
                                <span id="demo8" class="error" style="color:red;">enter your password</span>

                            </div>

                            <!-- Password Confirmation Field -->

                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-confirm-password" class="input-group-text">
                                        <i class="ri-lock-line ri-20px"></i>
                                    </span>
                                    <input type="password" id="confirm-password" name="confirm-password"
                                        class="form-control" placeholder="Confirm Password"
                                        aria-label="Confirm Password"
                                        aria-describedby="basic-icon-default-confirm-password" />
                                </div>
                                <span id="demo9" class="error" style="color:red;">enter your confirm password</span>

                            </div>
                        </div>
                    </div>

                    <!-- Address with Icon -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-address" class="input-group-text"><i
                                    class="ri-map-pin-line ri-20px"></i></span>
                            <textarea id="address" name="address" class="form-control" placeholder="Enter your address"
                                rows="4" aria-label="Address" aria-describedby="basic-icon-default-address"></textarea>
                        </div>
                        <span id="demo10" style="color: red;">Please enter address</span>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Add User</button>
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

<script src="js/user.js"></script>

<!-- <script>
$(document).ready(function () {
    $('#addUserForm').on('submit', function (e) {
        e.preventDefault();

        
    });
});
</script> -->

