<?php
namespace tree\filemanager;


use Exception;

abstract class BaseFilemanager
{
    const FILE_TYPE_DIR = 'dir';

    public $config = array();
    protected $language = array();
    protected $get = array();
    protected $post = array();
    protected $logger = false;
    protected $logfile = '';
    protected $fm_path = '';

    /**
     * Default file information template
     * @var array
     */
    protected $defaultInfo = array(
        'Path'      => '',
        'Filename'  => '',
        'File Type' => '',
        'Protected' => 0,
        'Thumbnail' => '',
        'Preview'   => '',
        'Error'     => '',
        'Code'      => 0,
        'Properties' => array(
            'Date Created'  => '',
            'Date Modified' => '',
            'filemtime'     => '',
            'Height'        => 0,
            'Width'         => 0,
            'Size'          => 0
        ),
    );


    public function __construct($extraConfig)
    {
        $this->fm_path = isset($extraConfig['fmPath']) && !empty($extraConfig['fmPath'])
            ? $extraConfig['fmPath']
            : dirname(dirname(dirname(__FILE__)));

        // getting default config file
        $content = file_get_contents($this->fm_path . "/assets/filemanager/scripts/filemanager.config.default.json");
        $config_default = json_decode($content, true);


        // getting user config file
        if(isset($_REQUEST['config'])) {
            $this->getvar('config');
            if (file_exists($this->fm_path . "/assets/filemanager/scripts/" . $_REQUEST['config'])) {
                $this->__log('Loading ' . basename($this->get['config']) . ' config file.');
                $content = file_get_contents($this->fm_path . "/assets/filemanager/scripts/" . basename($this->get['config']));
            } else {
                $this->__log($this->get['config'] . ' config file does not exists.');
                $this->error("Given config file (".basename($this->get['config']).") does not exist !");
            }
        } else {
            $content = file_get_contents($this->fm_path . "/assets/filemanager/scripts/filemanager.config.json");
        }
        $config = json_decode($content, true);

        // Prevent following bug https://github.com/simogeo/Filemanager/issues/398
        $config_default['security']['uploadRestrictions'] = array();

        if(!$config) {
            $this->error("Error parsing the settings file! Please check your JSON syntax.");
        }
        $this->config = array_replace_recursive ($config_default, $config);

        // override config options if needed
        if(!empty($extraConfig)) {
            $this->setup($extraConfig);
        }

        // set logfile path according to system if not set into config file
        if(!isset($this->config['options']['logfile'])) {
            $this->config['options']['logfile'] = sys_get_temp_dir() . '/filemanager.log';
        }

        // Log actions or not?
        if ($this->config['options']['logger'] == true ) {
            if(isset($this->config['options']['logfile'])) {
                $this->logfile = $this->config['options']['logfile'];
            }
            $this->enableLog();
        }
    }

    /**
     * Extend config from file
     * @param array $extraConfig Should be formatted as json config array.
     */
    public function setup($extraConfig)
    {
        // Prevent following bug https://github.com/simogeo/Filemanager/issues/398
        $config_default['security']['uploadRestrictions'] = array();

        $this->config = array_replace_recursive($this->config, $extraConfig);
    }

    /**
     * Returns file info - filemanager action
     * @return array
     */
    abstract function getinfo();

    /**
     * Open specified folder - filemanager action
     * @return array
     */
    abstract function getfolder();

    /**
     * Open and edit file - filemanager action
     * @return array
     */
    abstract function editfile();

    /**
     * Save data to file after editing - filemanager action
     */
    abstract function savefile();

    /**
     * Rename file or folder - filemanager action
     */
    abstract function rename();

    /**
     * Move file or folder - filemanager action
     */
    abstract function move();

    /**
     * Delete existed file or folder - filemanager action
     */
    abstract function delete();

    /**
     * Replace existed file - filemanager action
     */
    abstract function replace();

    /**
     * Upload new file - filemanager action
     */
    abstract function add();

    /**
     * Create new folder - filemanager action
     * @return array
     */
    abstract function addfolder();

    /**
     * Download file - filemanager action
     * @param bool $force Whether to start download after validation
     */
    abstract function download($force);

    /**
     * Returns image file - filemanager action
     * @param bool $thumbnail Whether to generate image thumbnail
     */
    abstract function getimage($thumbnail);

