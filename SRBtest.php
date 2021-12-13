<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style.css">
  <title>Document</title>
</head>
<body>

<main>
  <h1>Setup-Run-Breakdown test </h1>
  <h2>DBhandler</h2>

  <?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once './testsManual/SetupRunBreakdownTest.php';
    
    $test = new \DBhandler\SetupRunBreakdownTest();
    $test->run();

  ?>
</main>
</body>
</html>