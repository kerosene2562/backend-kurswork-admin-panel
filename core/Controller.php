<?php
    namespace core;

    class Controller
    {
        protected $template;
        protected $errorMessages;

        public $isPost = false;
        public $isGet = false;
        public $isFiles = false;
        public $post;
        public $get;
        public $files;
        public $imgsUploader;

        public function __construct()
        {
            $action = Core::get()->actionName;
            $module = Core::get()->moduleName;
            $path = "views/{$module}/{$action}.php";
            $this->template = new Template($path);
            switch($_SERVER['REQUEST_METHOD'])
            {
                case 'POST':
                    $this->isPost = true;
                    break;

                case 'GET':
                    $this->isGet = true;
                    break;
            }
            if(!empty($_FILES['imgs_refs']))
            {
                $this->isFiles = true;
            }
            $this->post = new \core\Post();
            $this->get = new \core\Get();
            $this->files = new \core\Files();
            $this->imgsUploader = new \core\ImgsUploader($this->files->imgs_refs);
            $this->errorMessages = [];
        }

        public function render($pathToView = null)
        {
            if(!empty($pathToView))
                $this->template->setTemplateFilePath($pathToView);
            return ['Content' => $this->template->getHTML()];
        }

        public function redirect($path)
        {
            header("Location: {$path}");
            die;
        }

        public function addErrorMessage($message = null)
        {
            $this->errorMessages [] = $message;
            $this->template->setParam('error_message', implode('<br/>', $this->errorMessages));
        }

        public function clearErrorMessage()
        {
            $this->errorMessages = [];
            return $this->redirect('/lost_admin/admins/login');
        }

        public function isErrorMessagesExist()
        {
            return count($this->errorMessages) > 0;
        }
    }
?>