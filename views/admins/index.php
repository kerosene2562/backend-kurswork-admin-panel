<?php
    $this->Title = "admin panel";
?>

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
            <button id="save_button" onclick="saveAllData()">зберегти зміни</button>
        </div>
        <div id="workplace">

        </div>
    </div>
</div>
<script src="/lost_admin/scripts/admin.js"></script>