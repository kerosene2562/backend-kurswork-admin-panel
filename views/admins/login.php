<?php
    $this->Title = 'вхід в адміна';
?>

<link rel="stylesheet" href="/lost_admin/css/login.css">
<div class="login_content">
  <div class="login_block">
    <div class="img_block">
        <img src="/lost_admin/assets/images/login_boy.gif" alt="login_boy" class="img_login_boy">
    </div>
    <form method = "POST">
      <?php if(!empty($error_message)) : ?>
        <div class="alert alert-danger" role="alert">
          <?= $error_message ?>
        </div>
      <?php endif; ?>  
      <div class="info_block">
          <p class="info_text">Логін</p>
          <input type="text" name="login" class="info_input" id="login_input">
      </div>
      <div class="info_block">
          <p class="info_text">Пароль</p>
          <input type="text" name="password" class="info_input" id="password_input">
      </div>
      <button type="submit" class="loginButton">Увійти</button>
    </form>
  </div>
</div>
