<?php
require 'db.php'; 

$query = "
    SELECT u.id,ui.uid, ui.fname, ui.lname, ui.profileimage, ui.gender, ui.contact, ui.email, ui.address, u.role
    FROM user_info ui
    INNER JOIN userss u ON ui.user_id = u.id
    ORDER BY user_id DESC
";

$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        echo json_encode(['status' => 'success', 'data' => $users]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No users found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch users: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
