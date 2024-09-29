<?php





if($_SERVER["REQUEST_METHOD"] == "POST"){

    $componentData = $_SESSION['component_pass_data'];

    $id = $componentData['id'];
    $moduleId = $componentData['module_id'];
    $componentId = $componentData['component_id'];
    $componentName = $componentData['component_name'];
    $componentInstance = $componentData['instance'];
    $componentData = $componentData['component_data'];


?>

<form method="POST" action="process.php">
    <div class="mb-3">
        <label for="component_id" class="form-label">Component ID</label>
        <input type="text" class="form-control" id="component_id" name="component_id" value="<?php echo htmlspecialchars($componentId); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="module_id" class="form-label">Module ID</label>
        <input type="text" class="form-control" id="module_id" name="module_id" value="<?php echo htmlspecialchars($moduleId); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="component_instance" class="form-label">Component Instance</label>
        <input type="text" class="form-control" id="component_instance" name="component_instance" value="<?php echo htmlspecialchars($componentInstance); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="component_data_update" class="form-label">Data</label>
        <input type="text" class="form-control" id="component_data_update" name="component_data_update" value="<?php echo htmlspecialchars($componentData); ?>">
    </div>

    <input type="hidden" name="component_id" value="<?php echo htmlspecialchars($componentId); ?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

    <button name="update" type="submit" class="btn btn-primary">Update</button>
</form>

<?php
}else{
    echo "No components in current module";
}