<?php 
    namespace core;

    class ImgsUploader 
    {
        private $files;
        private $imgs = [];

        public function __construct($files)
        {
            $this->files = $files;
        }

        public function getImgsJson($folder_uuid)
        {
            $this->uploadFile($folder_uuid);
            return json_encode($this->imgs);
        }
        
        private function uploadFile($folder_uuid)
        {
            for($i = 0; $i < count($this->files["name"]); $i++)
            {
                if($this->files['name'][$i] != "")
                {
                    $file_uuid = uniqid();
                    $filename = $this->files['name'][$i];
                    $path = $this->getPath($filename, $file_uuid, $folder_uuid);
                    $this->imgs[] = $path;
                    move_uploaded_file($this->files['tmp_name'][$i], "pics/" . $path);
                }
            }
        }

        private function getPath($filename, $file_uuid, $folder_uuid)
        {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            return $folder_uuid . "/" . $file_uuid . "." . $extension;
        }
    }
?>