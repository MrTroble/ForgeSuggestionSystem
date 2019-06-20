<html>
    <head>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Suggestion System</title>
    </head>
    <body>
        <h1>Suggestion System</h1>
        <div class="menu">
            <a href="./index.php">Home</a>
        </div>
        <div class="container">
        <?php 
        include 'lib.php';
        
        if(isset($_GET) && count($_GET) > 0) {
            if(isset($_GET["srg"])){
                $srg = $_GET["srg"];
                checkValidSrg($srg);
                $path = "suggestions/" . $srg . ".csv";
                $map = load($path);
                if(isset($_GET["add"])){
                    if(!isset($map[$_GET["add"]])){
                        $file = fopen($path, "a");
                        fwrite($file, $_GET["add"] . ", 1\r\n");
                    }
                }
                $file = false;
                if(file_exists($path)) {
                    if(count($map) <= 0) {
                        return NULL;
                    }
                    echo "<table>";
                    foreach($map as $name => $votes){
                        echo "<tr><td>" . $name . "</td><td>" . $votes . "</td>";
                    }
                    echo "</table>";
                }
                echo "<form method='get'><input type='hidden' name='srg' value='" . $srg . "'><input type='text' name='add'><input type='submit' value='Add'></form>";
            }
        } else {
            echo "<iframe src='cache.html' frameBorder='0' width='100%' height='100%'></iframe>";
        }
        ?>
        </div>
    </body>
</html>
