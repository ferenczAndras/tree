<?php use tree\App as App;

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">
<?php
include App::app()->frameworkDir() . "/views/fragments/header.php";
?>
<body>


<?= $content ?>

</body>
<?php if ($includefooter) {
    include App::app()->frameworkDir() . "/views/fragments/footer.php";
} ?>
</html>

