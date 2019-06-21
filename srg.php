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
        
        error_reporting(E_ALL);
        ini_set("display_errors", 1);

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
                        if(($accesstoken = checkCookie()) !== false){
                            if(($user = getUsername($accesstoken)) !== false) {
                                if(!isset($map[$_GET["add"]])){
                                    $map[$_GET["add"]] = array($user);
                                    write($path, $map);
                                    header("Location: srg.php?srg=$srg");
                                } else {
                                    echo "<p>Already defined!</p>";
                                }
                            } else {
                                echo "<p>Error! Access token revoked? Git down? (Could not get username)</p>";
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
