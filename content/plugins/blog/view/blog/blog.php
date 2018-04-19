<?php if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}
use tree\App as App;
use plugin\blog\model\BlogModel;

?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Blog Posts
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= App::getUrl("") ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Blog Posts</li>
        </ol>
    </section>
    <section class="content">

        <?php if (isset($param['errors'])):
            if (count($param['errors']) > 0):
                ?>

                <div class="callout callout-danger">
                    <?php foreach ($param['errors'] as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>

                <?php
            endif;
        endif; ?>

        <?php if (isset($param['messages'])): ?>
            <div class="callout callout-success">
                <?php foreach ($param['messages'] as $message): ?>
                    <h4><?= $message ?></h4>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List of blog posts</h3><a style="margin-left: 20px;"
                                                                          href="<?= App::getUrl("blog/new") ?>">New
                            post</a>
                    </div>
                    <div class="box-body">
                        <table id="poststable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th> #</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Categories</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (BlogModel::getInstance()->getPosts() as $post): ?>

                                <?php
                                $titles = json_decode($post['title'], true);
                                $title = "";
                                foreach ($titles as $t):
                                    if (strlen($t) > 0)
                                        $title = $t . " - " . $title;
                                endforeach; ?>
                                <tr>
                                    <td>
                                        <a style="margin-right: 10px;"
                                           href="<?= App::getUrl("blog/edit?id=" . base64_encode($post['id']) . "&sec=" . BlogModel::getInstance()->getSecretKey()) . "&b=e" ?>">
                                            <i class="fa fa-edit <?= "text-" . App::app()->get("settings")->color ?>"></i>
                                        </a>
                                        <a href="<?= App::getUrl("blog/delete?id=" . base64_encode($post['id']) . "&sec=" . BlogModel::getInstance()->getSecretKey()) . "&b=e&n=" . $title ?>">
                                            <i class="fa fa-trash <?= "text-" . App::app()->get("settings")->color ?>"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= App::getUrl("blog/edit?id=" . base64_encode($post['id']) . "&sec=" . BlogModel::getInstance()->getSecretKey()) . "&b=e" ?>">
                                            <?= $title ?>
                                        </a>
                                    </td>
                                    <td>
                                        Created <?= \tree\helper\DateUtils::time_elapsed_string("@" . $post['time']) ?>.
                                    </td>
                                    <td><?php
                                        $cats = json_decode($post['category'], true);

                                        if (count($cats) > 0):
                                            foreach ($cats as $category): ?>
                                                <?= $category . " " ?>
                                            <?php endforeach;
                                        endif;
                                        ?>
                                    </td>

                                    <td>
                                        <?= ($post['status'] == "draft") ? "Draft" : "Published" ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th> #</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Categories</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="<?= App::getUrl("assets/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?= App::getUrl("assets/bootstrap/js/bootstrap.min.js") ?>"></script>
<script
    src="<?= App::getUrl("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/slimScroll/jquery.slimscroll.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/fastclick/fastclick.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/dist/js/app.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/datatables/dataTables.bootstrap.min.js") ?>"></script>
<script>
    $(window).load(function () {
        $("#poststable").DataTable();
    });
</script>
