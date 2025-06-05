<?php
    namespace core;

    class Core
    {
        public $defaultLayoutPath = 'views/layouts/index.php';
        public $moduleName;
        public $actionName;
        public $router;
        public $template;
        public $db;
        public $session;
        public Controller $controllerObject;

        private static $instance;

        
        private function __construct()
        {
            $this->template = new \core\Template($this->defaultLayoutPath);
            $host = \core\Config::get()->dbHost;
            $name = \core\Config::get()->dbName;
            $login = \core\Config::get()->dbLogin;
            $password = \core\Config::get()->dbPassword;
            $this->db = new \core\DB($host, $name, $login, $password);
            $this->session = new Session();
            session_start();
        }

        public function run($route)
        {
            $this->router = new \core\Router($route);
            $params = $this->router->run();
            if(!empty($params))
                $this->template->setParams($params);
        }

        public function done()
        {
            $this->template->display();
            $this->router->done();
        }

        public static function get()
        {
            if(empty(self::$instance))
            {
                self::$instance = new Core();
            }
            return self::$instance;
        }
    }
?>