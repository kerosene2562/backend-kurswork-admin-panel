<?php
    $this->Title = 'реєстрація в адміна';
?>

<form method = "POST">
  <?php if(!empty($error_message)) : ?>
    <div class="alert alert-danger" role="alert">
      <?= $error_message ?>
    </div>
  <?php endif; ?>  
  <div class="mb-3">
    <label for="inputLogin" class="form-label">Логін</label>
    <input value="<?=$this->controller->post->login ?>" name="login" class="form-control" id="inputLogin">
  </div>
  <div class="mb-3">
    <label for="inputEmail" class="form-label">Пошта</label>
    <input value="<?=$this->controller->post->email ?>" name="email" class="form-control" id="inputEmail">
  </div>
  <div class="mb-3">
    <label for="inputPassword" class="form-label">Пароль</label>
    <input name="password" type="password" class="form-control" id="inputPassword">
  </div>
  <div class="mb-3">
    <label for="inputPassword2" class="form-label">Пароль (повторити)</label>
    <input name="password2" type="password" class="form-control" id="inputPassword2">
  </div>
  <button type="submit" class="btn btn-primary">Зареєструвати</button>
</form>