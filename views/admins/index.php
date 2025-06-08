<?php
    $this->Title = "admin panel";
?>
<div id="overlay" onclick="closeModal()"></div>
<div id="create_modal_window">
    <div class="create_block">
        <p class="create_text">Введіть назву нової категорії:</p>
        <input type="text" id="new_category_input">
        <button id="create_category_button" onclick="createCategory()">Створити</button>
    </div>
</div>

<div class="content">
    <div class="side_bar">
        <?php foreach($tables as $table) : ?>
            <button class="model_button" onclick="getModelTable('<?= $table ?>')"><?= $table ?></button>
        <?php endforeach; ?>
        <button class="model_button" onclick="getReportWork()">reports work</button>
    </div>
    <div id="main_bar">
        <div id="toolbar">
            <input type="text" id="search">
            <button id="create_button" onclick="showCreateModal()">Створити</button>
            <button id="save_button" onclick="saveAllData()">зберегти зміни</button>
        </div>
        <div id="workplace">

        </div>
    </div>
</div>
<script src="/lost_admin/scripts/admin.js"></script>