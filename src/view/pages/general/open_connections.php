<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="http://localhost/cms/assets/style/openC.css" rel="stylesheet">
    <title>open connections</title>
</head>
<body>
    <main>
    <div id="open_connection_container">

        <?php
        if($_SESSION["acc_type"] == "admin"){
    
            echo "<a href='/cms/open_connections/'><button>Click to open connections</button></a>";
            echo "<p>By opening connections you would become the main admin.This priviledge should be reserved for
            the head of Department or whoever he or She authorises</p>";
        }else{
            echo "connections are not open yet...sorry";
        }
        ?>
        
    </div>
</main>
</body>
</html>
