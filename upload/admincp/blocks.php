<?php
//
//  TorrentTrader v3.x
//    $LastChangedDate: 2016-10-05 14:25:54 +0100 (Wed, 5 Oct 2016) $
//    $LastChangedBy: Meg4R0M $
//

// POWERFULL ADMINCP

require_once("../backend/functions.php");
require_once("../backend/bbcode.php");
dbconn();
loggedinonly();

if (!$CURUSER || $CURUSER["control_panel"]!="yes"){

    show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);

}

include("header.php");
include("sidemenu.php");
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="ace-icon fa fa-cog bigger-130"></i>
                </div>

                <div class="ace-settings-box clearfix" id="ace-settings-box">
                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                    <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                    <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                    <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                </select>
                            </div>
                            <span>&nbsp; Choose Skin</span>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
                            <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
                            <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                            <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
                            <label class="lbl" for="ace-settings-add-container">
                                Inside
                                <b>.container</b>
                            </label>
                        </div>
                    </div><!-- /.pull-left -->

                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                            <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                            <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                            <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                        </div>
                    </div><!-- /.pull-left -->
                </div><!-- /.ace-settings-box -->
            </div><!-- /.ace-settings-container --><?php

            echo '<div class="page-header">
                <h1>
                    Blocs
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        '.T_("_BLC_MAN_").'
                    </small>
                </h1>
            </div><!-- /.page-header -->';

            echo '<div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->';
            if($_GET["preview"]){
                $name = cleanstr($_GET["name"]);
                if (!file_exists("blocks/{$name}_block.php"))
                    show_error_msg(T_("ERROR"), "Possible XSS attempt.", 1);

                echo "<a name=\"".$name."\"></a>";
                begin_frame(T_("_BLC_PREVIEW_"));

                echo "<br /><center><b>".T_("_BLC_USE_SITE_SET_")."</b></center><hr />";
                echo "<table border=\"0\" width=\"180\" align=\"center\"><tr><td>";
                include("blocks/".$name."_block.php");
                echo "</td></tr></table><hr />";
                echo "<center><a href=\"javascript: self.close();\">".T_("_CLS_WIN_")."</a></center>";

                end_frame();
                stdfoot();
                die();
            }



            // == addnew

            if(@count($_POST["addnew"])){
                foreach($_POST["addnew"] as $addthis){
                    $i = $addthis;

                    $addblock = $_POST["addblock_".$i];
                    $wantedname = sqlesc($_POST["wantedname_".$i]);
                    $name = sqlesc(str_replace("_block.php","",cleanstr($addblock)));
                    $description = sqlesc($_POST["wanteddescription_".$i]);

                    SQL_Query_exec("INSERT INTO blocks (named, name, description, position, enabled, sort) VALUES ($wantedname, $name, $description, 'left', 0, 0)")  or ((((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false)) == 1062) ? show_error_msg(T_("ERROR"),"Sorry, this block is in database already!",1) : show_error_msg(T_("ERROR"),"Database Query failed: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))));
                    if(mysqli_affected_rows($GLOBALS["___mysqli_ston"]) != 0){
                        $success = "<center><font size=\"3\"><b>".T_("_SUCCESS_ADD_")."</b></font></center><br />";
                    }else{
                        $success = "<center><font size=\"3\"><b>".T_("_FAIL_ADD_")."</b></font></center><br />";
                    }
                }
                echo $success;
            }// end addnew

            // == permanent delete
            if(@count($_POST["deletepermanent"])){
                foreach($_POST["deletepermanent"] as $delpthis){
                    unlink("blocks/".$delpthis);
                    if(file_exists("blocks/".$delpthis))
                        $delmessage="<center><font size=\"3\"><b>".T_("_FAIL_DEL_")."</b></font></center><br />";
                    else
                        $delmessage="<center><font size=\"3\"><b>".T_("_SUCCESS_DEL_")."</b></font></center><br />";
                }
                echo $delmessage;
            }// end addnew

            $nextleft=(mysqli_num_rows(SQL_Query_exec("SELECT position FROM blocks WHERE position='left' AND enabled=1"))+1);
            $nextmiddle=(mysqli_num_rows(SQL_Query_exec("SELECT position FROM blocks WHERE position='middle' AND enabled=1"))+1);
            $nextright=(mysqli_num_rows(SQL_Query_exec("SELECT position FROM blocks WHERE position='right' AND enabled=1"))+1);

            // upload block
            if($_POST["upload"] == "true"){
                $uplfailmessage = "";
                $uplsuccessmessage = "";
                if ($_FILES['blockupl']) {

                    $blockfile = $_FILES['blockupl'];

                    if ($blockfile["name"] == ""){
                        $uplfailmessage .= "<br />".T_("_SEND_NOTHING_");
                    }
                    if (($blockfile["size"] == 0) && ($blockfile["name"] != "")){
                        $uplfailmessage .= "<br />".T_("_SEND_EMPTY_");
                    }
                    if ((!preg_match('/^(.+)\.php$/si', $blockfile['name'], $fmatches)) && ($blockfile["name"] != "")){
                        $uplfailmessage .= "<br />".T_("_SEND_INVALID_");
                    }
                    if ((!preg_match('/^(.+)\_block.php$/si', $blockfile['name'], $fmatches)) && ($blockfile["name"] != "")){
                        $uplfailmessage .= "<br />".T_("_SEND_NO_BLOCK_");
                    }

                    $blockfilename = $blockfile['tmp_name'];
                    if (@!is_uploaded_file($blockfilename)){
                        $uplfailmessage .= "<br />".T_("_FAIL_UPL_");
                    }

                }

                if(!$uplfailmessage){
                    $blockfilename = $site_config['blocks_dir'] . "/" . $blockfile['name'];
                    if($_POST["uploadonly"]){
                        if(file_exists($blockfilename)){
                            $uplfailmessage .= "<center><font size=\"3\">\"".$blockfile['name']."\"<b> ".T_("_BLC_EXIST_")."</b></font></center><br />";
                        }else{
                            if(@!move_uploaded_file($blockfile["tmp_name"], $blockfilename)){
                                $uplfailmessage .= "<center><font size=\"3\"><b>".T_("_CANNOT_MOVE_")." </b> \"".$blockfile['name']."\" <b>".T_("_TO_DEST_DIR_")."</b></font></center><br />".T_("_CONFIG_DEST_DIR_").": <b>\"".$site_config['blocks_dir']. "\"</b><br />".T_("_PLS_CHECK_")." <b>config.php</b> ".T_("_SURE_FULL_PATH_").". ".T_("_YOUR_CASE_").": <b>\"".$_SERVER['DOCUMENT_ROOT']."\"</b> + <b>\"/".T_("_SUB_DIR_")."\"</b> (".T_("_IF_ANY_").") ".T_("_AND_")." + <b>\"/blocks\"</b>.";
                            }else{
                                $uplsuccessmessage .= "<center><font size=\"3\">\"".$blockfile['name']."\" <b>".T_("_SUCCESS_UPL_")."</b></font></center><br />";
                            }
                        }
                    }else{
                        if(file_exists($blockfilename)){
                            $uplfailmessage .= "<center><font size=\"3\">\"".$blockfile['name']."\"<b> ".T_("_BLC_EXIST_")."</b></font></center><br />";
                        }else{
                            if(@!move_uploaded_file($blockfile["tmp_name"], $blockfilename)){
                                $uplfailmessage .= "<center><font size=\"3\"><b>".T_("_CANNOT_MOVE_")." </b> \"".$blockfile['name']."\" <b>".T_("_TO_DEST_DIR_")."</b></font></center><br />".T_("_CONFIG_DEST_DIR_").": <b>\"".$site_config['blocks_dir']. "\"</b><br />".T_("_PLS_CHECK_")." <b>config.php</b> ".T_("_SURE_FULL_PATH_").". ".T_("_YOUR_CASE_").": <b>\"".$_SERVER['DOCUMENT_ROOT']."\"</b> + <b>\"/".T_("_SUB_DIR_")."\"</b> (".T_("_IF_ANY_").") ".T_("_AND_")." + <b>\"/blocks\"</b>.";
                            }else{
                                $named = ($_POST["wantedname"] ? $_POST["wantedname"] : str_replace("_block.php","",$blockfile['name']));
                                $name  = str_replace("_block.php","",$blockfile['name']);
                                $description = $_POST["description"];
                                $position = $_POST["position"];
                                $sort = ($_POST["enabledyes"] ? $uplsort : 0);
                                $enabled = ($_POST["enabledyes"] ? 1 : 0);

                                SQL_Query_exec("INSERT INTO blocks (named, name, description, position, sort, enabled) VALUES (
                    ".sqlesc($named).", ".sqlesc($name).", ".sqlesc($description).", ".sqlesc($position).", ".sqlesc($sort).", ".sqlesc($enabled).")");

                                if(mysqli_affected_rows($GLOBALS["___mysqli_ston"]) != 0){
                                    $uplsuccessmessage .= "<center><font size='3'><b>".T_("_SUCCESS_UPL_ADD_")."</b></font></center><br />";
                                }else{
                                    $uplfailmessage .= "<center><font size='3'><b>".T_("_FAIL_UPL_ADD_")."</b></font></center><br />";
                                }
                                echo $uplsuccessmessage;
                            }
                        }
                    }
                }
            }// end upload block

            // == edit
            if ($_REQUEST["edit"] == "true")
            {
                # Prune Block Cache.
                //$TTCache->Delete("blocks_left");
                //$TTCache->Delete("blocks_middle");
                //$TTCache->Delete("blocks_right");

                //resort left blocks
                function resortleft(){
                    $sortleft = SQL_Query_exec("SELECT sort, id FROM blocks WHERE position='left' AND enabled=1 ORDER BY sort ASC");
                    $i=1;
                    while($sort = mysqli_fetch_assoc($sortleft)){
                        SQL_Query_exec("UPDATE blocks SET sort = $i WHERE id=".$sort["id"]);
                        $i++;
                    }
                }
                //resort middle blocks
                function resortmiddle(){
                    $sortmiddle = SQL_Query_exec("SELECT sort, id FROM blocks WHERE position='middle' AND enabled=1 ORDER BY sort ASC");
                    $i=1;
                    while($sort = mysqli_fetch_assoc($sortmiddle)){
                        SQL_Query_exec("UPDATE blocks SET sort = $i WHERE id=".$sort["id"]);
                        $i++;
                    }
                }
                //resort right blocks
                function resortright(){
                    $sortright = SQL_Query_exec("SELECT sort, id FROM blocks WHERE position='right' AND enabled=1 ORDER BY sort ASC");
                    $i=1;
                    while($sort = mysqli_fetch_assoc($sortright)){
                        SQL_Query_exec("UPDATE blocks SET sort = $i WHERE id=".$sort["id"]);
                        $i++;
                    }
                }

                // == delete

                if(@count($_POST["delete"])){
                    foreach($_POST["delete"] as $delthis){
                        SQL_Query_exec("DELETE FROM blocks WHERE id=".sqlesc($delthis));
                    }
                    resortleft();
                    resortmiddle();
                    resortright();
                }// == end delete

                // == move to left
                if(is_valid_id($_GET["left"])){
                    SQL_Query_exec("UPDATE blocks SET position = 'left', sort = $nextleft WHERE id = " . $_GET["left"]);
                    resortmiddle();
                    resortright();
                }// end move to left

                // == move to center
                if(is_valid_id($_GET["middle"])){
                    SQL_Query_exec("UPDATE blocks SET position = 'middle', sort = $nextmiddle WHERE id = " . $_GET["middle"]);
                    resortleft();
                    resortright();
                }// end move to center

                // == move to right
                if(is_valid_id($_GET["right"])){
                    SQL_Query_exec("UPDATE blocks SET position = 'right', sort = $nextright WHERE enabled=1 AND id = " . $_GET["right"]);
                    resortleft();
                    resortmiddle();
                }// end move to right

                // == move upper
                if(is_valid_id($_GET["up"])){
                    $cur = SQL_Query_exec("SELECT position, sort, id FROM blocks WHERE id = " . $_GET["up"]);
                    $curent = mysqli_fetch_assoc($cur);

                    $sort = ( int ) $_GET["sort"];

                    SQL_Query_exec("UPDATE blocks SET sort = ".$sort." WHERE sort = ".($sort-1)." AND id != " . $_GET["up"] . " AND position = " . sqlesc($_GET["position"]) . "");
                    SQL_Query_exec("UPDATE blocks SET sort = ".($sort-1)." WHERE id = " . $_GET["up"]);
                }// end move to upper

                // == move lower
                if(is_valid_id($_GET["down"])){
                    $cur = SQL_Query_exec("SELECT position, sort, id FROM blocks WHERE id = " . $_GET["down"]);
                    $curent = mysqli_fetch_assoc($cur);

                    $sort = ( int ) $_GET["sort"];

                    SQL_Query_exec("UPDATE blocks SET sort = ".($sort+1)." WHERE id = " . $_GET["down"]);
                    SQL_Query_exec("UPDATE blocks SET sort = ".$sort." WHERE sort = ".($sort+1)." AND id != " . $_GET["down"] . " AND position = " . sqlesc($_GET["position"]) ."");
                }// end move lower

                // == update
                $res=SQL_Query_exec("SELECT * FROM blocks ORDER BY id");

                if(!$_GET["up"] && !$_GET["down"] && !$_GET["right"] && !$_GET["left"] && !$_GET["middle"]){

                    $update = array();

                    while($upd = mysqli_fetch_assoc($res)){
                        $id = $upd["id"];
                        $update[] = "enabled = ".$_POST["enable_".$upd["id"]];
                        $update[] = "named = '".((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST["named_".$upd["id"]]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""))."'";
                        $update[] = "description = '".((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST["description_".$upd["id"]]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""))."'";

                        if(($upd["enabled"] == 0) && ($upd["position"] == "left") && ($_POST["enable_".$upd["id"]] == 1))
                            $update[] = "sort = ".$nextleft;
                        elseif(($upd["enabled"] == 0) && ($upd["position"] == "middle") && ($_POST["enable_".$upd["id"]] == 1))
                            $update[] = "sort = ".$nextmiddle;
                        elseif(($upd["enabled"] == 0) && ($upd["position"] == "right") && ($_POST["enable_".$upd["id"]] == 1))
                            $update[] = "sort = ".$nextright;

                        elseif(($upd["enabled"] == 1) && ($upd["position"] == "left") && ($_POST["enable_".$upd["id"]] == 0))
                            $update[] = "sort = 0";
                        elseif(($upd["enabled"] == 1) && ($upd["position"] == "middle") && ($_POST["enable_".$upd["id"]] == 0))
                            $update[] = "sort = 0";
                        elseif(($upd["enabled"] == 1) && ($upd["position"] == "right") && ($_POST["enable_".$upd["id"]] == 0))
                            $update[] = "sort = 0";
                        else
                            $update[] = "sort = ".$upd["sort"];

                        SQL_Query_exec("UPDATE blocks SET ". implode(", ", $update). " WHERE id=$id") or show_error_msg(T_("ERROR"), "".T_("_FAIL_DB_QUERY_").": ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
                    }
                }
                resortleft();
                resortmiddle();
                resortright();
            }// == end edit
                    if ($_POST["edit"]){

                        echo '<div class="row">
                            <div class="col-xs-12">';
                                // ---- <table> for blocks in database -----------------------------------------
                                $res = SQL_Query_exec("SELECT * FROM blocks ORDER BY enabled DESC, position, sort");
                                echo '<table id="simple-table" class="table  table-bordered table-hover">
                                    <form name="blocks" method="post" action="blocks.php">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">'.T_("_NAMED_").'<br />('.T_("_FL_NM_IF_NO_SET_").')</th>
                                                <th rowspan="2">'.T_("_FILE_NAME_").'</th>
                                                <th rowspan="2">'.T_("DESCRIPTION").'<br />('.T_("_MAX_").' 255 '.T_("_CHARS_").')</th>
                                                <th rowspan="2" colspan="3" class="hidden-480">'.T_("_POSITION_").'</th>
                                                <th rowspan="2" colspan="2">'.T_("_SORT_ORDER_").'</th>
                                                <th colspan="2">'.T_("ENABLED").'</th>
                                                <th rowspan="2">'.T_("_DEL_").'</th>
                                            </tr>
                                            <tr>
                                                <th>'.T_("YES").'</th>
                                                <th>'.T_("NO").'</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            while($blocks2 = mysqli_fetch_assoc($res)){
                                                $down=$blocks["id"];
                                                switch($blocks2["position"]){
                                                    case "left":
                                                        $pos = T_("_LEFT_");
                                                        break;
                                                    case "middle":
                                                        $pos = T_("_MIDDLE_");
                                                        break;
                                                    case "right":
                                                        $pos = T_("_RIGHT_");
                                                        break;
                                                }

                                                print("<tr>".     # id=\"qq"\" - removed
                                                    "<td rowspan=\"2\"><input type=\"text\" name=\"named_".$blocks2["id"]."\" value=\"".($blocks2["named"] ? $blocks2["named"] : $blocks2["name"])."\" /></td>".
                                                    "<td rowspan=\"2\">".$blocks2["name"]."</td>".
                                                    "<td rowspan=\"2\"><textarea name=\"description_".$blocks2["id"]."\" rows=\"2\" cols=\"20\">".$blocks2["description"]."</textarea></td>".
                                                    "<td colspan=\"3\" align=\"center\">".$pos."</td>".
                                                    "<td colspan=\"2\" align=\"center\">".$blocks2["sort"]."</td>".
                                                    "<td rowspan=\"2\" align=\"center\"><input type=\"radio\" name=\"enable_".$blocks2["id"]."\"".($blocks2["enabled"] ? " checked=\"checked\"" : "")." value=\"1\" /></td>".
                                                    "<td rowspan=\"2\" align=\"center\"><input type=\"radio\" name=\"enable_".$blocks2["id"]."\"".(!$blocks2["enabled"] ? " checked=\"checked\"" : "")." value=\"0\" /></td>".
                                                    "<td rowspan=\"2\" align=\"center\"><input type=\"checkbox\" name=\"delete[]\" value=\"".$blocks2["id"]."\"/></td>".
                                                "</tr>".
                                                "<tr>".
                                                    "<td height=\"1%\">".((($blocks2["position"] != "left") && ($blocks2["enabled"] == 1)) ? "<a href=\"blocks-edit.php?edit=true&amp;position=left&amp;left=".$blocks2["id"]."\"><i class=\"fa fa-align-left\" aria-hidden=\"true\"></i></a>" : "<img border=\"0\" src=\"images/blocks/leftdisable.gif\" width=\"18\" height=\"15\" ".($blocks2["enabled"] ? "alt=\"".T_("_AT_LEFT_")."\"" : "alt=\""._MUST_ENB_MOVE_."\"")." ".($blocks2["enabled"] ? "onclick=\"javascript: alert('".T_("_AT_LEFT_")."');\"" : "onclick=\"javascript: alert('".T_("_MUST_ENB_FIRST")."');\"")."  />")."</td>".
                                                    "<td height=\"1%\">".((($blocks2["position"] != "middle") && ($blocks2["enabled"] == 1)) ? "<a href=\"blocks-edit.php?edit=true&amp;position=middle&amp;middle=".$blocks2["id"]."\"><i class=\"fa fa-align-center\" aria-hidden=\"true\"></i></a>" : "<img border=\"0\" src=\"images/blocks/middledisable.gif\" width=\"18\" height=\"15\" ".($blocks2["enabled"] ? "alt=\"".T_("_AT_CENTER_")."\"" : "alt=\""._MUST_ENB_MOVE_."\"")." ".($blocks2["enabled"] ? "onclick=\"javascript: alert('".T_("_AT_CENTER_")."');\"" : "onclick=\"javascript: alert('".T_("_MUST_ENB_FIRST")."');\"")."  />")."</td>".
                                                    "<td height=\"1%\">".((($blocks2["position"] != "right") && ($blocks2["enabled"] == 1)) ? "<a href=\"blocks-edit.php?edit=true&amp;position=right&amp;right=".$blocks2["id"]."\"><i class=\"fa fa-align-right\" aria-hidden=\"true\"></i></a>" : "<i class=\"fa fa-align-right green\" aria-hidden=\"true\" ".($blocks2["enabled"] ? "alt=\"".T_("_AT_RIGHT_")."\"" : "alt=\""._MUST_ENB_MOVE_."\"")." ".($blocks2["enabled"] ? "onclick=\"javascript: alert('".T_("_AT_RIGHT_")."');\"" : "onclick=\"javascript: alert('".T_("_MUST_ENB_FIRST")."');\"")."  ></i>")."</td>".
                                                    "<td height=\"1%\">".((($blocks2["sort"]!= 1) && ($blocks2["enabled"] != 0)) ? "<a href=\"blocks-edit.php?edit=true&amp;position=".$blocks2["position"]."&amp;sort=".$blocks2["sort"]."&amp;up=".$blocks2["id"]."\"><i class=\"fa fa-arrow-up\" aria-hidden=\"true\"></i></a>" : "<i class=\"fa fa-arrow-up\" aria-hidden=\"true\" alt=\"".($blocks2["enabled"] ? "".T_("_AT_TOP_")."" : ""._MUST_ENB_SORT_."")."\" ".($blocks2["enabled"] ? "onclick=\"javascript: alert('".T_("_AT_TOP_")."');\"" : "onclick=\"javascript: alert('".T_("_MUST_ENB_FIRST")."');\"")." ></i>")."</td>".
                                                    "<td height=\"1%\">".(((($blocks2["sort"] != ($nextleft-1)) && ($blocks2["position"] == "left") || ($blocks2["sort"] != ($nextright-1)) && ($blocks2["position"] == "right") || ($blocks2["sort"] != ($nextmiddle-1)) && ($blocks2["position"] == "middle")) && ($blocks2["enabled"] != 0)) ? "<a href=\"blocks-edit.php?edit=true&amp;position=".$blocks2["position"]."&amp;sort=".$blocks2["sort"]."&amp;down=".$blocks2["id"]."\"><i class=\"fa fa-arrow-down\" aria-hidden=\"true\"></i></a>" : "<i class=\"fa fa-arrow-down\" aria-hidden=\"true\" alt=\"".($blocks2["enabled"] ? "".T_("_AT_BOTTOM_")."" : ""._MUST_ENB_SORT_."")."\" ".($blocks2["enabled"] ? "onclick=\"javascript: alert('".T_("_AT_BOTTOM_")."');\"" : "onclick=\"javascript: alert('".T_("_MUST_ENB_FIRST")."');\"")." ></i>")."</td>".
                                                "</tr>");
                                            }
                                            print("<tr>".
                                                "<td colspan=\"11\" align=\"center\" class=\"table_head\"><button type=\"submit\" class=\"btn btn-info\"><i class=\"ace-icon fa fa-check bigger-110\"></i>".T_("_BTN_UPDT_")."</button></td>".
                                            "</tr>".
                                        "</tbody>".
                                    "</form>".
                                "</table>");
                                // ---- </table> for blocks in database -----------------------------------------
                            echo '</div>
                        </div>
                        <div class="page-header">
                            <h1>
                                Blocs
                                <small>
                                    <i class="ace-icon fa fa-angle-double-right"></i>
                                    '.T_("_BLC_AVAIL_").' ('.T_("_IN_FOLDER_").')
                                </small>
                            </h1>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">';
                                // ---- <table> for blocks exist but not in database ----------------------------
                                $exist=SQL_Query_exec("SELECT name FROM blocks");
                                while($fileexist = mysqli_fetch_assoc($exist)){
                                    $indb[] = $fileexist["name"]."_block.php";
                                }

                                if ($folder = opendir('../blocks')) {
                                    while (false !== ($file = readdir($folder))) {
                                        if ($file != "." && $file != ".." && !in_array($file, $indb)) {
                                            if (preg_match("/_block.php/i", $file))
                                                $infolder[] = $file;
                                        }
                                    }
                                    closedir($folder);
                                }

                                if($infolder){
                                    print("<a name=\"anb\"></a>");
                                    echo $success.$delmessage;

                                    echo '<table id="simple-table" class="table  table-bordered table-hover">
                                        <form name="addnewblock" method="post" action="blocks.php#anb">
                                            <thead>
                                                <tr>
                                                    <th>'.T_("_NAMED_").'<br />('.T_("_FL_NM_IF_NO_SET_").')</th>
                                                    <th>'.T_("FILE").'</th>
                                                    <th>'.T_("DESCRIPTION").'<br />('.T_("_MAX_").' 255 '.T_("_CHARS_").')</th>
                                                    <th>'.T_("_ADD_").'</th>
                                                    <th>'.T_("_DEL_").'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                                /* loop over the blocks directory and take file names witch are not in database. */
                                                if ($folder = opendir('../blocks')) {
                                                    $i=0;
                                                    while (false !== ($file = readdir($folder))) {
                                                        if ($file != "." && $file != ".." && !in_array($file, $indb)) {
                                                            if (preg_match("/_block.php/i", $file)){
                                                                print("<tr>".
                                                                    "<td><input type=\"hidden\" name=\"addblock_".$i."\" value=\"".$file."\" /><input type=\"text\" name=\"wantedname_".$i."\" value=\"".str_replace("_block.php","",$file)."\"/></td>".
                                                                    "<td>$file</td>".
                                                                    "<td align=\"center\"><textarea name=\"wanteddescription_".$i."\" rows='1' cols='20'></textarea></td>".
                                                                    "<td align=\"center\"><div id=\"addn_".$i."\" ><input type='checkbox' name='addnew[]' value=\"".$i."\" onclick=\"javascript: if(dltp_".$i.".style.display=='none'){dltp_".$i.".style.display='block'}else{dltp_".$i.".style.display='none'}; \" /></div></td>".
                                                                    "<td align=\"center\"><div id=\"dltp_".$i."\" ><input type='checkbox' name='deletepermanent[]' value=\"".$file."\" onclick=\"javascript: if(addn_".$i.".style.display=='none'){addn_".$i.".style.display='block'}else{addn_".$i.".style.display='none'}\" /></div></td>".
                                                                "</tr>");
                                                                $i++;
                                                            }
                                                        }
                                                    }
                                                    closedir($folder);
                                                }
                                                /* end loop over the blocks directory and take names. */

                                                print("<tr>".
                                                    "<td colspan=\"5\" class=\"table_head\" align=\"center\"><button type=\"submit\" name=\"submit\" class=\"btn btn-info\"><i class=\"ace-icon fa fa-check bigger-110\"></i>".T_("_BTN_DOIT_")."</button>&nbsp; &nbsp; &nbsp;<button class=\"btn\" type=\"reset\"><i class=\"ace-icon fa fa-undo bigger-110\"></i>".T_("RESET")."</button>".
                                                    "<br />(".T_("_DLT_WIL_PER_")." <font color='#ff0000'>".T_("_NO_ADD_WAR_")."</font>)</td>".
                                                "</tr>".
                                            "</tbody>".
                                        "</form>".
                                    "</table>");
                                }
                                // ---- </table> for blocks exist but not in database ----------------------------
                            echo '</div>
                        </div>
                        <div class="page-header">
                            <h1>
                                Blocs
                                <small>
                                    <i class="ace-icon fa fa-angle-double-right"></i>
                                    '.T_("_BLC_UPL_").'
                                </small>
                            </h1>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">';
                                // ---- <table> for upload block -------------------------------------------------
                                print("<a name=\"upload\"></a>");
                                if($_POST["upload"]){
                                    if($uplfailmessage){
                                        echo $uplfailmessage;
                                    }else{
                                        echo $uplsuccessmessage;
                                    }
                                }

                                echo '<form class="form-horizontal" role="form" enctype="multipart/form-data" action="blocks-edit.php#upload" method="post">
                                    <input type="hidden" name="upload" value="true" />
                                    <div class="form-group">
								        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> '.T_("_NAMED_").' </label>
									    <div class="col-sm-9">
										    <input type="text" id="form-field-1" placeholder="'.T_("_NAMED_").'" class="col-xs-10 col-sm-5" name="wantedname"/>
										    <span class="help-inline col-xs-12 col-sm-7">
											    <span class="middle">('.T_("_FL_NM_IF_NO_SET_").')</span>
										    </span>
									    </div>
								    </div>
								    <div class="space-4"></div>
								    <div class="form-group">
									    <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> '.T_("DESCRIPTION").' </label>
									    <div class="col-sm-9">
                                            <textarea class="form-control input-xlarge" id="form-field-2" placeholder="'.T_("DESCRIPTION").'" name="description" ></textarea>
                                            <span class="help-inline col-xs-12 col-sm-7">
											    <span class="middle">('.T_("_MAX_").' 255 '.T_("_CHARS_").')</span>
										    </span>
                                        </div>
								    </div>
								    <div class="space-4"></div>
								    <div class="form-group">
								        <label class="col-sm-3 control-label no-padding-right" for="id-input-file-1"> '.T_("FILE").' </label>
										<div class="col-sm-9">
											<input type="file" id="id-input-file-1" name="blockupl"/>
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
								        <label class="col-sm-3 control-label no-padding-right"> '.T_("_POSITION_").' </label>
										<div class="radio">
											<label>
												<input name="form-field-radio" type="radio" class="ace"  name="position" checked="checked" value="left" onclick="javascript: if(enabledyes.checked){uplsort.value = \''.$nextleft.'\';}else{uplsort.value = \'0\';}" />
												<span class="lbl"> Left </span>
											</label>
											<label>
												<input name="form-field-radio" type="radio" class="ace" name="position" value="middle" onclick="javascript: if(enabledyes.checked){uplsort.value = \''.$nextmiddle.'\';}else{uplsort.value = \'0\';}" />
												<span class="lbl"> Middle </span>
											</label>
											<label>
												<input name="form-field-radio" type="radio" class="ace" name="position" value="right" onclick="javascript: if(enabledyes.checked){uplsort.value = \''.$nextright.'\';}else{uplsort.value = \'0\';}" />
												<span class="lbl"> Right </span>
											</label>
										</div>
									</div>
									<div class="space-4"></div>
								    <div class="form-group">
								        <label class="col-sm-3 control-label no-padding-right" for="spinner1"> '.T_("_SORT_").' </label>
										<div class="col-sm-9">
									        <input type="text" id="spinner1" name="uplsort" size="1" readonly="readonly" value="0" style="text-align: center;" onclick="javascript: alert(\''.T_("_CLICK_POS_").'\');" />
									    </div>
									</div>
									<div class="space-4"></div>
								    <div class="form-group">
								        <label class="col-sm-3 control-label no-padding-right"> '.T_("ENABLED").' </label>
								        <div class="col-sm-9">
									        <label>
												<input type="checkbox" name="enabledyes" onclick="javascript: uploadonly.disabled = enabledyes.checked; if(enabledyesnotice.style.display == \'block\'){enabledyesnotice.style.display = \'none\'}else{enabledyesnotice.style.display = \'block\'}; if(!checked){uplsort.value = \'0\'}" />
											</label>
									    </div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
									    <label class="col-sm-3 control-label no-padding-right"> '.T_("_JUST_UPL_").' </label>
								        <div class="col-sm-9">
									        <input type="checkbox" name="uploadonly" onclick="javascript: wantedname.disabled = enabledyes.disabled = description.disabled = pos.disabled = uploadonly.checked; if(uploadonlynotice.style.display == \'block\'){uploadonlynotice.style.display = \'none\'}else{uploadonlynotice.style.display = \'block\'};" />
										</div>
									</div>
									<div class="form-actions center">
										<button type="submit" class="btn btn-sm btn-success">
											'.T_("UPLOAD").'
											<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
										</button>
										<div id="uploadonlynotice" style="display: none;">('.T_("_UPL_ONLY_").')</div>
										<div id="enabledyesnotice" style="display: none;">('.T_("_UPL_ADD_").')</div>
									</div>
                                </form>';
                                // ---- </table> for upload block -------------------------------------------------
                            echo '</div>
                        </div>';
                    }else{
                        echo '<div class="row">
                            <div class="col-xs-12">';
                                echo '<table id="simple-table" class="table  table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="detail-col">Preview</th>
                                        <th>' . T_("NAME") . '</th>
                                        <th>Description</th>
                                        <th class="hidden-480">Position</th>
                                        <th>Sort Order</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>';
                                ///////////////// BLOCKS MANAGEMENT /////////////
                                    echo '<tr>
                                        <td colspan="6" align="center">
                                            ..:: Enabled Blocs ::..
                                        </td>
                                    </tr>';
                                    $enabled = SQL_Query_exec("SELECT named, name, description, position, sort FROM blocks WHERE enabled=1 ORDER BY position, sort");
                                    $disabled = SQL_Query_exec("SELECT named, name, description, position, sort FROM blocks WHERE enabled=0 ORDER BY position, sort");

                                    while ($blocks = mysqli_fetch_assoc($enabled)) {
                                        echo '<tr>
                                            <td align="center">[<a href="blocks-edit.php?preview=true&amp;name=' . $blocks["name"] . '#' . $blocks["name"] . '" target="_blank">preview</a>]</td>
                                            <td valign="top">' . $blocks["named"] . '</td>
                                            <td>' . $blocks["description"] . '</td>
                                            <td align="center">' . $blocks["position"] . '</td>
                                            <td align="center">' . $blocks["sort"] . '</td>
                                            <td>
								    			<div class="hidden-sm hidden-xs btn-group">
								    			    <form action="blocks.php" method="post">
									    			    <button class="btn btn-xs btn-success" name="edit" type="submit" value="edit">
										    			    <i class="ace-icon fa fa-check bigger-120"></i>
											    	    </button>

												        <button class="btn btn-xs btn-info">
													        <i class="ace-icon fa fa-pencil bigger-120"></i>
												        </button>

        												<button class="btn btn-xs btn-danger">
	        												<i class="ace-icon fa fa-trash-o bigger-120"></i>
		        										</button>
    
	    	    										<button class="btn btn-xs btn-warning">
		    	    										<i class="ace-icon fa fa-flag bigger-120"></i>
			    	    								</button>
			    	    							</form>
				    							</div>

					    						<div class="hidden-md hidden-lg">
						    						<div class="inline pos-rel">
						    						    <form action="blocks.php" method="post">
							    						    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" type="submit" value="Edit">
								    						    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
									    				    </button>

    										    			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
	    										    			<li>
		    										    			<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
			    										    			<span class="blue">
				    										    			<i class="ace-icon fa fa-search-plus bigger-120"></i>
					    										    	</span>
    					    										</a>
	    					    								</li>
    
	    						    							<li>
		    						    							<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
			    						    							<span class="green">
				    						    							<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
					    						    					</span>
						    						    			</a>
							    						    	</li>

    														    <li>
	    														    <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
		    														    <span class="red">
			    														    <i class="ace-icon fa fa-trash-o bigger-120"></i>
				    												    </span>
					    										    </a>
						    								    </li>
							    						    </ul>
							    						</form>
								    				</div>
									    		</div>
										    </td>
                                        </tr>';
                                    }
                                    //Break For Disabled blocs
                                    echo '<tr>
                                        <td colspan="6" align="center">
                                            ..:: Disabled Blocs ::..
                                        </td>
                                    </tr>';
                                    while ($blocks = mysqli_fetch_assoc($disabled)) {
                                        echo '<tr>
                                            <td>' . $blocks["named"] . '</td>
                                            <td>' . $blocks["description"] . '</td>
                                            <td align="center">' . $blocks["position"] . '</td>
                                            <td align="center">' . $blocks["sort"] . '</td>
                                            <td align="center">[<a href="blocks-edit.php?preview=true&amp;name=' . $blocks["name"] . '#' . $blocks["name"] . '" target="_blank">preview</a>]</td>
                                        </tr>';
                                    }
                                    print("<tr><td colspan=\"6\" align=\"center\" valign=\"bottom\" class=\"table_head\"><form action='blocks.php' method=\"post\"><button class=\"btn btn-xs btn-success\" name =\"edit\" type=\"submit\" value=\"edit\">Edit</button></form></td></tr>");
                                echo '</tbody>';
                                print("</table>");
                        echo '</div>
                        </div>';
                            }
                    ?><!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<?php
include("footer.php");
?>