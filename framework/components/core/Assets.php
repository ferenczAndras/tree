<?php
namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Assets class for the website head template
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Assets extends Meta
{

    public $css = array();

    public $headScrip = array();

    public $footerScript = array();


    public function addCss($css)
    {
        if (empty($css) == false) {
            $this->css[] = $css;
        }
        return $this;
    }

    public function addHeadScript($script)
    {
        $this->headScrip[] = $script;
        return $this;
    }

    public function addFooterScript($script)
    {
        $this->footerScript[] = $script;
        return $this;
    }

}