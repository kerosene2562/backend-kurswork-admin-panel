<?php
    /**@var string $Title */
    /**@var string $Content */
    if(empty($Title))
        $Title = '';
    if(empty($Content))
        $Content = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/lost_admin/css/style.css">
    <title><?= $Title ?></title>
</head>
<body>
    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                </a>
                
                <ul id="header_text_and_logo" class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <a href="/lost_admin/admins/index">
                        <img id="header_mini_logo" src="/lost_admin/assets/images/lost_island.jpg" alt="lost_island">
                    </a>
                    <li><a href="/lost_admin/admins/index" class="nav-link px-2 link-secondary"><p id="header_logo_text">Lost_island</p></a></li>
                </ul>
                <?php if(\models\Admins::IsAdminLogged()) : ?>
                    <div class="dropdown text-end">
                        <a class="dropdown-item" href="/lost_admin/admins/profile">Profile</a>
                        <a class="dropdown-item" href="/lost_admin/admins/logout">Sign out</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div>
        <?= $Content ?>
    </div>
</body>
</html>