    /**
     * Read file data - filemanager action
     * Intended to read and output file contents when it's not possible to get file by direct URL (e.g. protected file).
     * Initially implemented for viewing audio/video/docs/pdf and other files hosted on AWS S3 remote server.
     * @see S3Filemanager::readfile()
     */
    abstract function readfile();

    /**
     * Retrieves storage summarize info - filemanager action
     * @return array
     */
    abstract function summarize();


    /**
     * Invokes filemanager action based on request params and returns response
     */
    public function handleRequest()
    {
        $response = '';
        global $app;

        if(!isset($_GET)) {
            $this->error($this->lang('INVALID_ACTION'));
        } else {

            if(isset($_GET['mode']) && $_GET['mode']!='') {

                switch($_GET['mode']) {

                    default:
                        $this->error($this->lang('MODE_ERROR'));
                        break;

                    case 'getinfo':
                        if($this->getvar('path')) {
                            $response = $this->getinfo();
                        }
                        break;

                    case 'getfolder':

                        if($this->getvar('path')) {
                            $response = $this->getfolder();
                        }

                        break;

                    case 'rename':
                        if($this->getvar('old') && $this->getvar('new')) {
                            $response = $this->rename();
                            $app::app()->get("activitytracker")->newEditActivity($app::app()->get("login")->getUsername(),"renamed a file/folder", "fname",$response['New Name'], "mediamanager");
                        }
                        break;

                    case 'move':
                        if($this->getvar('old') && $this->getvar('new')) {
                            $response = $this->move();
                            $type = $response['Type'];
                            if($response['Type'] == "dir"){
                                $type = "folder";
                            }
                            $app::app()->get("activitytracker")->newEditActivity($app::app()->get("login")->getUsername(),"moved a ".$type, "fname",$response['New Path'], "mediamanager");

                        }
                        break;

                    case 'editfile':
                        if($this->getvar('path')) {
                            $response = $this->editfile();
                        }
                        break;

                    case 'delete':
                        if($this->getvar('path')) {
                            $response = $this->delete();
                            $app::app()->get("activitytracker")->newDeleteActivity($app::app()->get("login")->getUsername(),"deleted a file/folder", "fname",$response['Path'], "mediamanager");
                        }
                        break;

                    case 'addfolder':
                        if($this->getvar('path') && $this->getvar('name')) {
                            $response = $this->addfolder();
                            $app::app()->get("activitytracker")->newCreateActivity($app::app()->get("login")->getUsername(),"created a new folder", "fname",$response['Name'], "mediamanager");
                        }
                        break;

                    case 'download':
                        if($this->getvar('path')) {
                            $force = isset($_GET['force']);
                            $response = $this->download($force);
                        }
                        break;

                    case 'getimage':
                        if($this->getvar('path')) {
                            $thumbnail = isset($_GET['thumbnail']);
                            $this->getimage($thumbnail);
                        }
                        break;

                    case 'readfile':
                        if($this->getvar('path')) {
                            $this->readfile();
                        }
                        break;

                    case 'summarize':
                        $response = $this->summarize();
                        break;
                }

            } else if(isset($_POST['mode']) && $_POST['mode']!='') {

                switch($_POST['mode']) {

                    default:
                        $this->error($this->lang('MODE_ERROR'));
                        break;

                    case 'add':
                        if($this->postvar('currentpath')) {
                            $this->add();
                        }
                        break;

                    case 'replace':
                        if($this->postvar('newfilepath')) {
                            $this->replace();
                        }
                        break;

                    case 'savefile':
                        if($this->postvar('content', false) && $this->postvar('path')) {
                            $response = $this->savefile();
                        }
                        break;
                }
            }
        }

        echo json_encode($response);
        die();
    }

    /**
     * Echo error message and terminate the application
     * @param $string
     */
    public function error($string)
    {
        $array = array(
            'Error' => $string,
            'Code' => '-1',
            'Properties' => $this->defaultInfo['Properties'],
        );

        $this->__log( __METHOD__ . ' - error message : ' . $string);

        echo json_encode($array);
        die();
    }

    /**
     * Setup language by code
     * @param $string
     * @return string
     */
    public function lang($string)
    {
        if(isset($this->language[$string]) && $this->language[$string]!='') {
            return $this->language[$string];
        } else {
            return 'Language string error on ' . $string;
        }
    }

