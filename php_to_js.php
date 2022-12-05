<?php
    $name = "David";
    setcookie("name", $name);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="name" content="<?= htmlspecialchars($name) ?>" />
    </head>
    <body>
        <h1>DEMO</h1>
            <script>
                var match = document.cookie.match(new RegExp('name=([^;]+)'));
                var name = decodeURIComponent(match[1]);
                alert(name);
            </script>
    </body>
</html>