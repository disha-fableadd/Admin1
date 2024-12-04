<?php 


include 'db.php'; 

$categoryId = $_POST['id'];

$query = "DELETE FROM category WHERE cid = '$categoryId'";

if (mysqli_query($conn, $query)) {
    echo "Category deleted successfully";
} else {
    echo "Error deleting category: " . mysqli_error($conn);
}



?>