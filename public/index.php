<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$template_loader = "functions/template_loader.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library: Films, Books, Music</title>
    <link rel="stylesheet" href="styles/global.css">
</head>

<body>
    <!--NAVBAR -->
    <div>
        <?php include 'components/navigationbar.php'; ?>
    </div>
    <!--MAIN-->  
    <div id="main-container" role="main">      
        <?php include $template_loader; ?>   
    </div>
    <!--FOOTER-->
    <div>
        <?php include 'components/footer.php'; ?>
    </div>
</body>

</html>
<?php
ob_end_flush();
?>