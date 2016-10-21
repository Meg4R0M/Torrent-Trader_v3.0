<?php
//
//  TorrentTrader v3.x
//      $LastChangedDate: 2016-10-21 14:55:35 +0100 (Fri, 21 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

require_once("backend/functions.php");
dbconn();

stdhead(T_("FAQ"));

$faq_categ = null;

$res = SQL_Query_exec("SELECT `id`, `question`, `flag` FROM `faq` WHERE `type`='categ' ORDER BY `order` ASC");
while ($arr = mysqli_fetch_array($res,  MYSQLI_BOTH)) {
    $faq_categ[$arr[id]][title] = $arr[question];
    $faq_categ[$arr[id]][flag] = $arr[flag];
}

$res = SQL_Query_exec("SELECT `id`, `question`, `answer`, `flag`, `categ` FROM `faq` WHERE `type`='item' ORDER BY `order` ASC");
while ($arr = mysqli_fetch_array($res,  MYSQLI_BOTH)) {
    $faq_categ[$arr[categ]][items][$arr[id]][question] = $arr[question];
    $faq_categ[$arr[categ]][items][$arr[id]][answer] = $arr[answer];
    $faq_categ[$arr[categ]][items][$arr[id]][flag] = $arr[flag];
}

if (isset($faq_categ)) {
    // gather orphaned items
    foreach ($faq_categ as $id => $temp) {
        if (!array_key_exists("title", $faq_categ[$id])) {
            foreach ($faq_categ[$id][items] as $id2 => $temp) {
                $faq_orphaned[$id2][question] = $faq_categ[$id][items][$id2][question];
                $faq_orphaned[$id2][answer] = $faq_categ[$id][items][$id2][answer];
                $faq_orphaned[$id2][flag] = $faq_categ[$id][items][$id2][flag];
                unset($faq_categ[$id]);
            }
        }
    }

    echo '<div class="tableHeader">
    	<div class="row">
	    	<div class="cell first">Search FAQ</div>
        </div>
    </div>

    <div class="torrent-box" id="search_faq">

	    <form method="post" action="http://templateshares-ue.net/tsue/?p=faq&amp;pid=12" name="form_search_faq" id="form_search_faq">
		    <input type="hidden" name="action" value="search" />
		    Keyword(s): <input type="text" name="keywords" id="keywords" class="s" accesskey="s" value="" /> 
		    <input type="submit" value="Search" class="submit" />
        </form>

    </div>';

    echo '<div class="tableHeader">
	    <div class="row">
		    <div class="cell first">Frequently Asked Questions</div>
        </div>
    </div>';

    echo '<div class="table">

	    <div class="row">
		    <div class="cell">
                Here you can find the Frequently Asked Questions which will be updated on a regular basis as and when appropriate. Please read through it as it contains important information you need to be aware of along with essential reading such as User Guides and many other cool stuff you may not even be aware of.
			    <div id="google_translate_element"></div>
		    </div>
	    </div>';

        echo '<div class="row">
		    <div class="cell">
			    <ul>';
			        foreach ($faq_categ as $id => $temp) {
                        if ($faq_categ[$id][flag] == "1") {
                            echo '<li>

	                            <div id="faq_category_'.$id.'">
		                            <a href="faq.php?id='.$id.'" rel="FAQCategory" cid="'.$id.'">'.stripslashes($faq_categ[$id][title]).'</a>
                            	</div>
                                <div id="faq_items_'.$id.'"  class="hidden">
                    	            <ol>';
                                        if (array_key_exists("items", $faq_categ[$id])) {
                                            foreach ($faq_categ[$id][items] as $id2 => $temp) {
                                                echo '<li>';
                                                    if ($faq_categ[$id][items][$id2][flag] == "1")
                                   	                    echo '<a href="faq.php?id='.$id.'" rel="faq_item" fid="'.$id2.'">'.stripslashes($faq_categ[$id][items][$id2][question]).'</a>';
                                                    elseif ($faq_categ[$id][items][$id2][flag] == "2")
                                                        echo '<a href="faq.php?id='.$id.'" rel="faq_item" fid="'.$id2.'">'.stripslashes($faq_categ[$id][items][$id2][question]).'</a> <img src="'.$site_config["SITEURL"].'/images/faq/updated.png" alt="Updated" width="46" height="13" align="bottom" />';
                                                    elseif ($faq_categ[$id][items][$id2][flag] == "3")
                                                        echo '<a href="faq.php?id='.$id.'" rel="faq_item" fid="'.$id2.'">'.stripslashes($faq_categ[$id][items][$id2][question]).'</a> <img src="'.$site_config["SITEURL"].'/images/faq/new.png" alt="New" width="25" height="12" align="bottom" />';
                               	                    echo '<div class="hidden" id="faq_item_'.$id2.'">';
                                                        if (array_key_exists("items", $faq_categ[$id])) {
                                                            echo '<p>' . stripslashes($faq_categ[$id][items][$id2][answer]) . '</p>';
                                                        }
                               	                    echo '</div>
                                                </li>';
                                            }
                                        }
                    	            echo '</ol>
                    	        </div>
                    	    </li>';
                        }
                    }
                echo '</ul>
            </div>
        </div>';

    // End Table
    echo '</div>';

}

stdfoot();
?>