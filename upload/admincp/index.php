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
                </div><!-- /.ace-settings-container -->

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-sm-6 well">
                        <?php
                        //Get Last Cleanup
                        $res = SQL_Query_exec("SELECT last_time FROM tasks WHERE task = 'cleanup'");
                        $row = mysqli_fetch_row($res);
                        if (!$row){
                            $lastclean="never done...";
                        }else{
                            $row[0]=gmtime()-$row[0]; $days=intval($row[0] / 86400);$row[0]-=$days*86400;
                            $hours=intval($row[0] / 3600); $row[0]-=$hours*3600; $mins=intval($row[0] / 60);
                            $secs=$row[0]-($mins*60);
                            $lastclean = "$days days, $hours hrs, $mins minutes, $secs seconds ago.";
                        }

                        echo '<p style="text-align: center;">';
                        print "<b>Last cleanup performed: </b>".$lastclean."<br />[<a href='admincp.php?action=forceclean'>".T_("FORCE_CLEAN")."</a>]";

                        if ($site_config["ttversion"] != "2-svn") {
                            $file = @file_get_contents('https://www.torrenttrader.org/tt2version.php');
                            if ($site_config['ttversion'] >= $file){
                                echo "<br /><b>".T_("YOU_HAVE_LATEST_VER_INSTALLED")." v$site_config[ttversion]</b>";
                            }else{
                                echo "<br /><b><font class='error'>".T_("NEW_VERSION_OF_TT_NOW_AVAIL").": v".$file." you have v".$site_config['ttversion']."<br /> Please visit <a href=http://www.torrenttrader.org>TorrentTrader.org</a> to upgrade.</b>";
                            }
                        }

                        $res = SQL_Query_exec("SELECT VERSION() AS mysql_version");
                        $row = mysqli_fetch_assoc($res);
                        $mysqlver = $row['mysql_version'];
                        $pending = get_row_count("users", "WHERE status = 'pending' AND invited_by = '0'");
                        echo "<br /><b>".T_("USERS_AWAITING_VALIDATION").":</b> <a href='admincp.php?action=confirmreg'>($pending)</a><br />";
                        echo "<b>".T_("VERSION_MYSQL").": </b>" . $mysqlver . "<br /><b>".T_("VERSION_PHP").": </b>" . phpversion() . "<br />";
                        if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN") {
                            if (!class_exists("COM"))
                                echo "COM support not available.";
                            else {
                                function mkprettytime2($s){
                                    foreach (array("60:sec","60:min","24:hour","1:day") as $x) {
                                        $y = explode(":", $x);
                                        if ($y[0] > 1) {
                                            $v = $s % $y[0];
                                            $s = floor($s / $y[0]);
                                        } else
                                            $v = $s;
                                        $t[$y[1]] = $v;
                                    }

                                    if ($t['week'] > 1 || $t['week'] == 0) $wk = " weeks";
                                    else $wk = " week";
                                    if ($t['day'] > 1 || $t['day'] == 0) $day = " days";
                                    else $day = " day";
                                    if ($t['hour'] > 1 || $t['hour'] == 0) $hr = " hrs";
                                    else $hr = " hr";
                                    if ($t['min'] > 1 || $t['min'] == 0) $min = " mins";
                                    else $min = " min";
                                    if ($t['sec'] > 1 || $t['sec'] == 0) $sec = " secs";
                                    else $sec = " sec";

                                    if ($t["month"])
                                        return "{$t['month']}$mth {$t['week']}$wk {$t['day']}$day ".sprintf("%d$hr %02d$min %02d$sec", $t["hour"], $t["min"], $t["sec"], $f["month"]);
                                    if ($t["week"])
                                        return "{$t['week']}$wk {$t['day']}$day ".sprintf("%d$hr %02d$min %02d$sec", $t["hour"], $t["min"], $t["sec"], $f["month"]);
                                    if ($t["day"])
                                        return "{$t['day']}$day ".sprintf("%d$hr %02d$min %02d$sec", $t["hour"], $t["min"], $t["sec"]);
                                    if ($t["hour"])
                                        return sprintf("%d$hr %02d$min %02d$sec", $t["hour"], $t["min"], $t["sec"]);
                                    if ($t["min"])
                                        return sprintf("%d$min %02d$sec", $t["min"], $t["sec"]);
                                    return $t["sec"].$sec;
                                }

                                if (version_compare(PHP_VERSION, '5.0.0', '<'))
                                    require("../backend/serverload4.php");
                                else
                                    require("../backend/serverload5.php");
                            }
                        } else {
                            // Users and load information
                            $reguptime = exec("uptime");
                            if ($reguptime) {
                                if (preg_match("/up (.*), *(\d) (users?), .*: (.*), (.*), (.*)/", $reguptime, $uptime)) {
                                    $up = preg_replace("!(\d\d):(\d\d)!", '\1h\2m', $uptime[1]);
                                    $users[0] = $uptime[2];
                                    $users[1] = $uptime[3];
                                    $loadnow = $uptime[4];
                                    $load5 = $uptime[5];
                                    $load15 = $uptime[6];
                                }
                            } else {
                                $up = "--";
                                $users[0] = "NA";
                                $users[1] = "--";
                                $loadnow = "NA";
                                $load5 = "--";
                                $load15 = "--";
                            }

                            // RAM usage
                            $meminfo = file_get_contents("/proc/meminfo");
                            preg_match("!^MemTotal:\s*(.*) kB!m", $meminfo, $memtotal);
                            $memtotal = $memtotal[1] * 1024;
                            preg_match("!^MemFree:\s*(.*) kB!m", $meminfo, $memfree);
                            $memfree = $memfree[1] * 1024;
                            preg_match("!^Buffers:\s*(.*) kB!m", $meminfo, $buffers);
                            $buffers = $buffers[1] * 1024;
                            preg_match("!^Cached:\s*(.*) kB!m", $meminfo, $cached);
                            $cached = $cached[1] * 1024;

                            $memused = mksize($memtotal - $memfree - $buffers - $cached);
                            $memtotal = mksize($memtotal);

                            //echo("<b>Current Users:</b> $users[0]<br>

                            // Get Number of core
                            function shapeSpace_system_cores() {

                                $cmd = "uname";
                                $OS = strtolower(trim(shell_exec($cmd)));

                                switch($OS) {
                                    case('linux'):
                                        $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
                                        break;
                                    case('freebsd'):
                                        $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
                                        break;
                                    default:
                                        unset($cmd);
                                }

                                if ($cmd != '') {
                                    $cpuCoreNo = intval(trim(shell_exec($cmd)));
                                }

                                return empty($cpuCoreNo) ? 1 : $cpuCoreNo;

                            }
                            // Get Load in Percentage
                            function shapeSpace_system_load($coreCount = 2, $interval = 1) {
                                $rs = sys_getloadavg();
                                $interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
                                $load = $rs[$interval];
                                return round(($load * 100) / $coreCount,2);
                            }
                            echo '<b>CPU</b> ('.shapeSpace_system_cores().' Core(s)) <b>Charge: </b>'.shapeSpace_system_load().'% ('.$loadnow.' - '.$load5.' - '.$load15.')';

                            echo("<br /><b>OS:</b> ".php_uname("v")."<br />");
                            echo("<b>".T_("RAM_USED").":</b> $memused/$memtotal<br />");
                            echo("<b>".T_("UPTIME").":</b> $up<br />");

                        }
                        echo '</p>';

                        ?></div>
                            <div class="space-6"></div>
                            <div class="space-6"></div>

                            <div class="col-sm-6 infobox-container">
                                <div class="infobox infobox-green">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-comments"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">32</span>
                                        <div class="infobox-content">comments + 2 reviews</div>
                                    </div>

                                    <div class="stat stat-success">8%</div>
                                </div>

                                <div class="infobox infobox-blue">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-twitter"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">11</span>
                                        <div class="infobox-content">new followers</div>
                                    </div>

                                    <div class="badge badge-success">
                                        +32%
                                        <i class="ace-icon fa fa-arrow-up"></i>
                                    </div>
                                </div>

                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-users"></i>
                                    </div><?php
                                    function shapeSpace_http_connections() {

                                    if (function_exists('exec')) {

                                    $www_total_count = 0;
                                    @exec ('netstat -an | egrep \':80|:443\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);

                                    foreach ($results as $result) {
                                    $array = explode(':', $result);
                                    $www_total_count ++;

                                    if (preg_match('/^::/', $result)) {
                                    $ipaddr = $array[3];
                                    } else {
                                    $ipaddr = $array[0];
                                    }

                                    if (!in_array($ipaddr, $unique)) {
                                    $unique[] = $ipaddr;
                                    $www_unique_count ++;
                                    }
                                    }

                                    unset ($results);

                                    return count($unique);

                                    }

                                    }
                                    echo '<div class="infobox-data">
                                        <span class="infobox-data-number">'.shapeSpace_http_connections().'</span>
                                        <div class="infobox-content">connected</div>
                                    </div>';
                                    //<div class="stat stat-important">4%</div>
                                echo '</div>';

                                ?><div class="infobox infobox-red">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-flask"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">7</span>
                                        <div class="infobox-content">experiments</div>
                                    </div>
                                </div>

                                <div class="infobox infobox-orange2">
                                    <div class="infobox-chart">
                                        <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">6,251</span>
                                        <div class="infobox-content">pageviews</div>
                                    </div>

                                    <div class="badge badge-success">
                                        7.2%
                                        <i class="ace-icon fa fa-arrow-up"></i>
                                    </div>
                                </div>

                                <div class="infobox infobox-blue2">
                                    <div class="infobox-progress">
                                        <div class="easy-pie-chart percentage" data-percent="42" data-size="46">
                                            <span class="percent">42</span>%
                                        </div>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-text">traffic used</span>

                                        <div class="infobox-content">
                                            <span class="bigger-110">~</span>
                                            58GB remaining
                                        </div>
                                    </div>
                                </div>

                                <div class="space-6"></div>

                                <div class="infobox infobox-green infobox-small infobox-dark">
                                    <div class="infobox-progress">
                                        <div class="easy-pie-chart percentage" data-percent="61" data-size="39">
                                            <span class="percent">61</span>%
                                        </div>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">Task</div>
                                        <div class="infobox-content">Completion</div>
                                    </div>
                                </div>

                                <div class="infobox infobox-blue infobox-small infobox-dark">
                                    <div class="infobox-chart">
                                        <span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">Earnings</div>
                                        <div class="infobox-content">$32,000</div>
                                    </div>
                                </div>

                                <div class="infobox infobox-grey infobox-small infobox-dark">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-download"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">Downloads</div>
                                        <div class="infobox-content">1,205</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr hr32 hr-dotted"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="widget-box transparent" id="recent-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title lighter smaller">
                                            <i class="ace-icon fa fa-rss orange"></i>RECENT
                                        </h4>

                                        <div class="widget-toolbar no-border">
                                            <ul class="nav nav-tabs" id="recent-tab">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#task-tab">Tasks</a>
                                                </li>

                                                <li>
                                                    <a data-toggle="tab" href="#member-tab">Members</a>
                                                </li>

                                                <li>
                                                    <a data-toggle="tab" href="#comment-tab">Comments</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-4">
                                            <div class="tab-content padding-8">
                                                <div id="task-tab" class="tab-pane active">
                                                    <h4 class="smaller lighter green">
                                                        <i class="ace-icon fa fa-list"></i>
                                                        Sortable Lists
                                                    </h4>

                                                    <ul id="tasks" class="item-list">
                                                        <li class="item-orange clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Answering customer questions</span>
                                                            </label>

                                                            <div class="pull-right easy-pie-chart percentage" data-size="30" data-color="#ECCB71" data-percent="42">
                                                                <span class="percent">42</span>%
                                                            </div>
                                                        </li>

                                                        <li class="item-red clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Fixing bugs</span>
                                                            </label>

                                                            <div class="pull-right action-buttons">
                                                                <a href="#" class="blue">
                                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                </a>

                                                                <span class="vbar"></span>

                                                                <a href="#" class="red">
                                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                                </a>

                                                                <span class="vbar"></span>

                                                                <a href="#" class="green">
                                                                    <i class="ace-icon fa fa-flag bigger-130"></i>
                                                                </a>
                                                            </div>
                                                        </li>

                                                        <li class="item-default clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Adding new features</span>
                                                            </label>

                                                            <div class="pull-right pos-rel dropdown-hover">
                                                                <button class="btn btn-minier bigger btn-primary">
                                                                    <i class="ace-icon fa fa-cog icon-only bigger-120"></i>
                                                                </button>

                                                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-caret dropdown-close dropdown-menu-right">
                                                                    <li>
                                                                        <a href="#" class="tooltip-success" data-rel="tooltip" title="Mark&nbsp;as&nbsp;done">
																					<span class="green">
																						<i class="ace-icon fa fa-check bigger-110"></i>
																					</span>
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																					<span class="red">
																						<i class="ace-icon fa fa-trash-o bigger-110"></i>
																					</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>

                                                        <li class="item-blue clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Upgrading scripts used in template</span>
                                                            </label>
                                                        </li>

                                                        <li class="item-grey clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Adding new skins</span>
                                                            </label>
                                                        </li>

                                                        <li class="item-green clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Updating server software up</span>
                                                            </label>
                                                        </li>

                                                        <li class="item-pink clearfix">
                                                            <label class="inline">
                                                                <input type="checkbox" class="ace" />
                                                                <span class="lbl"> Cleaning up</span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div id="member-tab" class="tab-pane">
                                                    <div class="clearfix">
                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Bob Doe's avatar" src="assets/images/avatars/user.jpg" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Bob Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">20 min</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-warning label-sm">pending</span>

                                                                    <div class="inline position-relative">
                                                                        <button class="btn btn-minier btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                            <i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
                                                                        </button>

                                                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                            <li>
                                                                                <a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																							<span class="green">
																								<i class="ace-icon fa fa-check bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																							<span class="orange">
																								<i class="ace-icon fa fa-times bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																							<span class="red">
																								<i class="ace-icon fa fa-trash-o bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Joe Doe's avatar" src="assets/images/avatars/avatar2.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Joe Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">1 hour</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-warning label-sm">pending</span>

                                                                    <div class="inline position-relative">
                                                                        <button class="btn btn-minier btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                            <i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
                                                                        </button>

                                                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                            <li>
                                                                                <a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																							<span class="green">
																								<i class="ace-icon fa fa-check bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																							<span class="orange">
																								<i class="ace-icon fa fa-times bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																							<span class="red">
																								<i class="ace-icon fa fa-trash-o bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Jim Doe's avatar" src="assets/images/avatars/avatar.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Jim Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">2 hour</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-warning label-sm">pending</span>

                                                                    <div class="inline position-relative">
                                                                        <button class="btn btn-minier btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                                                            <i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
                                                                        </button>

                                                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                            <li>
                                                                                <a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																							<span class="green">
																								<i class="ace-icon fa fa-check bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																							<span class="orange">
																								<i class="ace-icon fa fa-times bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>

                                                                            <li>
                                                                                <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																							<span class="red">
																								<i class="ace-icon fa fa-trash-o bigger-110"></i>
																							</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Alex Doe's avatar" src="assets/images/avatars/avatar5.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Alex Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">3 hour</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-danger label-sm">blocked</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Bob Doe's avatar" src="assets/images/avatars/avatar2.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Bob Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">6 hour</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-success label-sm arrowed-in">approved</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Susan's avatar" src="assets/images/avatars/avatar3.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Susan</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">yesterday</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-success label-sm arrowed-in">approved</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Phil Doe's avatar" src="assets/images/avatars/avatar4.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Phil Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">2 days ago</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-info label-sm arrowed-in arrowed-in-right">online</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv memberdiv">
                                                            <div class="user">
                                                                <img alt="Alexa Doe's avatar" src="assets/images/avatars/avatar1.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Alexa Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">3 days ago</span>
                                                                </div>

                                                                <div>
                                                                    <span class="label label-success label-sm arrowed-in">approved</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="space-4"></div>

                                                    <div class="center">
                                                        <i class="ace-icon fa fa-users fa-2x green middle"></i>

                                                        &nbsp;
                                                        <a href="#" class="btn btn-sm btn-white btn-info">
                                                            See all members &nbsp;
                                                            <i class="ace-icon fa fa-arrow-right"></i>
                                                        </a>
                                                    </div>

                                                    <div class="hr hr-double hr8"></div>
                                                </div><!-- /.#member-tab -->

                                                <div id="comment-tab" class="tab-pane">
                                                    <div class="comments">
                                                        <div class="itemdiv commentdiv">
                                                            <div class="user">
                                                                <img alt="Bob Doe's Avatar" src="assets/images/avatars/avatar.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Bob Doe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="green">6 min</span>
                                                                </div>

                                                                <div class="text">
                                                                    <i class="ace-icon fa fa-quote-left"></i>
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
                                                                </div>
                                                            </div>

                                                            <div class="tools">
                                                                <div class="inline position-relative">
                                                                    <button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                                        <i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
                                                                    </button>

                                                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                                        <li>
                                                                            <a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																						<span class="green">
																							<i class="ace-icon fa fa-check bigger-110"></i>
																						</span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																						<span class="orange">
																							<i class="ace-icon fa fa-times bigger-110"></i>
																						</span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																						<span class="red">
																							<i class="ace-icon fa fa-trash-o bigger-110"></i>
																						</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv commentdiv">
                                                            <div class="user">
                                                                <img alt="Jennifer's Avatar" src="assets/images/avatars/avatar1.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Jennifer</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="blue">15 min</span>
                                                                </div>

                                                                <div class="text">
                                                                    <i class="ace-icon fa fa-quote-left"></i>
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
                                                                </div>
                                                            </div>

                                                            <div class="tools">
                                                                <div class="action-buttons bigger-125">
                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-pencil blue"></i>
                                                                    </a>

                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-trash-o red"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv commentdiv">
                                                            <div class="user">
                                                                <img alt="Joe's Avatar" src="assets/images/avatars/avatar2.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Joe</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="orange">22 min</span>
                                                                </div>

                                                                <div class="text">
                                                                    <i class="ace-icon fa fa-quote-left"></i>
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
                                                                </div>
                                                            </div>

                                                            <div class="tools">
                                                                <div class="action-buttons bigger-125">
                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-pencil blue"></i>
                                                                    </a>

                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-trash-o red"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="itemdiv commentdiv">
                                                            <div class="user">
                                                                <img alt="Rita's Avatar" src="assets/images/avatars/avatar3.png" />
                                                            </div>

                                                            <div class="body">
                                                                <div class="name">
                                                                    <a href="#">Rita</a>
                                                                </div>

                                                                <div class="time">
                                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                                    <span class="red">50 min</span>
                                                                </div>

                                                                <div class="text">
                                                                    <i class="ace-icon fa fa-quote-left"></i>
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
                                                                </div>
                                                            </div>

                                                            <div class="tools">
                                                                <div class="action-buttons bigger-125">
                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-pencil blue"></i>
                                                                    </a>

                                                                    <a href="#">
                                                                        <i class="ace-icon fa fa-trash-o red"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="hr hr8"></div>

                                                    <div class="center">
                                                        <i class="ace-icon fa fa-comments-o fa-2x green middle"></i>

                                                        &nbsp;
                                                        <a href="#" class="btn btn-sm btn-white btn-info">
                                                            See all comments &nbsp;
                                                            <i class="ace-icon fa fa-arrow-right"></i>
                                                        </a>
                                                    </div>

                                                    <div class="hr hr-double hr8"></div>
                                                </div>
                                            </div>
                                        </div><!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div><!-- /.widget-box -->
                            </div><!-- /.col -->
                            <div class="vspace-12-sm"></div>

                            <div class="col-sm-5">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-flat widget-header-small">
                                        <h5 class="widget-title">
                                            <i class="ace-icon fa fa-signal"></i>
                                            Traffic Sources
                                        </h5>

                                        <div class="widget-toolbar no-border">
                                            <div class="inline dropdown-hover">
                                                <button class="btn btn-minier btn-primary">
                                                    This Week
                                                    <i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
                                                    <li class="active">
                                                        <a href="#" class="blue">
                                                            <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
                                                            This Week
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            Last Week
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            This Month
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            Last Month
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="piechart-placeholder"></div>

                                            <div class="hr hr8 hr-double"></div>

                                            <div class="clearfix">
                                                <div class="grid3">
															<span class="grey">
																<i class="ace-icon fa fa-facebook-square fa-2x blue"></i>
																&nbsp; likes
															</span>
                                                    <h4 class="bigger pull-right">1,255</h4>
                                                </div>

                                                <div class="grid3">
															<span class="grey">
																<i class="ace-icon fa fa-twitter-square fa-2x purple"></i>
																&nbsp; tweets
															</span>
                                                    <h4 class="bigger pull-right">941</h4>
                                                </div>

                                                <div class="grid3">
															<span class="grey">
																<i class="ace-icon fa fa-pinterest-square fa-2x red"></i>
																&nbsp; pins
															</span>
                                                    <h4 class="bigger pull-right">1,050</h4>
                                                </div>
                                            </div>
                                        </div><!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div><!-- /.widget-box -->
                            </div><!-- /.col -->
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
<?php
include("footer.php");
?>