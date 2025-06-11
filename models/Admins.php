<?php
    namespace models;
    
    class Admins extends \core\Model
    {
        public static $tableName = 'admins';

        public static function FindByLoginAndPassword($login, $password)
        {
            $rows = self::findByCondition(['login' => $login, 'password' => $password]);
            if(!empty($rows))
            {
                return $rows[0];
            }
            else
            {
                return null;
            }
        }

        public static function FindByLogin($login)
        {
            $rows = self::findByCondition(['login' => $login]);
            if(!empty($rows))
            {
                return $rows[0];
            }
            else
            {
                return null;
            }
        }

        public static function FindByEmail($email)
        {
            $rows = self::findByCondition(['email' => $email]);
            if(!empty($rows))
            {
                return $rows[0];
            }
            else
            {
                return null;
            }
        }

        public static function IsAdminLogged()
        {
            return !empty(\core\Core::get()->session->get('admin'));
        }

        public static function LoginAdmin($admin)
        {
            \core\Core::get()->session->set('admin', $admin);
        }

        public static function LogoutAdmin()
        {
            \core\Core::get()->session->remove('admin');
        }

        public static function RegisterAdmin($login, $password, $email)
        {
            $admin = new \models\Admins();
            $admin->login = $login;
            $admin->password = $password;
            $admin->email = $email;
            $admin->save();
        }

        public static function UpdateAdmin($login, $password, $email)
        {
            $adminData = \core\Core::get()->session->get('admin');
            $admin = new \models\Admins();
            $admin->id = $adminData["id"];
            $admin->login = $login;
            $admin->password = $password;
            $admin->email = $email;
            $admin->save();
        }
    }
?>