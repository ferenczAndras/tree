<?php
namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use Exception;

/**
 * Class      BaseModel abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class BaseModel extends Object
{


    public $messages = array();

    public $errors = array();

    function __construct()
    {
    }

    public function addMessage($mess)
    {
        $this->messages[] = $mess;
    }

    /*
     * Rules for validation
     * This method must be overwritten!
     */
    public function rules()
    {
        return array();
    }


    public function validateArray($array, $rules = null)
    {
        if ($rules == null) {
            $rules = $this->rules();
        }
        if (is_array($rules)) {
            foreach ($rules as $ruleitem) {
                $attributes = explode(',', $ruleitem['attributes']);
                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);

                    switch ($ruleitem['rule']) {
                        case 'required':
                            if (!isset($array[$attribute]) || empty($array[$attribute])) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (001)";
                            }
                            break;
                        case 'email':
                            if (!filter_var($array[$attribute], FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (002)";
                            }
                            break;
                        case 'regexp':
                            if (preg_match($ruleitem['expression'], $array[$attribute])) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (003)";
                            }
                            break;
                        case 'matches':
                            $field = $ruleitem['field'];
                            if ($array[$attribute] != $array[$field]) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (004)";
                            }
                            break;

                        case 'length':
                            $min = isset($ruleitem['min']) ? $ruleitem['min'] : null;
                            $max = isset($ruleitem['max']) ? $ruleitem['max'] : null;
                            if ($min && $max) {
                                if (strlen($array[$attribute]) < $min || strlen($array[$attribute]) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($min) {
                                if (strlen($array[$attribute]) < $min) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($max) {
                                if (strlen($array[$attribute]) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            }
                            break;
                        case 'exists':
                            $obj = new $ruleitem['class'];
                            if (!$obj->exists($array[$attribute])) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (006)";
                            }
                            break;

                        default:
                            throw new \Exception('Rule "' . $ruleitem['rule'] . '" is not declared in code.');
                            break;
                    }
                }
            }
        }
        return empty($this->errors);
    }


    /**
     * Validates model instance against the definition in rules()
     * @return Boolean
     * @throws Exception
     */
    public function validate()
    {
        $rules = $this->rules();
        if (is_array($rules)) {
            foreach ($rules as $ruleitem) {
                $attributes = explode(',', $ruleitem['attributes']);
                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);

                    switch ($ruleitem['rule']) {
                        case 'required':
                            if (!isset($this->$attribute) || empty($this->$attribute)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (001)";
                            }
                            break;
                        case 'email':
                            if (!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (002)";
                            }
                            break;
                        case 'regexp':
                            if (preg_match($ruleitem['expression'], $this->$attribute)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (003)";
                            }
                            break;
                        case 'matches':
                            $field = $ruleitem['field'];
                            if ($this->$attribute != $this->$field) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (004)";
                            }
                            break;

                        case 'length':
                            $min = isset($ruleitem['min']) ? $ruleitem['min'] : null;
                            $max = isset($ruleitem['max']) ? $ruleitem['max'] : null;
                            if ($min && $max) {
                                if (strlen($this->$attribute) < $min || strlen($this->$attribute) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($min) {
                                if (strlen($this->$attribute) < $min) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($max) {
                                if (strlen($this->$attribute) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            }
                            break;
                        case 'exists':
                            $obj = new $ruleitem['class'];
                            if (!$obj->exists($this->$attribute)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (006)";
                            }
                            break;

                        default:
                            throw new \Exception('Rule "' . $ruleitem['rule'] . '" is not declared in code.');
                            break;
                    }
                }
            }
        }
        return empty($this->errors);
    }

    /*
     * Method for checking if something exists
     * Must be overwritten !
     */
    public function exists($value)
    {
        return false;
    }


    /**
     * Generates a string from the name array of the model
     * @param $titles
     * @return string
     */
    public function generateName($titles)
    {
        $title = "";
        foreach ($titles as $t):
            if (strlen($t) > 0)
                $title = $t . " - " . $title;
        endforeach;
        return $title;
    }
}