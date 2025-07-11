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
                $admin = \models\Admins::FindByLoginAndPassword($this->post->login, hash('sha512',$this->post->password));
                if(!empty($admin))
                {
                    \models\Admins::LoginAdmin($admin);
                    return $this->redirect('/lost_admin/admins/index');
                }
                else
                {
                    $this->addErrorMessage('неправильний логін або пароль!');
                }
            }
            return $this->render();
        }

        public function actionRegistr()
        {
            if(\models\Admins::IsAdminLogged())
            {
                if($this->isPost)
                {
                    $admin = \models\Admins::FindByLogin($this->post->login);
                    $email = \models\Admins::FindByEmail($this->post->email);
                    
                    if(!empty($admin))
                    {
                        $this->addErrorMessage('користувач з таким логіном вже існує');
                    }
                    if(!empty($email))
                    {
                        $this->addErrorMessage('користувач з такою поштою вже існує');
                    }
                    
                    if(strlen($this->post->login) == 0)
                    {
                        $this->addErrorMessage('логін не вказано');
                    }
                    if($this->post->password1 != $this->post->password2)
                    {
                        $this->addErrorMessage('паролі не співпадають');
                    }
                    if(strlen($this->post->password1) == 0)
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
                        \models\Admins::RegisterAdmin($this->post->login, hash('sha512', $this->post->password1), $this->post->email);
                        return $this->redirect('/lost_admin/admins/login');
                    }
                }
                return $this->render();
            }
            else{
                return $this->redirect('/lost_admin/admins/login');
            }
            
        }

        public function actionIndex()
        {
            if(\models\Admins::IsAdminLogged())
            {
                $tables = [];
                foreach(array_diff(scandir("models/"), array("..", ".")) as $table)
                {
                    $tables[] = pathinfo(strtolower($table), PATHINFO_FILENAME);
                }
                $this->template->setParam("tables", $tables);
                    

                return $this->render();
            }
            else{
                return $this->redirect('/lost_admin/admins/login');
            }
            
        }

        public function actionGetTable()
        {
            $tableName = $this->get->tableName;
            $order_by = $this->get->order_by;
            $sortDirection = $this->get->direction;
            $db = \core\Core::get()->db;
            $order_by_string = "";
            if($order_by != "null")
            {
                $order_by_string = "ORDER BY " . $order_by; 
                if($sortDirection == 'asc')
                {
                    $order_by_string .= " ASC";
                }
                else{
                    $order_by_string .= " DESC";
                }
            }
            
            $tableData = $db->select("{$tableName}", "*", null, $order_by_string);

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
            $keys = $db->select("INFORMATION_SCHEMA.COLUMNS", "COLUMN_NAME", ["TABLE_NAME" => $tableName], "ORDER BY ORDINAL_POSITION");
            $data = array_slice($data, 1);
            for($i = 1; $i < count($keys); $i++)
            {
                if($data[$i] == "")
                {
                    $data[$i] = null;
                }
                $db->update("$tableName", [$keys[$i]['COLUMN_NAME'] => $data[$i]], ['id' => "$id"]);
            }
            exit;
        }

        public function actionDeleteData()
        {
            $table = $this->get->table;
            $id = $this->get->id;
            $db = \core\Core::get()->db;

            if($table == "threads")
            {
                $dir = $db->select('threads', "*", ['id' => $id]);
                array_map('unlink', glob(__DIR__ . "/../../lost_island/pics/" . $dir[0]["pics_folder_uuid"] . "/*" ));
                rmdir(__DIR__ . "/../../lost_island/pics/" . $dir[0]["pics_folder_uuid"] . "/");
            }

            $db->delete($table, ["id" => $id]);
        }

        public function actionGetReportsWork()
        {
            $db = \core\Core::get()->db;
            $reports = $db->select("reports", "*", ["is_checked" => 0]);
            if(count($reports) == 0)
                exit;
            foreach($reports as $report)
            {
                $reportedData;

                if($report["type"] == 'comment'){
                    $reportedData = $db->select("discussion", "*", ["id" => $report["reported_id"], "is_deleted" => 0]);
                }
                if($report["type"] == 'thread'){
                    $reportedData = $db->select("threads", "*", ["id" => $report["reported_id"], "is_deleted" => 0]);
                }                
                $result = $db->select("reports", "*", ["reported_id" => $report["reported_id"], "is_checked" => 0]);
                $result[] = $reportedData[0];
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }
        }

        public function actionClearComment()
        {
            $id = $this->get->id;
            $isComment = $this->get->is_comment;

            $db = \core\Core::get()->db;
            
            if($isComment == "true")
            {
                $db->update('discussion', ["is_deleted" => 1], ['id' => $id]);
                $reports = $db->select('reports', "*", ["reported_id" => $id]);
                foreach($reports as $report)
                {
                    $db->update('reports', ["is_checked" => 1], ["reported_id" => $id]);
                }
            }
            else
            {
                $db->update('threads', ["is_deleted" => 1], ['id' => $id]);
                $reports = $db->select('reports', "*", ["reported_id" => $id]);
                var_dump($reports);
                foreach($reports as $report)
                {
                    $db->update('reports', ["is_checked" => 1], ["reported_id" => $id]);
                }
            }
        }

        public function actionIgnoreReports()
        {
            $id = $this->get->id;
            $db = \core\Core::get()->db;
            
            $reports = $db->select('reports', "*", ["reported_id" => $id]);
            foreach($reports as $report)
            {
                $db->update('reports', ["is_checked" => 1], ["reported_id" => $id]);
            }
        }

        public function actionProfile()
        {
            if($this->isPost)
            {
                $setPassword = hash('sha512', $this->post->password);
                $newPass1 = $this->post->password1;
                $newPass2 = $this->post->password2;
                $data = \core\Core::get()->session->get('admin');
                if(strlen($this->post->login) == 0)
                {
                    $this->addErrorMessage('логін не вказано');
                }
                if(hash('sha512', $this->post->password) != $data['password'])
                {
                    $this->addErrorMessage('неправильний пароль!');
                }
                if($newPass1 != "" || $newPass2 != "")
                {
                    if($newPass2 == $newPass1)
                    {
                        $setPassword = hash('sha512',$this->post->password1);
                    }
                    else
                    {
                        $this->addErrorMessage('нові паролі не збігаються');
                    }
                }
                
                if(strlen($this->post->email) == 0)
                {
                    $this->addErrorMessage('пошту не вказано');
                }
                if(!$this->isErrorMessagesExist())
                {
                    \models\Admins::UpdateAdmin($this->post->login, $setPassword, $this->post->email);
                    \models\Admins::LoginAdmin(\models\Admins::FindByLogin($this->post->login));

                }
            }
            if(\models\Admins::IsAdminLogged()){
                $data = \core\Core::get()->session->get('admin');

                $this->template->setParams(['data' => $data]);

                return $this->render();
            }            
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

        public function actionCreateCategory()
        {
            $db = \core\Core::get()->db;
            $name = $this->get->name;
            
            $sameCategorie = $db->select('categories', "*", ["name" => ucfirst($name)]);
            if(count($sameCategorie) > 0)
            {
                $error = 'Дана категорія вже існує!';
                header('Content-Type: application/json');
                echo json_encode($error);
                exit;
            }
            if(strlen($name) < 2)
            {
                $error = 'Назва категорії повинна бути довшою за 2 символи!';
                header('Content-Type: application/json');
                echo json_encode($error);
                exit;
            }
            $db->insert("categories", ["name" => ucfirst($name)]);
        }

        public function actionStats()
        {
            $db = \core\Core::get()->db;

            $post = $db->select("logger", "*", ["method" => "POST"]);
            $this->template->setParam("post", count($post));

            $get = $db->select("logger", "*", ["method" => "GET"]);
            $this->template->setParam("get", count($get));

            $reports = $db->select("reports", "*", null, "WHERE reported_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
            $this->template->setParam("reports", count($reports));

            $countOfTreads = $db->select("threads", "*");
            $this->template->setParam("countOfTreads", count($countOfTreads));
            
            $threadsPerMonth = $db->select("threads", "*", null, "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
            $this->template->setParam("threadsPerMonth", count($threadsPerMonth));

            $commentsPerMonth = $db->select("discussion", "*", null, "WHERE post_datetime >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
            $this->template->setParam("commentsPerMonth", count($commentsPerMonth));

            return $this->render();
        }

        public function actionGetStats()
        {
            $db = \core\Core::get()->db;
            
            $data = $db->select('logger', ["status_code", "created_at"]);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }
?>