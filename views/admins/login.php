<?php
    $this->Title = 'вхід в адміна';
?>

<form method = "POST">
  <?php if(!empty($error_message)) : ?>
    <div class="alert alert-danger" role="alert">
      <?= $error_message ?>
    </div>
  <?php endif; ?>  
  <div class="mb-3">
    <label for="inputLogin" class="form-label">Пошта \ Логін</label>
    <input value="<?=\core\Core::get()->session->get('admin') ?>" name="login" class="form-control" id="inputLogin">
  </div>
  <div class="mb-3">
    <label for="inputPassword" class="form-label">Пароль</label>
    <input name="password" type="password" class="form-control" id="inputPassword">
  </div>
  <button type="submit" class="btn btn-primary">Увійти</button>
</form>