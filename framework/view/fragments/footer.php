<?php use tree\App as App;

if (isset($assets)):
    foreach ($assets->footerScript as $src): ?>
        <script src="<?= App::getUrl($src) ?>"></script>
    <?php endforeach;
endif;
