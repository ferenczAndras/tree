<?php
namespace tree\email\generator;


use tree\core\Object;

class EmailGenerator extends Object
{
    /**
     * This type is like:
     *
     *   message....
     *
     *  CLICK HERE to do something
     *
     *  end message.
     *  ----
     * If the button is not working
     *
     * @var string
     */
    public static $TYPE_CLICK_HERE = "view_one_button";


    public static $TYPE_MESSAGE_ONLY = "";

    /**
     * Stores wich type of email needs to be send
     * @var string
     */
    private $type;

    /**
     * The title of the message
     * @var string
     */
    private $title;

    /**
     * Link to action
     * @var string
     */
    private $link;

    /**
     * Contains the firs part of the message
     *
     * @var string
     */
    private $message_beginning = "";

    /**
     * End part of the message
     * @var string
     */
    private $message_end = "";


    public function __construct()
    {
        $this->type = self::$TYPE_MESSAGE_ONLY;
    }

    public function setMessageBeginning($message_beginning)
    {
        $this->message_beginning = $message_beginning;
        return $this;
    }

    public function setMessageEnd($message_end)
    {
        $this->message_end = $message_end;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function render()
    {
        $param = array(
            "link" => $this->link,
            "message_beginning" => $this->message_beginning,
            "message_end" => $this->message_end,
            "title" => $this->title
        );
        return $this->renderView($this->type, $param);
    }


    private function renderView($page, $param = NULl)
    {
        ob_start();
        require_once __DIR__ . '/views/' . $page . '.php';
        return ob_get_clean();
    }
}