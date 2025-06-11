<html>
<head>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
  <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
  <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">
  <link href="/lost_admin/css/stats.css" type="text/css" rel="stylesheet">
</head>
<body>
  <div class="content">
    <div class="info_block">
      <div class="block">
        <p class="info_desc">GET</p>
        <div class="info_text_block">
          <p class="info_text"><?=$get?></p>
        </div>
      </div>
      <div class="block">
        <p class="info_desc">POST</p>
        <div class="info_text_block">
          <p class="info_text"><?=$post?></p>
        </div>
      </div>
      <div class="block">
        <p class="info_desc">Репортів за місяці</p>
        <div class="info_text_block">
          <p class="info_text"><?=$reports?></p>
        </div>
      </div>
      <div class="block">
        <p class="info_desc">Створених тредів</p>
        <div class="info_text_block">
          <p class="info_text"><?=$countOfTreads?></p>
        </div>
      </div>
      <div class="block">
        <p class="info_desc">Тредів за місяць</p>
        <div class="info_text_block">
          <p class="info_text"><?=$threadsPerMonth?></p>
        </div>
      </div>
      <div class="block">
        <p class="info_desc">Коментарів за місяць</p>
        <div class="info_text_block">
          <p class="info_text"><?=$commentsPerMonth?></p>
        </div>
      </div>
    </div>
    <div id="container"></div>
  </div>
  
 
  <script src="/lost_admin/scripts/stats.js"></script>
</body>
</html>
                