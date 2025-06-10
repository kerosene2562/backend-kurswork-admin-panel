<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERROR <?=http_response_code()?></title>
    <link rel="stylesheet" href="/lost_island/css/404.css">
</head>
<body>
    <div class="content">
        <div class="info_block">
            <div class="img_container">
                <img src="/lost_island/assets/images/work_boy.gif" id="img_vault_boy" alt="work_boy">
            </div>
            <div class="text_container">
                <h1 class="error">
                    ERROR <?=http_response_code()?>
                </h1>
                <p class="text">
                    Упс... сталася неочікувана помилка, напевно то РЕБ працює або ж що інше. 
                    Але не переживай, ти можеш повернутись на <a href="/lost_admin/admins/index" id="main_ref">головну</a> сторінку
                    та продовжити ділитись своїми думками анонімно.
                </p>
            </div>
        </div>
        
    </div>
    
</body>
</html>