<?php
    namespace models;

    class Logger extends \core\Model
    {
        public static $tableName = 'logger';

        public static function addLog($status_code, $controller, $action)
        {   
            $logger = new \models\Logger;
            $logger->method = $_SERVER["REQUEST_METHOD"] ?? "UNKNOWN";
            $logger->status_code = $status_code;
            $logger->controller = $controller;
            $logger->action = $action;
            $logger->url = $_SERVER["REQUEST_URI"] ?? "";
            $logger->save();
        }
    }
?>