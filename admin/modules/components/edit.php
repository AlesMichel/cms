<?php
include("../../templates/header.php");
require_once __DIR__ . '/../../../vendor/autoload.php';
use phpCms\Module\module;
use phpCms\Components\Component;
use phpCms\DbConnect\connect;

// Retrieve GET values
$componentId = $_GET['component_id'];
$moduleId = $_GET['module_id'];
$id = $_GET['id'];
$instance = $_GET['instance'];
$data = $_GET['component_data']

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
        <input type="text" class="form-control" id="component_instance" name="component_instance" value="<?php echo htmlspecialchars($instance); ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="component_data" class="form-label">Data</label>
        <input type="text" class="form-control" id="component_data" name="component_data" value="<?php echo htmlspecialchars($data); ?>">
    </div>

    <input type="hidden" name="component_id" value="<?php echo htmlspecialchars($componentId); ?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

    <button name="update" type="submit" class="btn btn-primary">Update</button>
</form>
