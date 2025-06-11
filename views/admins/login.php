<?php
    $this->Title = 'вхід в адміна';
?>

<link rel="stylesheet" href="/lost_admin/css/login.css">
<div id="video_block">
  <video id="background_video" src="/lost_admin/assets/images/login_background.mp4" muted></video>
</div>
<div class="login_block_glass"></div>
<div class="login_content">
  <div class="login_block">
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
<script>
  document.getElementById('background_video').play();
</script>