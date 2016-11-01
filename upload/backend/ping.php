<?php

function getDBConnection () {
    include("mysql.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass

    $conn = ($GLOBALS["___mysqli_ston"] = mysqli_connect($mysql_host,  $mysql_user,  $mysql_pass));
    if (!$conn) {
        echo "Connection &#224; la DB n'a pas été possible!";
        end;
    }
    if (!((bool)mysqli_query( $conn, "USE " . $mysql_db))) {
        echo "Pas de DB avec ce nom semble exister sur le serveur!";
        end;
    }
    return $conn;
}

# establishes a connection to a mySQL Database accroding to the details specified in settings.php
function his_getDBConnection () {
    include("mysql.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    $conn = ($GLOBALS["___mysqli_ston"] = mysqli_connect($mysql_host,  $mysql_user,  $mysql_pass));
    if (!$conn) {
        echo "Connection &#224; la DB n'a pas été possible!";
        end;
    }
    if (!((bool)mysqli_query( $conn, "USE " . $mysql_db))) {
        echo "Pas de DB avec ce nom semble exister sur le serveur!";
        end;
    }
    return $conn;
}
?>