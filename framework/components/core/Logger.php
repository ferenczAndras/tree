<?php
namespace tree\core;


class Logger
{

    /**
     * Error message level. An error message is one that indicates the abnormal termination of the
     * application and may require developer's handling.
     */
    const LEVEL_ERROR = 0x01;
    /**
     * Warning message level. A warning message is one that indicates some abnormal happens but
     * the application is able to continue to run. Developers should pay attention to this message.
     */
    const LEVEL_WARNING = 0x02;
    /**
     * Informational message level. An informational message is one that includes certain information
     * for developers to review.
     */
    const LEVEL_INFO = 0x04;
    /**
     * Tracing message level. An tracing message is one that reveals the code execution flow.
     */
    const LEVEL_TRACE = 0x08;
    /**
     * Profiling message level. This indicates the message is for profiling purpose.
     */
    const LEVEL_PROFILE = 0x40;
    /**
     * Profiling message level. This indicates the message is for profiling purpose. It marks the
     * beginning of a profiling block.
     */
    const LEVEL_PROFILE_BEGIN = 0x50;
    /**
     * Profiling message level. This indicates the message is for profiling purpose. It marks the
     * end of a profiling block.
     */
    const LEVEL_PROFILE_END = 0x60;

    /**
     * @var $_logger Logger holds the current logger object
     */
    private static $_logger;

    /**
     * Logger constructor.
     */
    public function __construct()
    {
        self::setLogger($this);
    }

    /**
     * @return Logger message logger
     */
    public static function getLogger()
    {
        if (self::$_logger !== null) {
            return self::$_logger;
        } else {
            return self::$_logger = new Logger();
        }
    }

    /**
     * Sets the logger object.
     * @param Logger $logger the logger object.
     */
    public static function setLogger($logger)
    {
        self::$_logger = $logger;
    }

    /**
     * Logs a trace message.
     * Trace messages are logged mainly for development purpose to see
     * the execution work flow of some code.
     * @param string $message the message to be logged.
     * @param string $category the category of the message.
     */
    public static function trace($message, $category = 'application')
    {
        if (self::isDebug()) {
            self::getLogger()->log($message, Logger::LEVEL_TRACE, $category);
        }
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string $message the message to be logged.
     * @param string $category the category of the message.
     */
    public static function error($message, $category = 'application')
    {
        if (self::isDebug()) {
            static::getLogger()->log($message, Logger::LEVEL_ERROR, $category);
        }
    }

    /**
     * Logs a warning message.
     * A warning message is typically logged when an error occurs while the execution
     * can still continue.
     * @param string $message the message to be logged.
     * @param string $category the category of the message.
     */
    public static function warning($message, $category = 'application')
    {
        if (self::isDebug()) {
            static::getLogger()->log($message, Logger::LEVEL_WARNING, $category);
        }
    }

    /**
     * Logs an informative message.
     * An informative message is typically logged by an application to keep record of
     * something important (e.g. an administrator logs in).
     * @param string $message the message to be logged.
     * @param string $category the category of the message.
     */
    public static function info($message, $category = 'application')
    {
        if (self::isDebug()) {
            static::getLogger()->log($message, Logger::LEVEL_INFO, $category);
        }
    }

    /**
     * Marks the beginning of a code block for profiling.
     * This has to be matched with a call to [[endProfile]] with the same category name.
     * The begin- and end- calls must also be properly nested. For example,
     *
     * ~~~
     * \Yii::beginProfile('block1');
     * // some code to be profiled
     *     \Yii::beginProfile('block2');
     *     // some other code to be profiled
     *     \Yii::endProfile('block2');
     * \Yii::endProfile('block1');
     * ~~~
     * @param string $token token for the code block
     * @param string $category the category of this log message
     * @see endProfile()
     */
    public static function beginProfile($token, $category = 'application')
    {
        if (self::isDebug()) {
            static::getLogger()->log($token, Logger::LEVEL_PROFILE_BEGIN, $category);
        }
    }

    /**
     * Marks the end of a code block for profiling.
     * This has to be matched with a previous call to [[beginProfile]] with the same category name.
     * @param string $token token for the code block
     * @param string $category the category of this log message
     * @see beginProfile()
     */
    public static function endProfile($token, $category = 'application')
    {
        if (self::isDebug()) {
            static::getLogger()->log($token, Logger::LEVEL_PROFILE_END, $category);
        }
    }

    /**
     * Returns an HTML hyperlink that can be displayed on your Web page showing "Powered by Yii Framework" information.
     * @return string an HTML hyperlink that can be displayed on your Web page showing "Powered by Yii Framework" information
     */
    public static function powered()
    {
        return 'Powered by <a href="http://www.treeframework.affarit.com/" rel="external">Tree Framework</a>';
    }


    public function log($message, $level, $category = 'application')
    {
//        $time = microtime(true);
//        $traces = [];
//        if ($this->traceLevel > 0) {
//            $count = 0;
//            $ts = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
//            array_pop($ts); // remove the last trace since it would be the entry script, not very useful
//            foreach ($ts as $trace) {
//                if (isset($trace['file'], $trace['line']) && strpos($trace['file'], YII2_PATH) !== 0) {
//                    unset($trace['object'], $trace['args']);
//                    $traces[] = $trace;
//                    if (++$count >= $this->traceLevel) {
//                        break;
//                    }
//                }
//            }
//        }
//        $this->messages[] = [$message, $level, $category, $time, $traces];
//        if ($this->flushInterval > 0 && count($this->messages) >= $this->flushInterval) {
//            $this->flush();
//        }
    }

    /**
     * @return bool value if there is debug or not
     */
    public static function isDebug()
    {
        return defined("TREE_DEBUG") ? TREE_DEBUG : false;
    }


}