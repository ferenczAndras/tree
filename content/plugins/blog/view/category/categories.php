<?php if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}
use plugin\blog\model\BlogCategoryModel;
use tree\App as App;
use plugin\blog\model\BlogModel;
use tree\core\L as L;

?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Blog Categories
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= App::getUrl("") ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Blog Categories</li>
        </ol>
    </section>

    <section class="content">

        <?php  $categories = BlogCategoryModel::getInstance()->getCategories();  ?>


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


        <?php if (isset($param['infomessage'])): ?>
            <div class="callout callout-info">
                <h4><i class="icon fa fa-info-circle"></i>
                    <?= $param['infomessage']['title'] ?></h4>

                <p><?= $param['infomessage']['time'] ?></p>
            </div>

        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">About this section</h3>
                    </div>
                    <div class="box-body">
                        <p>This is the control panel for the categories section.
                            Here you can add new or edit a category, delete and see the full list of it.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <?php if (isset($param['edit'])): ?>
                        <div class="box-header">
                            <h3 class="box-title">Edit category</h3>
                        </div>
                        <div class="box-body">
                            <form action="<?= App::getUrl("blogcategories") ?>" method="post">

                                <div class="form-group">
                                    <label>Category name:</label>
                                    <?php $cat = $param['category']; ?>

                                        <input type="text" name="category[<?= L::L()->currentEditIdentifier() ?>]"
                                               class="form-control"
                                               style="margin-bottom: 5px;"
                                               value="<?= $cat[L::L()->currentEditIdentifier()] ?>"
                                               placeholder="Type here...">


                                    <label>Category identifier (the label which is used at public queries like:
                                        blog/category-identifier):</label>
                                    <label>NOTE: WE do not recommend editing the identifier.</label>
                                    <input type="text" name="value" class="form-control"
                                           placeholder="Enter here the identifier of the category... "
                                           value="<?= isset($param['value']) ? $param['value'] : "" ?>">


                                    <input type="hidden" name="sec"
                                           value="<?= BlogCategoryModel::getInstance()->getSecretKey() ?>">

                                    <input type="hidden" name="editcategory" value="true">

                                    <input type="hidden" name="id" value="<?= base64_encode($param['id']) ?>">
                                </div>
                                <div class="form-group">
                                    <input type="submit"
                                           class="btn <?= "bg-" . App::app()->get("settings")->color ?> btn-flat"
                                           value="Save changes"/>
                                </div>
                            </form>
                        </div>

                    <?php else: ?>
                        <div class="box-header">
                            <h3 class="box-title">Create new category</h3>
                        </div>
                        <div class="box-body">
                            <form action="<?= App::getUrl("blogcategories") ?>" method="post">
                                <div class="form-group">
                                    <?php $cat = isset($_POST['category']) ? $_POST['category'] : array(); ?>
                                    <label>Category name:</label>


                                        <input type="text" name="category[<?= L::L()->currentEditIdentifier() ?>]"
                                               class="form-control"
                                               style="margin-bottom: 5px;"
                                               value="<?= isset($cat[L::L()->currentEditIdentifier()]) ? $cat[L::L()->currentEditIdentifier()] : "" ?>"
                                               placeholder="Type here...">


                                    <label>Category identifier (the label which is used at public queries like:
                                        blog/category-identifier):</label>

                                    <input type="text" name="value" class="form-control"
                                           placeholder="Enter here the identifier of the category... "
                                           value="<?= isset($_POST['value']) ? $_POST['value'] : "" ?>">

                                    <input type="hidden" name="sec"
                                           value="<?= BlogCategoryModel::getInstance()->getSecretKey() ?>">
                                    <input type="hidden" name="newcategory" value="true">

                                </div>
                                <div class="form-group">
                                    <input type="submit"
                                           class="btn <?= "bg-" . App::app()->get("settings")->color ?> btn-flat"
                                           value="Create"/>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List of blog categories</h3>
                    </div>
                    <div class="box-body">
                        <table id="categories-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Identifier</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (BlogCategoryModel::getInstance()->getCategories() as $cat): ?>
                                <tr>
                                    <td><?= $cat['id'] ?></td>
                                    <td>
                                        <?php $names = json_decode($cat['name'], true);
                                        foreach ($names as $name): ?>
                                            <?= $name . " | " ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?= $cat['value'] ?></td>
                                    <td>
                                        <a style="margin-right: 10px;"
                                           href="<?= App::getUrl("blogcategories/edit?id=" . base64_encode($cat['id']) . " &k=" . base64_encode($cat['name'])) ?>">
                                            <i class="fa fa-edit <?= "text - " . App::app()->get("settings")->getColor() ?>"></i>
                                        </a>
                                        <a href="<?= App::getUrl("blogcategories/delete?id=" . $cat['id'] . "&k=" . base64_encode($cat['name'])) ?>">
                                            <i class="fa fa-trash <?= "text - " . App::app()->get("settings")->getColor() ?>"></i>
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Identifier</th>
                                <th></th>
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
<script src="<?= App::getUrl("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/slimScroll/jquery.slimscroll.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/fastclick/fastclick.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/dist/js/app.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= App::getUrl("assets/plugins/datatables/dataTables.bootstrap.min.js") ?>"></script>
<script>
    $(window).load(function () {
        $("#categories-table").DataTable();
    });
</script>
