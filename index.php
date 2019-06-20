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
        // parses csvs and packs them in arrays
        function readAndPack($name){
            $map = array();
            $file = fopen($name, "r");
            if($file) {
                fgets($file); // Gets the first line bc not needed
                while(($line = fgets($file)) !== false){
                    $arr = explode(",", $line);
                    $map[$arr[0]] = $arr[1];
                }
                fclose($file);
            } else {
                echo "Error reading " . $name . "! Contact side owner!";
            }    
            return $map;
        }

        // Displays table
        function display($map) {
            echo "<table>";
            foreach($map as $srg => $name) {
                echo "<tr><td>" . $srg . "</td><td><a href=\"?srg=" . $srg . "\">" . $name . "</a></td>";
            }
            echo "</table>";
        }

        if(!isset($GLOBALS["methods"])){
            $GLOBALS["methods"] = readAndPack("methods.csv");
        }
        if(!isset($GLOBALS["params"])){
            $GLOBALS["params"] = readAndPack("params.csv");
        }
        if(!isset($GLOBALS["fields"])){
            $GLOBALS["fields"] = readAndPack("fields.csv");
        }

        // Checking for the srg value for not being mallissios
        function checkValidSrg($srg) {
            if(isset($GLOBALS["methods"][$srg])) {
                return;
            }
            if(isset($GLOBALS["params"][$srg])) {
                return;
            }
            if(isset($GLOBALS["fields"][$srg])) {
                return;
            }
            echo("Invalide srg!");
            exit(403);
        }

        function load($pth) {
            $map = array();
            $file = fopen($srg, "r");
            while(($line = fgets($file)) !== false){
                $arr = explode(",", $line);
                $map[$arr[0]] = (int)$arr[1];
            }
            asort($map);
            return $map;            
        }

        if(isset($_GET) && count($_GET) > 0) {
            if(isset($_GET["srg"])){
                $srg = $_GET["srg"];
                checkValidSrg($srg);
                $path = $srg . ".csv";
                if(isset($_GET["add"])){
                    $file = fopen($srg, "a");
                    while(($line = fgets($file)) !== false){
                        $arr = explode(",", $line);
                        $map[$arr[0]] = $arr[1];
                    }            
                    fwrite($file, $_GET["add"] . ", 1");
                }
                $file = false;
                if(file_exists($path)) {
                    $map = load($path);
                    if(count($map) <= 0) {
                        return NULL;
                    }
                    echo "<table>";
                    foreach($map as $srg => $votes){
                        echo "<tr><td>" . $srg . "</td><td>" . $votes . "</td>";
                    }
                    echo "</table>";
                }
                echo "<form method='get'><input type='hidden' name='srg' value='" . $srg . "'><input type='text' name='add'><input type='submit' value='Add'></form>";
            }
        } else {
            display($GLOBALS["methods"]);
            display($GLOBALS["params"]);
            display($GLOBALS["fields"]);
        }
        ?>
        </div>
    </body>
</html>
