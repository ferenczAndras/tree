<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Facebook class for seo and meta usage
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2018 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class FaceBook
{
    public $openGraph = array();


    private function insertMeta($name, $val)
    {
        if (strlen($val) > 0) {
            $this->openGraph[] = ['name' => $name, 'value' => $val];
        }
    }

    public function appId($value)
    {
        $this->insertMeta("fb:app_id", $value);
        return $this;
    }

    public function url($value)
    {
        $this->insertMeta("og:url", $value);
        return $this;
    }

    /**
     * Recommended value : website
     *
     * @param $value
     * @return $this
     */
    public function type($value)
    {
        $this->insertMeta("og:type", $value);
        return $this;
    }

    public function title($value)
    {
        $this->insertMeta("og:title", $value);
        return $this;
    }

    public function image($value)
    {
        $this->insertMeta("og:image", $value);
        return $this;
    }

    public function description($value)
    {
        $this->insertMeta("og:description", $value);
        return $this;
    }

    public function siteName($value)
    {
        $this->insertMeta("og:site_name", $value);
        return $this;
    }

    public function locale($value)
    {
        $this->insertMeta("og:locale", $value);
        return $this;
    }

    public function author($value)
    {
        $this->insertMeta("article:author", $value);
        return $this;
    }


}

/**
 * Class      Twitter abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2018 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Twitter
{

    public $meta = array();


    private function insertMeta($name, $val)
    {
        if (strlen($val) > 0) {
            $this->meta[] = ['name' => $name, 'value' => $val];
        }
    }

    public function summary($val)
    {
        $this->insertMeta("twitter:card", $val);
        return $this;
    }


    public function account($val)
    {
        $this->insertMeta("twitter:site", $val);
        return $this;
    }


    public function creator($val)
    {
        $this->insertMeta("twitter:creator", $val);
        return $this;
    }

    public function url($val)
    {
        $this->insertMeta("twitter:url", $val);
        return $this;
    }

    public function title($val)
    {
        $this->insertMeta("twitter:title", $val);
        return $this;
    }

    public function description($val)
    {
        $this->insertMeta("twitter:description", $val);
        return $this;
    }

    public function image($val)
    {
        $this->insertMeta("twitter:image", $val);
        return $this;
    }
}

/**
 * Class      Meta abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2018 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Meta
{

    private $baseMeta = array();

    public $meta = array();

    /**
     * @var FaceBook
     */
    public $facebook;

    /**
     * @var Twitter
     */
    public $twitter;

    /**
     * Meta constructor.
     */
    public function __construct()
    {
        $this->facebook = new FaceBook();
        $this->twitter = new Twitter();
    }

    public function addMeta($metaName, $metaValue)
    {
        if (strlen($metaValue) > 0) {
            $this->meta[] = [
                'name' => $metaName,
                'value' => $metaValue
            ];
        }
    }


    /**
     * Sets the site rating value
     * @param $rate
     */
    public function setRating($rate)
    {
        $this->addMeta("rating", $rate);
    }

    /**
     * Sets the site coverage.
     *  Recommended value : Global !!!
     * @param $cover
     */
    public function setDistribution($cover)
    {
        $this->addMeta("distribution", $cover);
    }

    /**
     * Sets the site coverage.
     *  Recommended value : Worldwide !!!
     * @param $cover
     */
    public function setCoverage($cover)
    {
        $this->addMeta("coverage", $cover);
    }

    /**
     * Sets the categories for the site
     * @param $cat
     */
    public function setCategory($cat)
    {
        $this->addMeta("category", $cat);
    }

    /**
     * Sets the url param fot the site with http:// and www ...
     * @param $url
     */
    public function setUrl($url)
    {
        $this->addMeta("url", $url);
        $this->addMeta("identifier-URL", $url);
    }

    /**
     * Sets the summary of the site
     * @param $summ
     */
    public function setSummary($summ)
    {
        $this->addMeta("summary", $summ);
    }

    /**
     * Sets the topic of your site
     * @param $topic
     */
    public function setTopic($topic)
    {
        $this->addMeta("topic", $topic);
    }

    /**
     * Sets the abstract for the site
     * @param $abst
     */
    public function setAbstract($abst)
    {
        $this->addMeta("abstract", $abst);
    }

    /**
     * Sets the site subject
     * @param $sub
     */
    public function setSubject($sub)
    {
        $this->addMeta("subject", $sub);
    }

    /**
     * Sets the description field
     * @param $desc
     */
    public function setDescription($desc)
    {
        $this->addMeta("description", $desc);
    }


    /**
     * Sets the base url for the current site.
     * @param string $url
     */
    public function setBaseUrl($url = "/")
    {
        $this->baseMeta['baseUrl'] = $url;
    }

    /**
     * Returns the base url for the current site
     * @return mixed
     */
    public function getBaseUrl()
    {
        return ($this->getBaseKey('baseUrl') == null) ? "/" : $this->getBaseKey('baseUrl');
    }

    /**
     * Sets the page title. It will be used in meta
     * @param $title
     */
    public function setTitle($title)
    {
        $this->baseMeta['title'] = $title;
    }

    /**
     * Returns the site title.
     * @return string | null
     */
    public function getTitle()
    {
        return ($this->getBaseKey('title') == null) ? "" : $this->getBaseKey('title');
    }

    /**
     * Returns a key if exists in base array
     * @param $key
     * @return null
     */
    private function getBaseKey($key)
    {
        if (array_key_exists($key, $this->baseMeta)) {
            return $this->baseMeta[$key];
        } else {
            return null;
        }
    }

}