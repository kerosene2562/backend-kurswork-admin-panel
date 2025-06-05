<?php
    namespace controllers;

    class AdminsController extends \core\Controller
    {
        public function actionLogin()
        {
            if(\models\Admins::IsAdminLogged())
            {
                return $this->redirect('/lost_admin/admins/index');
            }
            if($this->isPost)
            {
                $admin = \models\Admins::FindByLoginAndPassword($this->post->login, $this->post->password);
                if(!empty($admin))
                {
                    \models\Admins::LoginAdmin($admin);
                    return $this->redirect('/lost_admin/admins/index');
                }
                else
                {
                    $this->setErrorMessage('неправильний логін або пароль!');
                }
            }
            return $this->render();
        }

        public function actionRegister()
        {
            if($this->isPost)
            {
                $admin = \models\Admins::FindByLogin($this->post->login);

                if(!empty($admin))
                {
                    $this->addErrorMessage('користувач з таким логіном вже існує');
                }
                
                if(strlen($this->post->login) == 0)
                {
                    $this->addErrorMessage('логін не вказано');
                }
                if($this->post->password != $this->post->password2)
                {
                    $this->addErrorMessage('паролі не співпадають');
                }
                if(strlen($this->post->password) == 0)
                {
                    $this->addErrorMessage('пароль не вказано');
                }
                if(strlen($this->post->password2) == 0)
                {
                    $this->addErrorMessage('повторний пароль не вказано');
                }
                if(strlen($this->post->email) == 0)
                {
                    $this->addErrorMessage('пошту не вказано');
                }
                if(!$this->isErrorMessagesExist())
                {
                    \models\Admins::RegisterAdmin($this->post->login, $this->post->password, $this->post->email);
                    return $this->redirect('/lost_admin/admins/login');
                }
            }
            return $this->render();
        }

        public function actionIndex()
        {
            $tables = [];
            foreach(array_diff(scandir("models/"), array("..", ".")) as $table)
            {
                $tables[] = pathinfo(strtolower($table), PATHINFO_FILENAME);
            }
            $this->template->setParam("tables", $tables);
            

            return $this->render();
        }

        public function actionGetTable()
        {
            $tableName = $this->get->tableName;
            $db = \core\Core::get()->db;

            $tableData = $db->select("{$tableName}", "*");

            header('Content-Type: application/json');
            echo json_encode($tableData);
            exit;
        }

        public function actionSaveData()
        {
            $db = \core\Core::get()->db;
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            $tableName = $data[0];
            $id = $data[1];
            $keys = $db->select("information_schema.columns", "column_name", ["table_name" => $tableName]);
            $data = array_slice($data, 1);
            for($i = 1; $i < count($keys); $i++)
            {
                $db->update("$tableName", [$keys[$i]['COLUMN_NAME'] => "$data[$i]"], ['id' => "$id"]);
            }
            exit;
        }

        public function actionDeleteData()
        {
            $table = $this->get->table;
            $id = $this->get->id;
            var_dump($table, $id);
            $db = \core\Core::get()->db;

            $db->delete($table, ["id" => $id]);
        }

        public function actionProfile()
        {
            return $this->render();
        }

        public function actionRegisterSuccess()
        {
            return $this->render();
        }

        public function actionLogout()
        {
            \models\Admins::LogoutAdmin();
            return $this->redirect('login');
        }
    }
?>