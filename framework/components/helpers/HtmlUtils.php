<?php
namespace tree\helper;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      HtmlUtils
 * @category  Helper Class for Core Html handling
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class HtmlUtils
{

    public static function selectWithLabel($label = null, $values = [], $selected = null, $tag = [])
    {
        $html = '<div class="form-group">';
        $html .= '<label>' . $label . '</label>';
        $html .= self::select($values, $selected, $tag);
        $html .= '</div>';
        return $html;
    }

    public static function checkboxWithLabel()
    {

        return '<div class="checkbox">
                        <label>
                          <input type="checkbox">
                          Checkbox 2
                        </label>
                      </div>';

    }

    public static function select($values = [], $selected = null, $tag = [])
    {


        $html = '<select ' . self::tag($tag) . '>';
        foreach ($values as $val) {
            if ($val['value'] == $selected) {
                $html .= '<option selected value="' . $val['value'] . '">' . $val['title'] . '</option>';
            } else {
                $html .= '<option value="' . $val['value'] . '">' . $val['title'] . '</option>';
            }
        }

        $html .= '</select>';

        return $html;
    }

    /**
     *     ["key" => "name",
     *      "values" => ["value"]
     *      ],
     *      ["key" => "class",
     *      "values" => ["form-control"]
     *      ]
     * ]
     * @param array $data
     * @return string
     */
    public static function tag($data = [])
    {
        $html = "";
        foreach ($data as $tag) {
            $html .= " " . $tag['key'] . '="';

            foreach ($tag['values'] as $value) {
                $html .= "" . $value . " ";
            }

            $html .= '" ';

        }
        return $html;
    }


    public static function getTreeFootprint()
    {
        $html = "<div style='width:100%;margin-top:10px;text-align: center;'>";
        $html .= "Powered by the <strong> Tree Framework</strong><br/>";
        $html .= '<a href="' . TREEURL . '" target="_blank">' . '<img style="height: 75px" src="' . \tree\App::getFrameworkUrl("assets/tree/logo.png") . '" />' . "</a>";
        $html .= "</div>";
        return $html;
    }

}