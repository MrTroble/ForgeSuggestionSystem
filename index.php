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
                echo "<p>You are logged in</p>";
            } else {
                echo "<a href='./login.php'>Login</a>";
            }
            ?>
        </div>
        <div class="container">
        <?php 
        include 'lib.php';
        
        if (!file_exists("./suggestions/")){
            mkdir("./suggestions/");
        }

        if(isset($_GET) && count($_GET) > 0) {
            if(isset($_GET["srg"])){
                $srg = $_GET["srg"];
                if(($list = checkValidSrg($srg)) !== false) {
                    $path = "suggestions/" . $srg . ".csv";
                    $map = load($path);
                    echo("<h1>" . $list[$srg] . "</h1>");
                    echo("<h2>$srg</h2>");
                    if(isset($_GET["add"])){
                        if(checkCookie()){
                            if(!isset($map[$_GET["add"]])){
                                $file = fopen($path, "a");
                                fwrite($file, $_GET["add"] . ", 1\r\n");
                                fclose($file);
                                header("Location: index.php?srg=$srg");
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
            } else if(isset($_GET["search"])){
                $name = $_GET["search"];
                echo "<table>";
                if(($list = checkValidSrg($name)) !== false){
                    echo "<tr><td>$name</td><td><a href='index.php?srg=$name'>" . $list[$name] . "</a></td></tr>";
                } else {
                    $arr = getSrgFromName($name);
                    foreach($arr as $srg => $nameo) {
                        echo "<tr><td>$srg</td><td><a href='index.php?srg=$srg'>$nameo</a></td></tr>";
                    }
                }
                echo "</table>";
            }
        } else {
            echo "<form method='get'><input type='text' name='search'><input type='submit' value='Search'></form>";
        }
        ?>
        </div>
    </body>
</html>
