<html>
    <head>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Suggestion System</title>
    </head>
    <body>
        <h1>Suggestion System</h1>
        <div class="menu">
            <a href="./index.php">Home</a>
            <a href="./cache.html">List</a>
            <?php 
            if(isset($_COOKIE["token"])){
                echo "<a href='./login.php?logout=true'>Logout</a>";
            } else {
                echo "<a href='./login.php'>Login</a>";
            }
            ?>
        </div>
        <div class="container">
        <?php 
        include 'lib.php';
        
        if(isset($_GET) && count($_GET) > 0) {
            if(isset($_GET["srg"])){
                $srg = $_GET["srg"];
                if(($list = checkValidSrg($srg)) !== false) {
                    if (!file_exists("./suggestions/")){
                        mkdir("./suggestions/");
                    }            
                    $path = "suggestions/" . $srg . ".csv";
                    $map = load($path);
                    echo("<h2>" . $list[$srg] . " - $srg</h2>");
                    if(isset($_GET["add"])){
                        if(checkCookie()){
                            if(!isset($map[$_GET["add"]])){
                                $file = fopen($path, "a");
                                fwrite($file, $_GET["add"] . ", 1\r\n");
                                fclose($file);
                                header("Location: srg.php?srg=$srg");
                            } else {
                                echo "<p>Already defined!</p>";
                            }
                        } else {
                            echo "<p>Error! Not logged in!</p>";
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
                } else {
                    echo "<p>Error not a valid SRG!</p>";
                }
            }
        }
        ?>
        </div>
    </body>
</html>
