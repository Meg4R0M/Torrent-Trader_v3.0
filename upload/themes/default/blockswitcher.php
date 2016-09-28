<?php

    if (isset($_GET["switch"]) && $_GET["switch"] == "right")
        setcookie("blockswitcher", "right", time() + 604800, "/");
    
    if (isset($_GET["switch"]) && $_GET["switch"] == "left")
        setcookie("blockswitcher", "left", time() + 604800, "/");

		header("Location: ".$_SERVER['HTTP_REFERER']);
	
?>