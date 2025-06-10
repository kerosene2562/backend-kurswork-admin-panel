<?php
    $this->Title = 'Веєстрація адміна';
?>

<link rel="stylesheet" href="/lost_admin/css/register.css">
<div class="registr_content">
    <div class="registr_block">
        <div class="img_block">
            <img src="/lost_admin/assets/images/registr_boy.gif" alt="registr_boy" class="img_registr_boy">
        </div>
        <?php if(!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>  
        <form action="/lost_admin/admins/registr" method="POST" class="registr_info_block">
            <div class="info_block">
                <p class="info_text">Логін</p>
                <input type="text" name="login" class="info_input" id="login_input">
            </div>
            <div class="info_block">
                <p class="info_text">Пошта</p>
                <input type="text" name="email" class="info_input" id="email_input">
            </div>
            <div class="info_block">
                <p class="info_text">Пароль</p>
                <input type="text" name="password1" class="info_input" id="password1_input">
            </div>
            <div class="info_block">
                <p class="info_text">Повторити новий пароль</p>
                <input type="text" name="password2" class="info_input" id="password2_input">
            </div>
            <button type="submit" class="saveButton">Зареєструватись</button>
        </form>
    </div>
</div>
