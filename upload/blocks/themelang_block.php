 <?php
if ($CURUSER){
    begin_block(T_("THEME")." / ".T_("LANGUAGE"));

    $ss_r = SQL_Query_exec("SELECT * from stylesheets");
    $ss_sa = array();

    while ($ss_a = mysqli_fetch_assoc($ss_r)){
        $ss_id = $ss_a["id"];
        $ss_name = $ss_a["name"];
        $ss_sa[$ss_name] = $ss_id;
    }

    ksort($ss_sa);
    reset($ss_sa);
    
    while (list($ss_name, $ss_id) = each($ss_sa)){
        if ($ss_id == $CURUSER["stylesheet"]) $ss = " selected='selected'"; else $ss = "";
        $stylesheets .= "<option value='$ss_id'$ss>$ss_name</option>\n";
    }

?>
 
 <form method="post" action="take-theme.php">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
<td align="center" valign="middle"><b><?php echo T_("THEME"); ?></b>
<select name="stylesheet"><?php echo $stylesheets; ?></select></td>
  </tr>
  <tr>
<td align="center" valign="middle"><input type="submit" value="<?php echo T_("APPLY"); ?>" /></td>
  </tr>
</table>
  </form>  

<?php
end_block();
}
?> 
