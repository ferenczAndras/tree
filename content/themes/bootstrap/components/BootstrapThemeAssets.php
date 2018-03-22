<?php

namespace theme\bootstrap;


use tree\App;
use tree\core\Assets;

class BootstrapThemeAssets extends Assets
{

    public $css = array("");

    public $headScrip = array("");

    public $footerScript = array("");

    public function __construct()
    {
        parent::__construct();

        $this->setTitle("TEST TITLE");

        $this->setBaseUrl(App::getUrl(""));

        // Description of your site, max 150 letter
        $this->setDescription("");
        // Short description of your site's subject
        $this->setSubject("");
        // Very short sentence describing the purpose of the website
        $this->setAbstract("");
        // Describes the topic of the website
        $this->setTopic("");
        // Brief summary of the company or purpose of the website
        $this->setSummary("");
        // Full domain name or web address
        $this->setUrl("");
        // Does the same function as the keywords tag
        $this->setCategory("");
        // Makes sure your website shows up in all countries and languages
        $this->setCoverage("Worldwide"); // THIS IS THE RECOMMENDED value for it
        // Does the same as the coverage tag
        $this->setDistribution("Global"); // this is the RECOMMENDED value for it
        // Gives a general age rating based on sites content
        $this->setRating("General");

        // init the opengraph metas. These are used via facebook
        $this->facebook = $this->facebook
            ->appId("")
            ->url("")
            ->type("website")
            ->title("")
            ->image("")
            ->description("")
            ->siteName("")
            ->locale("en_US")
            ->author("");

        $this->twitter = $this->twitter
            ->summary("")
            ->account("")
            ->creator("")
            ->url("")
            ->title("")
            ->description("")
            ->image("");
    }

}