<?php
use tree\App as App;

if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}
?>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            404 Error Page
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= App::app()->getUrl(""); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">404 error</li>
        </ol>
    </section>

    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

                <p>
                    We could not find the page you were looking for.
                    Meanwhile, you may <a href="<?= App::app()->getUrl("") ?>">return to dashboard</a> or try using
                    the search form.
                </p>

                <form class="search-form" action="<?= App::app()->getUrl("search"); ?>">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" name="submit" value="404" class="btn btn-warning btn-flat"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>