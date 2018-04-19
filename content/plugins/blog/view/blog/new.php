<?php if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}
use plugin\blog\model\BlogCategoryModel;
use tree\App as App;
use plugin\blog\model\BlogModel;
use tree\core\L as L;


function checked($value)
{
    if (!empty($_POST['Post']['category'])) {
        foreach ($_POST['Post']['category'] as $check) {
            if ($value == $check) return "checked";
        }
    }
    return "";
}

?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            New Blog Post
            <small> Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= App::getUrl("") ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Blog Posts</li>
            <li class="active">New blog post</li>
        </ol>
    </section>
    <section class="content">

        <?php if (isset($param['errors'])): ?>

            <div class="callout callout-danger">
                <h4>Something went wrong!</h4>
                <?php foreach ($param['errors'] as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <?php if (isset($param['messages'])): ?>

            <div class="callout callout-success">
                <?php foreach ($param['messages'] as $message): ?>
                    <h4><?= $message ?></h4>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <div class="callout callout-success">
            <h6>Current Language: <?= L::L()->name( L::L()->currentEditIdentifier()) ?></h6>
        </div>

        <form method="post" action="<?= App::getUrl("blog/new") ?>" >
            <div class="row">
                <div class="col-md-9">
                    <div class="box">
                        <div class="box-body">

                     <div class="tab-pane tabcontent"
                                     id="title-div-<?= L::L()->currentEditIdentifier() ?>">

                                    <div class="form-group">
                                        <label for="title"><?=L::t("Title","blog") ?></label>
                                        <input type="text" name="Post[title][<?= L::L()->currentEditIdentifier() ?>]"
                                               class="form-control"
                                               id="title<?= L::L()->currentEditIdentifier() ?>"
                                               autocomplete="off"
                                               value="<?= isset($_POST['Post']['title'][L::L()->currentEditIdentifier()]) ? $_POST['Post']['title'][L::L()->currentEditIdentifier()] : "" ?>"
                                               placeholder="Enter the title... ">
                                    </div>
                                </div>

                            <hr/>
                            <div class="form-group">
                                <label>The following two field is used to identify the post. Please use english word if
                                    possible. The fields are automatically generated form the title, if exists.
                                    If not, than you have to enter is.<br/>
                                    For example: <?= L::t("site.com","blog") . '/last-nigh-we-went-to-a-trip' ?></label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="fa fa-keyboard-o"></i> <?= L::t("site.com","blog")  . "/" ?> </span>
                                    <input autocomplete="off" type="text" name="Post[url]" id="url_full"
                                           class="form-control"
                                           value="<?= isset($_POST['Post']['url']) ? $_POST['Post']['url'] : "" ?>"
                                           placeholder="Enter post url">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="fa fa-keyboard-o"></i> <?= L::t("site.com","blog")  . "/" ?> </span>
                                    <input autocomplete="off" type="text" name="Post[short_url]" id="url_short"
                                           class="form-control"
                                           value="<?= isset($_POST['Post']['short_url']) ? $_POST['Post']['short_url'] : "" ?>"
                                           placeholder="Enter post short url">
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                    <label for="title">One sentence about the post</label>
                                    <input autocomplete="off" type="text" name="Post[content_one_sentence][<?= L::L()->currentEditIdentifier() ?>]"
                                           id="content_one_sentence"
                                           class="form-control"
                                           value="<?= isset($_POST['Post']['content_one_sentence'][L::L()->currentEditIdentifier()]) ? $_POST['Post']['content_one_sentence'][L::L()->currentEditIdentifier()] : "" ?>"
                                           placeholder="One sentence...">
                            </div>

                            <hr/>
                                <label for="main_content<?= L::L()->currentEditIdentifier() ?>">Main content</label>

                                <textarea id="main_content<?= L::L()->currentEditIdentifier() ?>" name="Post[content][<?= L::L()->currentEditIdentifier() ?>]" rows="80"
                                          style="width: 100%">
                                <?= isset($_POST['Post']['content'][L::L()->currentEditIdentifier()]) ? $_POST['Post']['content'][L::L()->currentEditIdentifier()] : "" ?>
                            </textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-body">
                            <input type="hidden" name="newpost" value="newpost">
                            <div class="form-group"> 
                                <label>Status</label> 
                                <?php
                                $postStatus = isset($_POST['Post']['status']) ? $_POST['Post']['status'] : "";
                                ?>
                                <select name="Post[status]" class="form-control"> 
                                    <option <?= ($postStatus == "published") ? "selected" : "" ?>
                                        value="published">
                                        Published
                                    </option>
                                    <option <?= ($postStatus == "draft") ? "selected" : "" ?> value="draft">
                                        Draft
                                    </option>
                                </select>
                            </div>

                            <input type="hidden" name="sec" value="<?= BlogModel::getInstance()->getSecretKey() ?>">

                            <div class="">
                                <input type="hidden" name="Post[time]" value="<?= time() ?>">
                                <h4>Date: <?= date("Y M d", time()) ?></h4>
                            </div>

                            <hr/>

                            <div class="form-group"> 
                                <label>Categories</label>  <br/>

                                <input type="checkbox" name="Post[category][]" value="unknown" checked> <?= L::t("Category unknown","blog") ?> </input> <br/>

                                <?php
                                foreach (BlogCategoryModel::getCategories() as $cat): ?>

                                    <input type="checkbox" name="Post[category][]" value="<?= $cat['value'] ?>"  <?=checked($cat['value'])?> >

                                        <?php $names = json_decode($cat['name'], true);
                                        foreach ($names as $name): ?>
                                            <?= $name . " | " ?>
                                        <?php endforeach; ?>

                                    </input> <br/>
                                <?php endforeach; ?>

                            </div>

                            <hr/>

                            <input type="submit" value="Publish" autocomplete="off"
                                   class="btn <?= "bg-" . App::app()->get("settings")->getColor() ?> btn-flat">

                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Featured Image</h3>
                        </div>
                        <div id="featuredimagebox" style="" class="box-body">
                            <input type="hidden" name="featuredimage"
                                   value="<?= isset($_POST['featuredimage']) ? $_POST['featuredimage'] : "" ?>"
                                   autocomplete="off" id="featuredimage">

                            <img
                                src="<?= isset($_POST['featuredimage']) ? $_POST['featuredimage'] : App::getUrl("assets/dist/placeholder.png") ?>"
                                id="fimage"
                                style=" border 0px; width: 100%; height: 100%; max-height: 500px;"/>

                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Other settings</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">

                                <p>If you want to secure this post, please type a password here.
                                    Before using this feature, please make sure, that in the front end of the website is
                                    implemented properly.
                                    If you forget it, you simply have to delete the content of the box.</p>
                                <input type="text" name="Post[password]"
                                       value="<?= isset($_POST['Post']['password']) ? $_POST['Post']['password'] : "" ?>"
                                       class="form-control" autocomplete="off"
                                       placeholder="Password...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
<script src="<?= App::getUrl("assets/plugins/ckeditor/ckeditor.js") ?>"></script>
<script>
    $(window).load(function () {

        $("#featuredimagebox").click(function () {

            var iframe = $("<iframe id='filemanager_iframe' class='fm-modal'></iframe>").attr({
                src: "<?=App::getUrl("?page=mediamanager&action=dialog")?>"
                + "&field_name=featuredimage&imgframe=fimage"
            });

            $("body").append(iframe);
//            $("body").css("overflow-y", "hidden");
        });

        window.addEventListener('message', function (event) {
            var element = document.getElementById('filemanager_iframe');
            element.parentNode.removeChild(element);
        }, false);

        function receiveMessage(event) {
            if (event.data == "removetheiframe") {
                var element = document.getElementById('filemanager_iframe');
                element.parentNode.removeChild(element);
                $("body").css("overflow-y", "show");
//                document.getElementById('body').style.overflowY = "show";
            }
        }



        window.addEventListener("message", receiveMessage, false);


        CKEDITOR.replace('main_content<?=L::L()->currentEditIdentifier()?>');

        CKEDITOR.on('dialogDefinition', function (event) {
            var editor = event.editor;
            var dialogDefinition = event.data.definition;
            var dialogName = event.data.name;

            var cleanUpFuncRef = CKEDITOR.tools.addFunction(function () {
                // Do the clean-up of filemanager here (called when an image was selected or cancel was clicked)
                $('#filemanager_iframe').remove();
                $("body").css("overflow-y", "scroll");
            });

            var tabCount = dialogDefinition.contents.length;
            for (var i = 0; i < tabCount; i++) {
                var browseButton = dialogDefinition.contents[i].get('browse');

                if (browseButton !== null) {
                    browseButton.hidden = false;
                    browseButton.onClick = function (dialog, i) {
                        editor._.filebrowserSe = this;
                        var iframe = $("<iframe id='filemanager_iframe' class='fm-modal'/>").attr({
                            src: "<?=App::getUrl("?page=mediamanager&action=dialog")?>" +
                            '&CKEditorFuncNum=' + CKEDITOR.instances[event.editor.name]._.filebrowserFn +
                            '&CKEditorCleanUpFuncNum=' + cleanUpFuncRef +
                            '&langCode=en' +
                            '&CKEditor=' + event.editor.name
                        });

                        $("body").append(iframe);
                        $("body").css("overflow-y", "hidden");  // Get rid of possible scrollbars in containing document
                    }
                }
            }
        });

        CKEDITOR.editorConfig = function (config) {

            config.toolbarGroups = [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                '/',
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'links', groups: ['links']},
                {name: 'insert', groups: ['insert']},
                '/',
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'tools', groups: ['tools']},
                {name: 'others', groups: ['others']},
                {name: 'about', groups: ['about']}
            ];

            config.removeButtons = 'Flash,Smiley,SpecialChar,About';
        };

        $("#title<?=L::L()->currentEditIdentifier()?>").on("change keyup paste", function () {
            var titel = $("#title<?=L::L()->currentEditIdentifier()?>");
            if (titel.val().length > 3) {

                $.ajax({
                    url: "<?= App::getUrl("blog/ajax") ?>",
                    type: "post",
                    data: {
                        title: titel.val(),
                        generateurl: true,
                        sec: "<?= BlogModel::getInstance()->getSecretKey() ?>"
                    },
                    success: function (response) {
                        var array = JSON.parse(response);
                        if (array.length > 1) {
                            var url = $("#url_full");
                            url.val(array[0]);
                            var urlshort = $("#url_short");
                            urlshort.val(array[1]);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });

            }
        })
    });
</script>
