<?php
$pageTitle = "Add Staff";
include('../header.php');
?>

<h1>Add New Staff</h1>
<form action="add_staff.php" method="POST">
    <label for="staff_name">Staff Name:</label>
    <input type="text" id="staff_name" name="staff_name" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <input type="submit" value="Add Staff">
</form>

<?php include('../footer.php'); ?>
