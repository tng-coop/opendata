<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application's Title</title>
</head>

<body>
    <?php
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    require_once 'config.php';
    require ('router.php');
    require ('error.php');  
    require ('goto.php');
    require ('list.php');
    ?>
</body>

</html>