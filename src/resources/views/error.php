<html>
<head>
    <title>Error</title>
</head>
<body>
<h2><?php echo $message ?></h2>
<?php
    require "data.php";

    if($data['bad_domain']){ ?>
        <script>
            setTimeout(function(){
                location.href = 'http://google.com/';
            }, 5000);
        </script>
    <?php } ?>
</body>
</html>