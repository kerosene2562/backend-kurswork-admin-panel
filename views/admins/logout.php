<?php 
    if(\models\Admins::IsAdminLogged())
    {
        \models\Admins::LoginAdmin();
    }
?>