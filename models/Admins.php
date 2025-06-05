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

        public static function RegisterAdmin($login, $password, $mail)
        {
            $admin = new \models\Admins();
            $admin->login = $login;
            $admin->password = $password;
            $admin->mail = $mail;
            $admin->save();
        }
    }
?>