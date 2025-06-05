<?php
    namespace core;

    class Files extends RequestMethod
    {
        public function __construct()
        {
            parent::__construct($_FILES);
        }
    }
?>