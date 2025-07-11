<?php
    $this->Title = "Профіль";
?>

<link rel="stylesheet" href="/lost_admin/css/profile.css">
<div class="profile_content">
    <div class="profile_block">
        <div class="img_block">
            <img src="/lost_admin/assets/images/profile_boy.gif" alt="profile_boy" class="img_profile_boy">
        </div>
        <?php if(!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>  
        <form action="/lost_admin/admins/profile" method="POST" class="profile_info_block">
            <div class="info_block">
                <p class="info_text">Логін</p>
                <input type="text" name="login" class="info_input" id="login_input" value="<?=$data['login']?>">
            </div>
            <div class="info_block">
                <p class="info_text">Пошта</p>
                <input type="text" name="email" class="info_input" id="email_input" value="<?=$data['email']?>">
            </div>
            <div class="info_block">
                <p class="info_text">Пароль</p>
                <input type="text" name="password" class="info_input" id="password1_input">
            </div>
            <div class="info_block">
                <p class="info_text">Новий пароль</p>
                <input type="text" name="password1" class="info_input" id="password1_input">
            </div>
            <div class="info_block">
                <p class="info_text">Повторити новий пароль</p>
                <input type="text" name="password2" class="info_input" id="password2_input">
            </div>
            <button type="submit" class="saveButton">Зберегти зміни</button>
        </form>
    </div>
</div>