    /**
     * Write log to file
     * @param string $msg
     */
    protected function __log($msg)
    {
//        if($this->logger == true) {
//            $fp = fopen($this->logfile, "a");
//            $str = "[" . date("d/m/Y h:i:s", time()) . "]#".  $this->get_user_ip() . "#" . $msg;
//            fwrite($fp, $str . PHP_EOL);
//            fclose($fp);
//        }
    }

    public function enableLog($logfile = '')
    {
        $this->logger = true;

        if($logfile != '') {
            $this->logfile = $logfile;
        }

        $this->__log(__METHOD__ . ' - Log enabled (in '. $this->logfile. ' file)');
    }

    public function disableLog()
    {
        $this->logger = false;

        $this->__log(__METHOD__ . ' - Log disabled');
    }

    /**
     * Return user IP address
     * @return mixed
     */
    protected function get_user_ip()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Retrieve data from $_GET global var
     * @param string $var
     * @param bool $sanitize
     * @return bool
     */
    public function getvar($var, $sanitize = true)
    {
        if(!isset($_GET[$var]) || $_GET[$var]=='') {
            $this->error(sprintf($this->lang('INVALID_VAR'),$var));
        } else {
            if($sanitize) {
                $this->get[$var] = $this->sanitize($_GET[$var]);
            } else {
                $this->get[$var] = $_GET[$var];
            }
            return true;
        }
    }

    /**
     * Retrieve data from $_POST global var
     * @param string $var
     * @param bool $sanitize
     * @return bool
     */
    public function postvar($var, $sanitize = true)
    {
        if(!isset($_POST[$var]) || ($var != 'content' && $_POST[$var]=='')) {
            $this->error(sprintf($this->lang('INVALID_VAR'),$var));
        } else {
            if($sanitize) {
                $this->post[$var] = $this->sanitize($_POST[$var]);
            } else {
                $this->post[$var] = $_POST[$var];
            }
            return true;
        }
    }

    /**
     * Retrieve data from $_SERVER global var
     * @param string $var
     * @param string|null $default
     * @return bool
     */
    public function get_server_var($var, $default = null)
    {
        return !isset($_SERVER[$var]) ? $default : $_SERVER[$var];
    }

    /**
     * Sanitize global vars: $_GET, $_POST
     * @param string $var
     * @return mixed|string
     */
    protected function sanitize($var)
    {
        $sanitized = strip_tags($var);
        $sanitized = str_replace('http://', '', $sanitized);
        $sanitized = str_replace('https://', '', $sanitized);
        $sanitized = str_replace('../', '', $sanitized);

        return $sanitized;
    }

    /**
     * Defines real size of file
     * Based on https://github.com/jkuchar/BigFileTools project by Jan Kuchar
     * @param string $path
     * @return int|string
     * @throws Exception
     */
    public static function get_real_filesize($path)
    {
        // This should work for large files on 64bit platforms and for small files everywhere
        $fp = fopen($path, "rb");
        if (!$fp) {
            throw new Exception("Cannot open specified file for reading.");
        }
        $flockResult = flock($fp, LOCK_SH);
        $seekResult = fseek($fp, 0, SEEK_END);
        $position = ftell($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if(!($flockResult === false || $seekResult !== 0 || $position === false)) {
            return sprintf("%u", $position);
        }

        // Try to define file size via CURL if installed
        if (function_exists("curl_init")) {
            $ch = curl_init("file://" . rawurlencode($path));
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            $data = curl_exec($ch);
            curl_close($ch);
            if ($data !== false && preg_match('/Content-Length: (\d+)/', $data, $matches)) {
                return $matches[1];
            }
        }

        return filesize($path);
    }

    /**
     * Check if extension is allowed regarding the security Policy / Restrictions settings
     * @param string $file
     * @return bool
     */
    public function is_allowed_file_type($file)
    {
        $path_parts = pathinfo($file);

        // if there is no extension
        if (!isset($path_parts['extension'])) {
            // we check if no extension file are allowed
            return (bool)$this->config['security']['allowNoExtension'];
        }

        $exts = array_map('strtolower', $this->config['security']['uploadRestrictions']);

        if($this->config['security']['uploadPolicy'] == 'DISALLOW_ALL') {

            if(!in_array(strtolower($path_parts['extension']), $exts))
                return false;
        }
        if($this->config['security']['uploadPolicy'] == 'ALLOW_ALL') {

            if(in_array(strtolower($path_parts['extension']), $exts))
                return false;
        }

        return true;
    }
}