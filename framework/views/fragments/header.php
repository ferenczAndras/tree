<?php use tree\App as App; ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php if (isset($assets)): ?>

        <title><?= $assets->getTitle() ?></title>

        <base href="<?= $assets->getBaseUrl() ?>">


        <!-- Allows control over where resources are loaded from -->
        <!--        <meta http-equiv="Content-Security-Policy" content="default-src 'self'">-->
        <!-- Place as early in the document as possible -->
        <!-- Only applies to content below this tag -->

        <!-- Control the behavior of search engine crawling and indexing -->
        <meta name="robots" content="index,follow,noodp"><!-- All Search Engines -->
        <meta name="googlebot" content="index,follow"><!-- Google Specific -->
                                                      <!-- Used to name software used to build the website (i.e. - Wordpress, Dreamweaver) -->
        <meta name="generator" content="tree">


        <?php foreach ($assets->meta as $meta): ?>
            <meta name="<?= $meta['name'] ?>" content="<?= $meta['value'] ?>">
        <?php endforeach; ?>


        <!-- Gives information about an author or another person -->
        <!--        <link rel="me" href="https://google.com/profiles/thenextweb" type="text/html">-->
        <!--        <link rel="me" href="mailto:name@example.com">-->
        <!--        <link rel="me" href="sms:+15035550125">-->


        <!--        <!-- The first, next, previous, and last documents in a series of documents, respectively -->
        <!--        <link rel="first" href="https://example.com/atomFeed.php">-->
        <!--        <link rel="next" href="https://example.com/atomFeed.php?page=4">-->
        <!--        <link rel="previous" href="https://example.com/atomFeed.php?page=2">-->
        <!--        <link rel="last" href="https://example.com/atomFeed.php?page=147">-->

        <!--        <!-- Feeds -->
        <!--        <link rel="alternate" href="https://feeds.feedburner.com/example" type="application/rss+xml" title="RSS">-->
        <!--        <link rel="alternate" href="https://example.com/feed.atom" type="application/atom+xml" title="Atom 0.3">-->


        <?php if (isset($assets->facebook->openGraph)):
            foreach ($assets->facebook->openGraph as $og): ?>
                <meta property="<?= $og['name'] ?>" content="<?= $og['value'] ?>">
            <?php endforeach ?>
        <?php endif; ?>
        <!--- twitter -->


        <?php if (isset($assets->twitter->meta)):
                foreach ($assets->twitter->meta as $og): ?>
                    <meta name="<?= $og['name'] ?>" content="<?= $og['value'] ?>">
            <?php endforeach ?>
        <?php endif; ?>


        <!-- GOOGLE + -->

        <!--        <link href="https://plus.google.com/+YourPage" rel="publisher">-->
        <!--        <meta itemprop="name" content="Content Title">-->
        <!--        <meta itemprop="description" content="Content description less than 200 characters">-->
        <!--        <meta itemprop="image" content="https://example.com/image.jpg">-->
        <!---->

        <!-- Apple iOS -->


        <!--        <!-- Add to Home Screen -->
        <!--        <meta name="apple-mobile-web-app-capable" content="yes">-->
        <!--        <meta name="apple-mobile-web-app-status-bar-style" content="black">-->
        <!--        <meta name="apple-mobile-web-app-title" content="App Title">-->

        <!-- Touch Icons -->
        <!--        <link rel="apple-touch-icon" href="path/to/apple-touch-icon.png">-->
        <!--        <link rel="apple-touch-icon-precomposed" href="path/to/apple-touch-icon-precomposed.png">-->
        <!-- In most cases, one 180×180px touch icon in the head is enough -->
        <!-- If you use art-direction and/or want to have different content for each device, you can add more touch icons -->

        <!-- Startup Image -->
        <!--        <link rel="apple-touch-startup-image" href="path/to/startup.png">-->

        <!-- More info: https://developer.apple.com/safari/library/documentation/appleapplications/reference/safarihtmlref/articles/metatags.html -->


        <!--        Apple Safari-->

        <!-- Pinned Site -->
        <!--        <link rel="mask-icon" href="path/to/icon.svg" color="red">-->

        <!--        <!--        Google Android-->
        <!---->
        <!--        <meta name="theme-color" content="#E64545">-->
        <!---->
        <!--        <!-- Add to homescreen -->
        <!--        <meta name="mobile-web-app-capable" content="yes">-->

        <!---->
        <!--        <!--        App Links-->
        <!---->
        <!--        <!-- iOS -->
        <!--        <meta property="al:ios:url" content="applinks://docs">-->
        <!--        <meta property="al:ios:app_store_id" content="12345">-->
        <!--        <meta property="al:ios:app_name" content="App Links">-->
        <!---->
        <!--        <!-- Android -->
        <!--        <meta property="al:android:url" content="applinks://docs">-->
        <!--        <meta property="al:android:app_name" content="App Links">-->
        <!--        <meta property="al:android:package" content="org.applinks">-->
        <!--        <!-- Web Fallback -->
        <!--        <meta property="al:web:url" content="http://applinks.org/documentation">-->
        <!-- More info: http://applinks.org/documentation/ -->


    <?php foreach ($assets->css as $css): ?>
    <link rel="stylesheet" href="<?= App::app()->getUrl($css) ?>">
    <?php endforeach; ?>

    <?php foreach ($assets->headScrip as $src): ?>
        <script src="<?= App::app()->getUrl($src) ?>"></script>
    <?php endforeach; ?>

    <?php endif; ?>
</head>