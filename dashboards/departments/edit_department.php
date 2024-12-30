<?php
$pageTitle = "Edit Department";
include('../header.php');
?>

<h1>Edit Department</h1>
<form action="edit_department.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <label for="department_name">Department Name:</label>
    <input type="text" id="department_name" name="department_name" value="<?php // Fetch current name ?>" required>
    
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php // Fetch current description ?></textarea>
    
    <input type="submit" value="Update Department">
</form>

<?php include('../footer.php'); ?>
