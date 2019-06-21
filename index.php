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
            if(isset($_GET["search"])){
                $name = $_GET["search"];
                echo "<table>";
                if(($list = checkValidSrg($name)) !== false){
                    echo "<tr><td>$name</td><td><a href='srg.php?srg=$name'>" . $list[$name] . "</a></td></tr>";
                } else {
                    $arr = getSrgFromName($name);
                    foreach($arr as $srg => $nameo) {
                        echo "<tr><td>$srg</td><td><a href='srg.php?srg=$srg'>$nameo</a></td></tr>";
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
