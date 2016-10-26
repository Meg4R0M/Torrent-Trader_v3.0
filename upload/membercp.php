<?php
//
//  TorrentTrader v3.x
//      $LastChangedDate: 2016-10-10 16:40:26 +0100 (Sun, 10 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

require_once("backend/functions.php");
require_once("mailbox-functions.php");
dbconn();
loggedinonly();

stdhead(T_("USERCP"));

$action = $_REQUEST["action"];
$do = $_REQUEST["do"];

if (!$action){
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="personal_details" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">
					'.T_("USER").': '.$CURUSER[username].' ('.T_("ACCOUNT_PROFILE").')
				</div>
			</div>
		</div>

		<div class="table">
	
			<div class="row">
				<div class="cell first">Gender</div>
				<div class="cell second">
					<input type="radio" name="memberinfo_gender" id="memberinfo_gender_male" value="Male"'.($CURUSER["gender"] == "Male" ? " checked='checked'" : "").'/> <label for="memberinfo_gender_male">'.T_("MALE").'</label>
					<input type="radio" name="memberinfo_gender" id="memberinfo_gender_female" value="Female"'.($CURUSER["gender"] == "Female" ? " checked='checked'" : "").'/> <label for="memberinfo_gender_female">'.T_("FEMALE").'</label>
					<input type="radio" name="memberinfo_gender" id="memberinfo_gender_unspecified" value=""'.($CURUSER["gender"] == "" ? " checked='checked'" : "").'/> <label for="memberinfo_gender_unspecified">(unspecified)</label>
				</div>
			</div>

			<div class="row">
				<div class="cell first">Date of Birth</div>
				<div class="cell second">
					<span rel="date_of_birth">'.htmlspecialchars($CURUSER["age"]).'</span>';
					//<span rel="date_of_birth">16/06/1985</span>
					echo '<span class="small">Once your birthday has been entered, it cannot be changed. Please contact an administrator if it is incorrect.</span>
				</div>
			</div>

			<div class="row">
				<div class="cell first">Country</div>';
				$countries = "<option value='0'>----</option>\n";
				$ct_r = SQL_Query_exec("SELECT id,name from countries ORDER BY name");
				while ($ct_a = mysqli_fetch_assoc($ct_r))
					$countries .= "<option value='$ct_a[id]'" . ($CURUSER["country"] == $ct_a['id'] ? " selected='selected'" : "") . ">$ct_a[name]</option>\n";
				?><div class="cell second">
					<img src="http://templateshares-ue.net/tsue/data/countryFlags/noFlag.png" alt="" title="" class="countryMember" /> 
					<div id="countrySelect">
						<div class="close">&nbsp;</div>
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/Kosovo.png" alt="Kosovo" title="Kosovo" class="countryList" id="Kosovo" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/Scotland.png" alt="Scotland" title="Scotland" class="countryList" id="Scotland" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ad.png" alt="ad" title="ad" class="countryList" id="ad" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ae.png" alt="ae" title="ae" class="countryList" id="ae" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/af.png" alt="af" title="af" class="countryList" id="af" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ag.png" alt="ag" title="ag" class="countryList" id="ag" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/al.png" alt="al" title="al" class="countryList" id="al" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/am.png" alt="am" title="am" class="countryList" id="am" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ao.png" alt="ao" title="ao" class="countryList" id="ao" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ar.png" alt="ar" title="ar" class="countryList" id="ar" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/at.png" alt="at" title="at" class="countryList" id="at" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/au.png" alt="au" title="au" class="countryList" id="au" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/az.png" alt="az" title="az" class="countryList" id="az" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ba.png" alt="ba" title="ba" class="countryList" id="ba" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bb.png" alt="bb" title="bb" class="countryList" id="bb" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bd.png" alt="bd" title="bd" class="countryList" id="bd" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/be.png" alt="be" title="be" class="countryList" id="be" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bf.png" alt="bf" title="bf" class="countryList" id="bf" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bg.png" alt="bg" title="bg" class="countryList" id="bg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bh.png" alt="bh" title="bh" class="countryList" id="bh" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bi.png" alt="bi" title="bi" class="countryList" id="bi" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bj.png" alt="bj" title="bj" class="countryList" id="bj" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bn.png" alt="bn" title="bn" class="countryList" id="bn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bo.png" alt="bo" title="bo" class="countryList" id="bo" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/br.png" alt="br" title="br" class="countryList" id="br" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bs.png" alt="bs" title="bs" class="countryList" id="bs" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bt.png" alt="bt" title="bt" class="countryList" id="bt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bw.png" alt="bw" title="bw" class="countryList" id="bw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/by.png" alt="by" title="by" class="countryList" id="by" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/bz.png" alt="bz" title="bz" class="countryList" id="bz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ca.png" alt="ca" title="ca" class="countryList" id="ca" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cd.png" alt="cd" title="cd" class="countryList" id="cd" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cf.png" alt="cf" title="cf" class="countryList" id="cf" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cg.png" alt="cg" title="cg" class="countryList" id="cg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ch.png" alt="ch" title="ch" class="countryList" id="ch" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ci.png" alt="ci" title="ci" class="countryList" id="ci" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cl.png" alt="cl" title="cl" class="countryList" id="cl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cm.png" alt="cm" title="cm" class="countryList" id="cm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cn.png" alt="cn" title="cn" class="countryList" id="cn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/co.png" alt="co" title="co" class="countryList" id="co" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cr.png" alt="cr" title="cr" class="countryList" id="cr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cu.png" alt="cu" title="cu" class="countryList" id="cu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cv.png" alt="cv" title="cv" class="countryList" id="cv" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cy.png" alt="cy" title="cy" class="countryList" id="cy" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/cz.png" alt="cz" title="cz" class="countryList" id="cz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/de.png" alt="de" title="de" class="countryList" id="de" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/dj.png" alt="dj" title="dj" class="countryList" id="dj" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/dk.png" alt="dk" title="dk" class="countryList" id="dk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/dm.png" alt="dm" title="dm" class="countryList" id="dm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/do.png" alt="do" title="do" class="countryList" id="do" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/dz.png" alt="dz" title="dz" class="countryList" id="dz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ec.png" alt="ec" title="ec" class="countryList" id="ec" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ee.png" alt="ee" title="ee" class="countryList" id="ee" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/eg.png" alt="eg" title="eg" class="countryList" id="eg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/eh.png" alt="eh" title="eh" class="countryList" id="eh" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/er.png" alt="er" title="er" class="countryList" id="er" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/es.png" alt="es" title="es" class="countryList" id="es" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/et.png" alt="et" title="et" class="countryList" id="et" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/eu.png" alt="eu" title="eu" class="countryList" id="eu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/fi.png" alt="fi" title="fi" class="countryList" id="fi" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/fj.png" alt="fj" title="fj" class="countryList" id="fj" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/fm.png" alt="fm" title="fm" class="countryList" id="fm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/fr.png" alt="fr" title="fr" class="countryList" id="fr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ga.png" alt="ga" title="ga" class="countryList" id="ga" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gd.png" alt="gd" title="gd" class="countryList" id="gd" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ge.png" alt="ge" title="ge" class="countryList" id="ge" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gh.png" alt="gh" title="gh" class="countryList" id="gh" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gi.png" alt="gi" title="gi" class="countryList" id="gi" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gl.png" alt="gl" title="gl" class="countryList" id="gl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gm.png" alt="gm" title="gm" class="countryList" id="gm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gn.png" alt="gn" title="gn" class="countryList" id="gn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gq.png" alt="gq" title="gq" class="countryList" id="gq" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gr.png" alt="gr" title="gr" class="countryList" id="gr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gt.png" alt="gt" title="gt" class="countryList" id="gt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gw.png" alt="gw" title="gw" class="countryList" id="gw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/gy.png" alt="gy" title="gy" class="countryList" id="gy" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/hk.png" alt="hk" title="hk" class="countryList" id="hk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/hn.png" alt="hn" title="hn" class="countryList" id="hn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/hr.png" alt="hr" title="hr" class="countryList" id="hr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ht.png" alt="ht" title="ht" class="countryList" id="ht" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/hu.png" alt="hu" title="hu" class="countryList" id="hu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/id.png" alt="id" title="id" class="countryList" id="id" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ie.png" alt="ie" title="ie" class="countryList" id="ie" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/il.png" alt="il" title="il" class="countryList" id="il" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/in.png" alt="in" title="in" class="countryList" id="in" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/iq.png" alt="iq" title="iq" class="countryList" id="iq" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ir.png" alt="ir" title="ir" class="countryList" id="ir" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/is.png" alt="is" title="is" class="countryList" id="is" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/it.png" alt="it" title="it" class="countryList" id="it" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/jm.png" alt="jm" title="jm" class="countryList" id="jm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/jo.png" alt="jo" title="jo" class="countryList" id="jo" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/jp.png" alt="jp" title="jp" class="countryList" id="jp" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ke.png" alt="ke" title="ke" class="countryList" id="ke" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kg.png" alt="kg" title="kg" class="countryList" id="kg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kh.png" alt="kh" title="kh" class="countryList" id="kh" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ki.png" alt="ki" title="ki" class="countryList" id="ki" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/km.png" alt="km" title="km" class="countryList" id="km" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kn.png" alt="kn" title="kn" class="countryList" id="kn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kp.png" alt="kp" title="kp" class="countryList" id="kp" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kr.png" alt="kr" title="kr" class="countryList" id="kr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kw.png" alt="kw" title="kw" class="countryList" id="kw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/kz.png" alt="kz" title="kz" class="countryList" id="kz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/la.png" alt="la" title="la" class="countryList" id="la" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lb.png" alt="lb" title="lb" class="countryList" id="lb" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lc.png" alt="lc" title="lc" class="countryList" id="lc" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/li.png" alt="li" title="li" class="countryList" id="li" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lk.png" alt="lk" title="lk" class="countryList" id="lk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lr.png" alt="lr" title="lr" class="countryList" id="lr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ls.png" alt="ls" title="ls" class="countryList" id="ls" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lt.png" alt="lt" title="lt" class="countryList" id="lt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lu.png" alt="lu" title="lu" class="countryList" id="lu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/lv.png" alt="lv" title="lv" class="countryList" id="lv" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ly.png" alt="ly" title="ly" class="countryList" id="ly" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ma.png" alt="ma" title="ma" class="countryList" id="ma" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mc.png" alt="mc" title="mc" class="countryList" id="mc" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/md.png" alt="md" title="md" class="countryList" id="md" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/me.png" alt="me" title="me" class="countryList" id="me" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mg.png" alt="mg" title="mg" class="countryList" id="mg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mh.png" alt="mh" title="mh" class="countryList" id="mh" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mk.png" alt="mk" title="mk" class="countryList" id="mk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ml.png" alt="ml" title="ml" class="countryList" id="ml" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mm.png" alt="mm" title="mm" class="countryList" id="mm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mn.png" alt="mn" title="mn" class="countryList" id="mn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mo.png" alt="mo" title="mo" class="countryList" id="mo" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mr.png" alt="mr" title="mr" class="countryList" id="mr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mt.png" alt="mt" title="mt" class="countryList" id="mt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mu.png" alt="mu" title="mu" class="countryList" id="mu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mv.png" alt="mv" title="mv" class="countryList" id="mv" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mw.png" alt="mw" title="mw" class="countryList" id="mw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mx.png" alt="mx" title="mx" class="countryList" id="mx" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/my.png" alt="my" title="my" class="countryList" id="my" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/mz.png" alt="mz" title="mz" class="countryList" id="mz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/na.png" alt="na" title="na" class="countryList" id="na" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ne.png" alt="ne" title="ne" class="countryList" id="ne" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ng.png" alt="ng" title="ng" class="countryList" id="ng" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ni.png" alt="ni" title="ni" class="countryList" id="ni" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/nl.png" alt="nl" title="nl" class="countryList" id="nl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/no.png" alt="no" title="no" class="countryList" id="no" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/np.png" alt="np" title="np" class="countryList" id="np" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/nr.png" alt="nr" title="nr" class="countryList" id="nr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/nz.png" alt="nz" title="nz" class="countryList" id="nz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/om.png" alt="om" title="om" class="countryList" id="om" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pa.png" alt="pa" title="pa" class="countryList" id="pa" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pe.png" alt="pe" title="pe" class="countryList" id="pe" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pg.png" alt="pg" title="pg" class="countryList" id="pg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ph.png" alt="ph" title="ph" class="countryList" id="ph" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pk.png" alt="pk" title="pk" class="countryList" id="pk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pl.png" alt="pl" title="pl" class="countryList" id="pl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pr.png" alt="pr" title="pr" class="countryList" id="pr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ps.png" alt="ps" title="ps" class="countryList" id="ps" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pt.png" alt="pt" title="pt" class="countryList" id="pt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/pw.png" alt="pw" title="pw" class="countryList" id="pw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/py.png" alt="py" title="py" class="countryList" id="py" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/qa.png" alt="qa" title="qa" class="countryList" id="qa" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ro.png" alt="ro" title="ro" class="countryList" id="ro" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/rs.png" alt="rs" title="rs" class="countryList" id="rs" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ru.png" alt="ru" title="ru" class="countryList" id="ru" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/rw.png" alt="rw" title="rw" class="countryList" id="rw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sa.png" alt="sa" title="sa" class="countryList" id="sa" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sb.png" alt="sb" title="sb" class="countryList" id="sb" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sc.png" alt="sc" title="sc" class="countryList" id="sc" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sd.png" alt="sd" title="sd" class="countryList" id="sd" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/se.png" alt="se" title="se" class="countryList" id="se" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sg.png" alt="sg" title="sg" class="countryList" id="sg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/si.png" alt="si" title="si" class="countryList" id="si" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sk.png" alt="sk" title="sk" class="countryList" id="sk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sl.png" alt="sl" title="sl" class="countryList" id="sl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sm.png" alt="sm" title="sm" class="countryList" id="sm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sn.png" alt="sn" title="sn" class="countryList" id="sn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/so.png" alt="so" title="so" class="countryList" id="so" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sr.png" alt="sr" title="sr" class="countryList" id="sr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/st.png" alt="st" title="st" class="countryList" id="st" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sv.png" alt="sv" title="sv" class="countryList" id="sv" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sy.png" alt="sy" title="sy" class="countryList" id="sy" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/sz.png" alt="sz" title="sz" class="countryList" id="sz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/td.png" alt="td" title="td" class="countryList" id="td" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tg.png" alt="tg" title="tg" class="countryList" id="tg" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/th.png" alt="th" title="th" class="countryList" id="th" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tj.png" alt="tj" title="tj" class="countryList" id="tj" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tl.png" alt="tl" title="tl" class="countryList" id="tl" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tm.png" alt="tm" title="tm" class="countryList" id="tm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tn.png" alt="tn" title="tn" class="countryList" id="tn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/to.png" alt="to" title="to" class="countryList" id="to" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tr.png" alt="tr" title="tr" class="countryList" id="tr" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tt.png" alt="tt" title="tt" class="countryList" id="tt" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tv.png" alt="tv" title="tv" class="countryList" id="tv" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tw.png" alt="tw" title="tw" class="countryList" id="tw" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/tz.png" alt="tz" title="tz" class="countryList" id="tz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ua.png" alt="ua" title="ua" class="countryList" id="ua" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ug.png" alt="ug" title="ug" class="countryList" id="ug" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/uk.png" alt="uk" title="uk" class="countryList" id="uk" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/us.png" alt="us" title="us" class="countryList" id="us" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/uy.png" alt="uy" title="uy" class="countryList" id="uy" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/uz.png" alt="uz" title="uz" class="countryList" id="uz" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/va.png" alt="va" title="va" class="countryList" id="va" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/vc.png" alt="vc" title="vc" class="countryList" id="vc" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ve.png" alt="ve" title="ve" class="countryList" id="ve" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/vn.png" alt="vn" title="vn" class="countryList" id="vn" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/vu.png" alt="vu" title="vu" class="countryList" id="vu" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ws.png" alt="ws" title="ws" class="countryList" id="ws" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/ye.png" alt="ye" title="ye" class="countryList" id="ye" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/za.png" alt="za" title="za" class="countryList" id="za" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/zm.png" alt="zm" title="zm" class="countryList" id="zm" />
						<img src="http://templateshares-ue.net/tsue/data/countryFlags/zw.png" alt="zw" title="zw" class="countryList" id="zw" />
					</div> 
					<span>(click on the flag to change the country)</span>
				</div>
			</div><?php
			
			echo '<div class="row">
				<div class="cell first">Custom Title</div>
				<div class="cell second">
					<input type="text" name="title" size="50" value="'.strip_tags($CURUSER["title"]).'" />
				</div>
			</div>';
			
			$moods = "<option value='0'>--- ".T_("MOOD_SELECT")." ----</option>";
			$ms_r = SQL_Query_exec("SELECT id,name from moods ORDER BY name");
			while ($ms_a = mysqli_fetch_assoc($ms_r))
				$moods .= "<option value='$ms_a[id]'>$ms_a[name]</option>";
			echo '<div class="row">
				<div class="cell first">FaceMood</div>
				<div class="cell second">
					<select name="mood">'.$moods.'</select>
				</div>
			</div>';
			
			echo '<div class="row">
				<div class="cell first"></div>
				<div class="cell second">
					<input type="submit" value="Save" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>
	</form>';
}

if ($action=="contact_details"){
	echo '<div class="error" id="show_error">
		<b>An error has occured!</b><br />
		For security reasons, you can\'t change your e-mail address.<br />
		If you still need to change your e-mail address please <a href="/contact.php">contact us</a>.
	</div>
	<br />
	<div class="tableHeader">
		<div class="row">
			<div class="cell first">
				'.T_("USER").': '.$CURUSER[username].' ('.T_("EMAIL").')
			</div>
		</div>
	</div>

	<div class="table">
		<div class="row">
			<div class="cell first">'.T_("EMAIL").'</div>
			<div class="cell second">
				<input type="text" name="email" size="50" value="'.htmlspecialchars($CURUSER["email"]).'" /><br />
				<i>'.T_("REPLY_TO_CONFIRM_EMAIL").'</i>
			</div>
		</div>';

		$teams = "<option value='0'>--- ".T_("NONE_SELECTED")." ----</option>\n";
		$sashok = SQL_Query_exec("SELECT id,name FROM teams ORDER BY name");
		if (!$sashok){
			while ($sasha = mysqli_fetch_assoc($sashok))
				$teams .= "<option value='$sasha[id]'" . ($CURUSER["team"] == $sasha['id'] ? " selected='selected'" : "") . ">$sasha[name]</option>\n"; 
			echo '<div class="row">
				<div class="cell first">Team</div>
				<div class="cell second">
					<select name="teams">'.$teams.'</select>
				</div>
			</div>';
		}
	echo '</div>';
}

if ($action=="preferences"){

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
		if ($ss_id == $CURUSER["stylesheet"])
			$ss = " selected='selected'";
		else 
			$ss = "";
		$stylesheets .= "<option value='$ss_id'$ss>$ss_name</option>";
	}
	
	$lang_r = SQL_Query_exec("SELECT * from languages");
    $lang_sa = array();

    while ($lang_a = mysqli_fetch_assoc($lang_r)){
        $lang_id = $lang_a["id"];
        $lang_name = $lang_a["name"];
        $lang_sa[$lang_name] = $lang_id;
    }

    ksort($lang_sa);
    reset($lang_sa);

    while (list($lang_name, $lang_id) = each($lang_sa)){
        if ($lang_id == $CURUSER["language"]) $lang = " selected='selected'"; else $lang = "";
        $languages .= "<option value='$lang_id'$lang>$lang_name</option>\n";
    }
	
	ksort($tzs);
	reset($tzs);
	while (list($key, $val) = each($tzs)) {
	if ($CURUSER["tzoffset"] == $key)
		$tz .= "<option value=\"$key\" selected='selected'>$val[0]</option>\n";
	else
		$tz .= "<option value=\"$key\">$val[0]</option>\n";
	}
	
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="preferences" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">
					Member CP - Preferences
				</div>
			</div>
		</div>

		<div class="table">
	
			<div class="row">
				<div class="cell first">Style</div>
				<div class="cell second">
					<select name="themeid" id="cat_content">'.$stylesheets.'</select>';
					//<option value="1" selected="selected">Default</option>
				echo '</div>
			</div>

			<div class="row">
				<div class="cell first">Torrent Listing</div>
				<div class="cell second">
					<select name="torrentStyle" id="cat_content">
						<option value="1" selected="selected">Modern</option>
						<option value="2">Classic</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="cell first">Preferred Client</div>
				<div class="cell second">
					<input type="text" size="20" maxlength="20" name="client" value="'.htmlspecialchars($CURUSER["client"]).'" />
				</div>
			</div>

			<div class="row">
				<div class="cell first">Language</div>
				<div class="cell second">
					<select name="languageid" id="cat_content">'.$languages.'</select>';
						//<option value="1" selected="selected">English</option>
				echo '</div>
			</div>';

			echo '<div class="row">
				<div class="cell first">Time zone</div>
				<div class="cell second">
					<select name="timezone" id="cat_content">'.$tz.'</select>
				</div>
			</div>';
			
	// START CAT LIST SQL
	$r = SQL_Query_exec("SELECT id,name,parent_cat FROM categories ORDER BY parent_cat ASC, sort_index ASC");
	if (mysqli_num_rows($r) > 0)
	{
		$categories .= "<table><tr>\n";
		$i = 0;
		while ($a = mysqli_fetch_assoc($r))
		{
		  $categories .=  ($i && $i % 2 == 0) ? "</tr><tr>" : "";
		  $categories .= "<td class='bottom' style='padding-right: 5px'><input name='cat$a[id]' type=\"checkbox\" " . (strpos($CURUSER['notifs'], "[cat$a[id]]") !== false ? " checked='checked'" : "") . " value='yes' />&nbsp;" .htmlspecialchars($a["parent_cat"]).": " . htmlspecialchars($a["name"]) . "</td>\n";
		  ++$i;
		}
		$categories .= "</tr></table>\n";
	}

	// END CAT LIST SQL
	echo '<div class="row">
		<div class="cell first">'.T_("CATEGORIES").'</div>
		<div class="cell second">
			<table width="100%" align="left" cellpadding="5" cellspacing="0" id="torrentCategoriesCheckboxes">
				<tr></tr>
				<tr class="hidden">
					<td colspan="2" class="hidden"></td>
				</tr>
				<tr>';
				// get all parent cats
				$wherecatina = array();
				$i = 0;
				$catsperrow = 2;
				$catsquery = SQL_Query_exec("SELECT distinct parent_cat FROM categories ORDER BY parent_cat");
				while($catsrow = mysqli_fetch_assoc($catsquery)){
					echo '<td valign="top">';
						if ($i == $catsperrow){
								echo '</td>
							</tr>
							<tr class="hidden">
								<td colspan="2" class="hidden"></td>
							</tr>
							<tr>
								<td valign="top">';
							$i = 0;
						}
						echo '<img src="/images/categories/'.$catsrow["image"].'" style="max-width: 24px;" class="middle" />
						<input type="checkbox" rel="main" id="category_'.$catsrow["id"].'" name="cid[]" '.(in_array($cat["id"], $wherecatina) ? "checked='checked' " : "").'value="'.$catsrow["id"].'" /> 
						<span class="strong"><a href="../browse/?cat='.$catrow["id"].'">'.htmlspecialchars($catsrow[parent_cat]).'</a></span>
						<div style="padding-left: 26px;">
							<table cellpadding="3" cellspacing="0" border="0" align="left">';
								$parentcat = $catsrow['parent_cat'];
								$cats = SQL_Query_exec("SELECT * FROM categories WHERE parent_cat=".sqlesc($parentcat)." ORDER BY name");
								while ($cat = mysqli_fetch_assoc($cats)) {
									echo '<tr>
										<td valign="top">
											<img src="/images/categories/'.$cat["image"].'" style="max-width: 24px;" class="middle" alt=""/> 
											<input type="checkbox" rel="category_'.$catsrow["id"].'" id="category_'.$cat["id"].'" name="cid[]" '.(in_array($cat["id"], $wherecatina) ? "checked='checked' " : "").'value="'.$cat["id"].'" /> 
											<span class="small"><a href="../browse/?cat='.$cat["id"].'">'.htmlspecialchars($cat["name"]).'</a></span>
										</td>
									</tr>';
								}
							echo '</table>
						</div>
					</td>';
					$i++;
				}
			echo '</table>
		</div>
	</div>

	<div class="row">
        <div class="cell first"><label for="accountParked">Park Account</label></div>
        <div class="cell second">
            <input type="checkbox" name="accountParked" id="accountParked" accesskey="p" value="1" />
            <span class="small"><label for="accountParked">Check this box to park your account.<br />You can park your account to prevent it from being deleted because of inactivity if you go away on for example a vacation. When the account has been parked limits are put on the account, for example you cannot browse some of the pages.</label></span>
        </div>
    </div>
	
	<div class="row">
        <div class="cell first"><label for="'.T_("RESET_PASSKEY").'">'.T_("RESET_PASSKEY").'</label></div>
        <div class="cell second">
			<input type="checkbox" name="resetpasskey" value="1" />&nbsp;<i>'.T_("RESET_PASSKEY_MSG").'.</i>
		</div>
    </div>
	
	<div class="row">
		<div class="cell first"></div>
		<div class="cell second">
			<input type="submit" value="Save" class="submit" /> 
			<input type="reset" value="Clear" class="submit" />
		</div>
	</div>

</div>

</form>';
}

if ($action=="privacy"){
	
	function priv($name, $descr) {
		global $CURUSER;
		if ($CURUSER["privacy"] == $name)
			return "<input type=\"radio\" name=\"privacy\" value=\"$name\" checked=\"checked\" /> $descr";
		return "<input type=\"radio\" name=\"privacy\" value=\"$name\" /> $descr";
	}

	$acceptpms = $CURUSER["acceptpms"] == "yes";
	  
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="privacy" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">
					Member CP - Privacy
				</div>
			</div>
		</div>

		<div class="table">

			<div class="row">
				<div class="cell first"><label for="'.T_("ACCOUNT_PRIVACY_LVL").'">'.T_("ACCOUNT_PRIVACY_LVL").'</label></div>
				<div class="cell second">
					'. priv("normal", T_("NORMAL")).' '. priv("low", T_("LOW")).' '. priv("strong", T_("STRONG")).'<br />
					<span class="small"><label for="'.T_("ACCOUNT_PRIVACY_LVL").'">'.T_("ACCOUNT_PRIVACY_LVL_MSG").'</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="visible">Show your online status</label></div>
				<div class="cell second">
					<input type="checkbox" name="visible" id="visible" accesskey="v" value="1" checked="checked" />
					<span class="small"><label for="visible">This will allow other people to see what page you are currently viewing.</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="receive_admin_email">Receive site mailings</label></div>
				<div class="cell second">
					<input type="checkbox" name="receive_admin_email" id="receive_admin_email" accesskey="r" value="1" checked="checked" />
					<span class="small"><label for="receive_admin_email">You will receive a copy of emails sent by the administrator to all members of the site.</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="'.T_("ACCEPT_PMS").'">'.T_("ACCEPT_PMS").'</label></div>
				<div class="cell second">
					<input type="radio" name="acceptpms"'.($acceptpms ? " checked='checked'" : "").' value="yes" />'.T_("FROM_ALL").' 
					<input type="radio" name="acceptpms"'.($acceptpms ? "" : " checked='checked'").' value="no" />'.T_("FROM_STAFF_ONLY").'<br />
					<span class="small"><label for="'.T_("ACCEPT_PMS").'">'.T_("ACCEPTPM_WHICH_USERS").'</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="receive_pm_email">Receive PM mailings</label></div>
				<div class="cell second">
					<input type="checkbox" name="receive_pm_email" id="receive_pm_email" accesskey="r" value="1" />
					<span class="small"><label for="receive_pm_email">Receive Email Notification of New Private Message</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="show_your_age">Show your age</label></div>
				<div class="cell second">
					<input type="checkbox" name="show_your_age" id="show_your_age" accesskey="r" value="1" checked="checked" />
					<span class="small"><label for="show_your_age">This will allow other people to see how old are you.</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="cat_content">View your details on your profile page</label></div>
				<div class="cell second">
					<select name="allow_view_profile" id="cat_content">
						<option value="everyone">All Visitors</option>
						<option value="members" selected="selected">Members Only</option>
						<option value="followed">People You Follow Only</option>
						<option value="none">None</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="cell first"></div>
				<div class="cell second">
					<input type="submit" value="Save" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>

	</form>';
}

if ($action=="password"){
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="password" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">Member CP - Password</div>
			</div>
		</div>

		<div class="table">
	
			<div class="row">
				<div class="cell first"><label for="membercp_your_existing_password">Your Existing Password</label></div>
				<div class="cell second">
					<input type="password" name="membercp_your_existing_password" id="membercp_your_existing_password" class="s" accesskey="e" value="" title="For security reasons, you must verify your existing password before you may set a new password." />
				</div>
			</div>
	
			<div class="row">
				<div class="cell first"><label for="membercp_new_password">New Password</label></div>
				<div class="cell second">
					<div class="passwordStrength">
						<div class="score"><span><b></b></span></div>
						<input type="password" name="membercp_new_password" id="membercp_new_password" class="s" accesskey="n" value="" title="Try to make it hard to guess. Must be at least 5 characters." />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="membercp_confirm_new_password">Confirm New Password</label></div>
				<div class="cell second">
					<input type="password" name="membercp_confirm_new_password" id="membercp_confirm_new_password" class="s" accesskey="c" value="" title="Enter your Password again." />
				</div>
			</div>

			<div class="row">
				<div class="cell first"></div>
				<div class="cell second">
					<input type="submit" value="Save" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>
	</form>';
}

if ($action=="signature"){
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="signature" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">Member CP - '.T_("SIGNATURE").'</div>
			</div>
		</div>

		<div class="table">
	
			<div class="row">
				<div class="cell footer">
					<textarea name="signature" id="tinymce_autoload" class="tinymce">'.htmlspecialchars($CURUSER["signature"]).'</textarea>
				</div>
			</div>

			<div class="row">
				<div class="cell footer">'.sprintf(T_("MAX_CHARS"), 150).T_("HTML_NOT_ALLOWED").'</div>
			</div>

			<div class="row">
				<div class="cell footer">
					<input type="submit" value="Save" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>

	</form>';
}

if ($action=="avatar"){
	echo '<form id="membercp_avatar_form" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="avatar" id="action" />
		<input type="hidden" name="securitytoken" value="618-1476003530-80c06e73493d964ffdfd4a9ed8147b3bd209c507" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">
					Member CP - Avatar
				</div>
			</div>
		</div>

		<div class="table">

			<div class="row">
				<div class="cell first">Avatar url</div>
				<div class="cell second">
					<input type="text" name="avatar" size="50" value="'.htmlspecialchars($CURUSER["avatar"]).'" /> 
				</div>
			</div>

			<div class="row">
				<div class="cell first">Select and Upload a new Custom Avatar</div>
				<div class="cell second">
					<input type="file" name="avatar" /> <input type="submit" value="Upload" class="submit" id="avatar_submit" /> 
				</div>
			</div>

		</div>

	</form>';
}

if ($action=="invite"){
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="invite" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">Send Invite</div>
			</div>
		</div>

		<div class="table">

			<div class="row">
				<div class="cell first"><label for="invite_friend_name">Friend Name</label></div>
				<div class="cell second">
					<input type="text" name="invite_friend_name" id="invite_friend_name" class="s" accesskey="n" value="" />
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="invite_friend_email">Friend Email</label></div>
				<div class="cell second">
					<input type="text" name="invite_friend_email" id="invite_friend_email" class="s" accesskey="e" value="" />
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="invite_friend_message">Message</label></div>
				<div class="cell second">
					<textarea name="invite_friend_message" id="cat_content_small" class="s" accesskey="m" title="Enter a message to include in the email (optional)"></textarea>
				</div>
			</div>

			<div class="row">
				<div class="cell first"></div>
				<div class="cell second">
					<input type="submit" value="Send Invite" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>

	</form>
	<br />
	<div class="information">Invite your friends to earn free Upload Amont and/of Bonus Points<br /></div><br />
	<div class="tableHeader" id="inviteListHeader">
		<div class="row">
			<div class="cell first">
				Invited Friends
			</div>
		</div>
	</div>';	
}

if ($action=="upgrade"){
	echo '<div id="upgrade">
		<strong class="newIndicator"><span></span>6 Months VIP Membership</strong>

		<div><p>6 Months of full VIP Membership!</p></div>

		<div class="small promotions">
			Your account will be upgraded to <span class="membernameVIP">VIP Members</span> group.<br />
			You will earn <b>+750</b> points.<br />
			You will earn <b>+10</b> invites.<br />
			You will earn <b>+10 GB</b> upload amount.
		</div>
	
		<div class="submit small price">
			<img src="/themes/default/market/eur.png" title="Price & Length" class="middle" />
			40.00 EUR for 6 Months
		</div>

		<div class="submit small purchase" rel="purchase" id="5">
			<img src="/themes/default/market/paypal.png" class="middle" alt="" title="" /> Purchase Now
		</div>

	</div>
	<div id="upgrade">
		<strong class="newIndicator"><span></span>1 Year VIP Membership</strong>

		<div><p>This is most popular Account Upgrade option available right now! Purchase this to have 1 year VIP membership!</p></div>

		<div class="small promotions">
			Your account will be upgraded to <span class="membernameVIP">VIP Members</span> group.<br />
			You will earn <b>+500</b> points.<br />
			You will earn <b>+300</b> invites.<br />
			You will earn <b>+50 GB</b> upload amount.
		</div>

		<div class="submit small price">
			<img src="/themes/default/market/eur.png" title="Price & Length" class="middle" />
			64.99 EUR for 1 Year
		</div>

		<div class="submit small purchase" rel="purchase" id="4">
			<img src="/themes/default/market/paypal.png" class="middle" alt="" title="" /> Purchase Now
		</div>

	</div>';
}

if ($action=="following"){
	show_error_msg(T_("ERROR"), "Nothing found.", 1);
}

if ($action=="gallery"){
?><div class="whiteBox center"> <span class="admincp_link">
	[<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;my_images=1">My Images</a>]
</span></div>

<div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=533"><img src="http://templateshares-ue.net/tsue/data/gallery/s/chat.png" title="Filename: chat.png<br />
File Size: 58.6 KB<br />
View Count: 82<br />
Upload Date: 01-07-2015 12:28<br />
Uploaded By: Exception" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=508"><img src="http://templateshares-ue.net/tsue/data/gallery/s/curl.jpg" title="Filename: curl.jpg<br />
File Size: 51.37 KB<br />
View Count: 107<br />
Upload Date: 16-12-2014 21:15<br />
Uploaded By: ads" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=503"><img src="http://templateshares-ue.net/tsue/data/gallery/s/classic.png" title="Filename: classic.png<br />
File Size: 212.32 KB<br />
View Count: 92<br />
Upload Date: 01-10-2014 17:02<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=477"><img src="http://templateshares-ue.net/tsue/data/gallery/s/person-of-interest-season-3-faulse-statistics.jpg" title="Filename: person-of-interest-season-3-faulse-statistics.jpg<br />
File Size: 10 KB<br />
View Count: 90<br />
Upload Date: 25-08-2014 21:54<br />
Uploaded By: ads" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=476"><img src="http://templateshares-ue.net/tsue/data/gallery/s/3036037fce09b36cacbc4d5f48d44a13.png" title="Filename: 3036037fce09b36cacbc4d5f48d44a13.png<br />
File Size: 12.67 KB<br />
View Count: 94<br />
Upload Date: 14-08-2014 07:54<br />
Uploaded By: jamesmartonlf" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=474"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-08-02-09h35-41.png" title="Filename: 2014-08-02-09h35-41.png<br />
File Size: 79.96 KB<br />
View Count: 99<br />
Upload Date: 02-08-2014 09:38<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=473"><img src="http://templateshares-ue.net/tsue/data/gallery/s/Sem-T-tulo1.png" title="Filename: Sem-T-tulo1.png<br />
File Size: 27.78 KB<br />
View Count: 98<br />
Upload Date: 23-07-2014 10:15<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=471"><img src="http://templateshares-ue.net/tsue/data/gallery/s/Screenshot-2.jpg" title="Filename: Screenshot-2.jpg<br />
File Size: 146.51 KB<br />
View Count: 103<br />
Upload Date: 19-07-2014 11:11<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=470"><img src="http://templateshares-ue.net/tsue/data/gallery/s/Screenshot-1.jpg" title="Filename: Screenshot-1.jpg<br />
File Size: 123.19 KB<br />
View Count: 100<br />
Upload Date: 19-07-2014 11:11<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=468"><img src="http://templateshares-ue.net/tsue/data/gallery/s/nfoview.jpg" title="Filename: nfoview.jpg<br />
File Size: 97.26 KB<br />
View Count: 101<br />
Upload Date: 19-07-2014 11:10<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=466"><img src="http://templateshares-ue.net/tsue/data/gallery/s/similar.jpg" title="Filename: similar.jpg<br />
File Size: 304.7 KB<br />
View Count: 112<br />
Upload Date: 17-07-2014 17:34<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=465"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-17-13h57-13.png" title="Filename: 2014-07-17-13h57-13.png<br />
File Size: 18.18 KB<br />
View Count: 101<br />
Upload Date: 17-07-2014 14:12<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=464"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-17-13h57-32.png" title="Filename: 2014-07-17-13h57-32.png<br />
File Size: 25.07 KB<br />
View Count: 97<br />
Upload Date: 17-07-2014 14:08<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=463"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-17-13h57-49.png" title="Filename: 2014-07-17-13h57-49.png<br />
File Size: 14.64 KB<br />
View Count: 102<br />
Upload Date: 17-07-2014 14:01<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=460"><img src="http://templateshares-ue.net/tsue/data/gallery/s/threadpreview.jpg" title="Filename: threadpreview.jpg<br />
File Size: 47.22 KB<br />
View Count: 99<br />
Upload Date: 15-07-2014 16:22<br />
Uploaded By: Cookie" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=459"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-15-10h28-18corrected.png" title="Filename: 2014-07-15-10h28-18corrected.png<br />
File Size: 54.98 KB<br />
View Count: 98<br />
Upload Date: 15-07-2014 10:59<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=458"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-15-10h32-51corrected.png" title="Filename: 2014-07-15-10h32-51corrected.png<br />
File Size: 39.47 KB<br />
View Count: 97<br />
Upload Date: 15-07-2014 10:50<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=457"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-15-10h34-57.png" title="Filename: 2014-07-15-10h34-57.png<br />
File Size: 21.17 KB<br />
View Count: 100<br />
Upload Date: 15-07-2014 10:35<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=456"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-15-10h32-51.png" title="Filename: 2014-07-15-10h32-51.png<br />
File Size: 35.43 KB<br />
View Count: 97<br />
Upload Date: 15-07-2014 10:33<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=455"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-15-10h28-18.png" title="Filename: 2014-07-15-10h28-18.png<br />
File Size: 29.35 KB<br />
View Count: 96<br />
Upload Date: 15-07-2014 10:31<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=454"><img src="http://templateshares-ue.net/tsue/data/gallery/s/Udklip.PNG" title="Filename: Udklip.PNG<br />
File Size: 118.03 KB<br />
View Count: 112<br />
Upload Date: 14-07-2014 17:02<br />
Uploaded By: MasterDraco" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=452"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-14-13h45-25.png" title="Filename: 2014-07-14-13h45-25.png<br />
File Size: 41.23 KB<br />
View Count: 110<br />
Upload Date: 14-07-2014 13:54<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=451"><img src="http://templateshares-ue.net/tsue/data/gallery/s/2014-07-14-13h44-39.png" title="Filename: 2014-07-14-13h44-39.png<br />
File Size: 28.47 KB<br />
View Count: 93<br />
Upload Date: 14-07-2014 13:52<br />
Uploaded By: stef76" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=450"><img src="http://templateshares-ue.net/tsue/data/gallery/s/sppedtesturl.PNG" title="Filename: sppedtesturl.PNG<br />
File Size: 22.64 KB<br />
View Count: 119<br />
Upload Date: 14-07-2014 12:42<br />
Uploaded By: MasterDraco" /></a>
</div><div class="igImageBox">
	<a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;action=viewFile&amp;attachment_id=449"><img src="http://templateshares-ue.net/tsue/data/gallery/s/errorsdb.png" title="Filename: errorsdb.png<br />
File Size: 129.39 KB<br />
View Count: 97<br />
Upload Date: 10-07-2014 10:49<br />
Uploaded By: Cookie" /></a>
</div>

<div class="clear"></div>

<div class="pagination"><ul><li class="active"><a href="#">1</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=2">2</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=3">3</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=4">4</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=5">5</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=6">6</a></li><li><a href="http://templateshares-ue.net/tsue/?p=gallery&amp;pid=400&amp;page=2">&raquo;</a></li></ul></div>
<?php
}

if ($action=="performance"){
	echo '<form id="membercp_form" action="">
		<input type="hidden" name="action" value="performance" id="action" />

		<div class="tableHeader">
			<div class="row">
				<div class="cell first">Member CP - Performance</div>
			</div>
		</div>

		<div class="table">';

			if ($site_config["SHOUTBOX"]){
				echo '<div class="row">
					<div class="cell first"><label for="shoutbox_enabled">'.T_("HIDE_SHOUT").'</label></div>
					<div class="cell second">
						<input type="checkbox" name="shoutbox_enabled" id="shoutbox_enabled" accesskey="v" value="1" '.($CURUSER["hideshoutbox"] == "yes" ? "checked='checked'" : "").' />
						<span class="small"><label for="shoutbox_enabled">'.T_("HIDE_SHOUT_TEXT").'</label></span>
					</div>
				</div>';
			}

			echo '<div class="row">
				<div class="cell first"><label for="irtm_enabled">IRTM Enabled</label></div>
				<div class="cell second">
					<input type="checkbox" name="irtm_enabled" id="irtm_enabled" accesskey="v" value="1" checked="checked" />
					<span class="small"><label for="irtm_enabled">Uncheck this box to disable The instants real time private messaging system.</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"><label for="alerts_enabled">Alerts Enabled</label></div>
				<div class="cell second">
					<input type="checkbox" name="alerts_enabled" id="alerts_enabled" accesskey="v" value="1" checked="checked" />
					<span class="small"><label for="alerts_enabled">Uncheck this box to disable The instants real time alert system globally.</label></span>
				</div>
			</div>

			<div class="row">
				<div class="cell first"></div>
				<div class="cell second">
					<input type="submit" value="Save" class="submit" /> 
					<input type="reset" value="Clear" class="submit" />
				</div>
			</div>

		</div>

	</form>';
}

if ($action=="subscribed_threads"){
	show_error_msg(T_("ERROR"), "Nothing found.", 1);
}

if ($action=="open_port_check_tool"){
	echo '<div class="information" id="show_information">
		The open port checker is a tool you can use to check your external IP address and detect open ports on your connection. This tool is useful for finding out if your port forwarding is setup correctly or if your server applications are being blocked by a firewall. This tool may also be used as a port scanner to scan your network for ports that are commonly forwarded. It is important to note that some ports, such as port 25, are often blocked at the ISP level in an attempt to prevent malicious activity.
		<p>For more a comprehensive list of TCP and UDP ports, check out <a href="http://en.wikipedia.org/wiki/List_of_TCP_and_UDP_port_numbers" target="_blank">this Wikipedia article</a>.</p>
		Need help? Try this: <a href="http://portforward.com/help/start_here.htm" target="_blank">Port Forwarding</a>.
	</div>

	<div class="comment-box">

		<h3>Your IP: '.getip().'</h3>

		<form method="post" action="" name="scanPort" id="scanPort">
			Port Number:
			<input type="text" name="port" id="port" class="s" accesskey="p" value="" /> 
			<input type="submit" value="Check" class="submit" />
		</form>

	</div>';
}

if ($action=="alerts"){
	show_error_msg(T_("ERROR"), "You have no new alerts.", 1);
}

if ($action=="messages") {
    if (isset($_GET["message_id"])) {
        if (is_valid_id($_GET["message_id"])){
            $messageid = $_GET["message_id"];
            $res = SQL_Query_exec("SELECT * FROM messages WHERE id=$messageid");
            $arr = mysqli_fetch_assoc($res);
            echo '<div id="messageTools">
            	<div class="toolsMenu">
            	    <dl class="dropdown">
                    	<dt><a href="javascript:void(0);"></a></dt>
                    	<dd>
                    		<ul id="ulglobal">
			                    <li><a href="#" id="pm_markAsUnread" rel="'.$arr["id"].'">Mark as Unread</a></li>
			        			<li><a href="#" id="pm_DeleteMessage" rel="'.$arr["id"].'">Delete Message</a></li>
			                    <li><a href="#" id="pm_forwardMessage" rel="'.$arr["id"].'">Forward Message</a></li>
                    		</ul>
                    	</dd>
                    </dl>
                </div>
            </div>';

            if ($arr["sender"] == $CURUSER['id'])
                $sender = "Yourself";
            elseif (is_valid_id($arr["sender"])) {
                $res2 = SQL_Query_exec("SELECT username, class, avatar FROM users WHERE `id` = $arr[sender]");
                $arr2 = mysqli_fetch_assoc($res2);
                $sender = ($arr2["username"] ? $arr2["username"] : "[Deleted]");
                $avatar = htmlspecialchars($arr2["avatar"]);
                if (!$avatar)
                    $avatar = "/images/default_avatar.png";
                if ($sender != "[Deleted]") {
                    switch ($arr2["class"]) {
                        case 1:
                            $color = "#00FFFF";// user
                            break;
                        case 2:
                            $color = "#FF7519";// power user
                            break;
                        case 3:
                            $color = "#990099";// VIP
                            break;
                        case 4:
                            $color = "#0000FF";// uploader
                            break;
                        case 5:
                            $color = "#009900";//moderator
                            break;
                        case 6:
                            $color = "#00FF00";//super moderator
                            break;
                        case 7:
                            $color = "#FF0000";// you and most trusted
                            break;
                    }
                } else {
                    $color = "#000";
                }
            } else {
                $sender = T_("SYSTEM");
                $avatar = "/images/default_avatar.png";
                $color = "#000";
            }

            echo '<div id="message_id_'.$arr["id"].'">
                <div class="comments" id="reply_15291">
                	<div class="cAvatar">';
                        if ($sender == T_("SYSTEM") || $sender == "[Deleted]") {
                            echo '<img src="' . $avatar . '" alt="" title="" class="avatar" width="48px" />';
                        } else {
                            echo '<img src="' . $avatar . '" alt="" title="" class="clickable avatar" id="member_info" memberid="' . $arr["sender"] . '" width="48px" />';
                        }
                    echo '</div>
	                <div class="commentHolder">
		                <div class="cMessage" id="cMessage_15291">'.format_comment($arr["msg"]).'</div>
                        <div class="commentDate">
			                <div class="cLinks">
				                &nbsp;&nbsp;&nbsp;<a href="#" id="replyMessage" reply_id="15291" message_id="'.$arr["id"].'">Reply</a>
                			</div>';
                            if ($sender == T_("SYSTEM") || $sender == "[Deleted]") {
                                echo '<span style="color: ' . $color . '; font-weight: bold;">' . $sender . '</span>  '.$arr["added"];
                            } else {
                                echo '<span id="member_info" memberid="' . $arr["sender"] . '" class="clickable"><span style="color: ' . $color . '; font-weight: bold;">' . $sender . '</span></span>  '.$arr["added"];
                            }
                		echo '</div>
                		<div class="clear"></div>
                	</div>
                	<div class="clear"></div>';

                    echo '<form method="POST" action="mailbox.php">';
                    require_once("backend/bbcode.php");

                    print textbbcode("compose","msg","$msg");
                    echo "<table width='600px' border='0' align='center' cellpadding='4' cellspacing='0'>";
                    $output = "<input type=\"submit\" name=\"send\" value=\"Send\" />&nbsp;<label><input type=\"checkbox\" name=\"save\" checked='checked' />Save Copy In Outbox</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"draft\" value=\"Save Draft\" />&nbsp;";
                    tr2($output);
            echo "</table>";
            end_form();
                echo '</div>
            </div>
            <div id="messageData"><input type="hidden" name="last_reply_id" id="last_reply_id" value="15291" /></div>
            <div id="fetchNewMessages"></div>
            <div id="messages_post_reply"></div>';
        }
        else
            echo 'WUT ! Invalid iD !...';
    } else {
        echo '<div class="message" id="show_all_messages">

    		<span class="floatright">
	    		<input type="button" name="messages_delete_messages" value="Delete Selected Messages" id="messages_delete_messages" class="submit" /> 
		    	<input type="button" name="messages_select_all" value="Select All" id="messages_select_all" class="submit" /> 
		    </span>
	
		    <input type="button" name="messages_new_message" value="New Message" id="messages_new_message" class="submit" /> 
		    <input type="button" name="messages_view_all" value="See All Messages" id="messages_view_all" class="submit" />
		    <div id="show_member_messages">';

                $where = "`receiver` = $CURUSER[id] AND `location` IN ('in','both')";
                $order = order("added,sender,sendto,subject", "added", true);
                $res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE $where");
                $count = mysqli_result($res, 0);
                //list($pagertop, $pagerbottom, $limit) = pager2(20, $count);

                $res = SQL_Query_exec("SELECT * FROM messages WHERE $where $order");
                while ($arr = mysqli_fetch_assoc($res)) {
                    $userid = 0;
                    $format = '';
                    $reading = false;

                    if ($arr["sender"] == $CURUSER['id'])
                        $sender = "Yourself";
                    elseif (is_valid_id($arr["sender"])) {
                        $res2 = SQL_Query_exec("SELECT username, class, avatar FROM users WHERE `id` = $arr[sender]");
                        $arr2 = mysqli_fetch_assoc($res2);
                        $sender = ($arr2["username"] ? $arr2["username"] : "[Deleted]");
                        $avatar = htmlspecialchars($arr2["avatar"]);
                        if (!$avatar)
                            $avatar = "/images/default_avatar.png";
                        if ($sender != "[Deleted]") {
                            switch ($arr2["class"]) {
                                case 1:
                                    $color = "#00FFFF";// user
                                    break;
                                case 2:
                                    $color = "#FF7519";// power user
                                    break;
                                case 3:
                                    $color = "#990099";// VIP
                                    break;
                                case 4:
                                    $color = "#0000FF";// uploader
                                    break;
                                case 5:
                                    $color = "#009900";//moderator
                                    break;
                                case 6:
                                    $color = "#00FF00";//super moderator
                                    break;
                                case 7:
                                    $color = "#FF0000";// you and most trusted
                                    break;
                            }
                        } else {
                            $color = "#000";
                        }
                    } else {
                        $sender = T_("SYSTEM");
                        $avatar = "/images/default_avatar.png";
                        $color = "#000";
                    }

                    if ($arr["receiver"] == $CURUSER['id']) $sentto = "Yourself";
                    elseif (is_valid_id($arr["receiver"])) {
                        $res2 = SQL_Query_exec("SELECT username FROM users WHERE `id` = $arr[receiver]");
                        $arr2 = mysqli_fetch_assoc($res2);
                        $sentto = "<a href=\"account-details.php?id=$arr[receiver]\">" . ($arr2["username"] ? $arr2["username"] : "[Deleted]") . "</a>";
                    } else
                        $sentto = T_("SYSTEM");

                    $subject = ($arr['subject'] ? htmlspecialchars($arr['subject']) : "no subject");

                    if (@$_GET['read'] == $arr['id']) {
                        $reading = true;
                        if (isset($_GET['inbox']) && $arr["unread"] == "yes")
                            SQL_Query_exec("UPDATE messages SET `unread` = 'no' WHERE `id` = $arr[id] AND `receiver` = $CURUSER[id]");
                    }
                    if ($arr["unread"] == "yes") {
                        $format = "font-weight:bold;";
                        $unread = true;
                    }

                    echo '<div id="show_message_' . $arr["id"] . '" class="comment-box">';
                        if ($sender == T_("SYSTEM") || $sender == "[Deleted]") {
                            echo '<img src="' . $avatar . '" alt="" title="" class="avatar" width="48px" />';
                        } else {
                            echo '<img src="' . $avatar . '" alt="" title="" class="clickable avatar" id="member_info" memberid="' . $arr["sender"] . '" width="48px" />';
                        }

                        echo '<div class="floatright textAlignCenter">';
                            if ($arr["unread"] == "yes") {
                                echo '<span id="unread" class="prefixButton red">Unread</span>';
                            }
                            echo '<label><input type="checkbox" name="deleteMessages[]" value="' . $arr["id"] . '" id="deleteMessages" /></label>
                        </div>

                        <div>';
                            if ($sender == T_("SYSTEM") || $sender == "[Deleted]") {
                                echo '<span style="color: ' . $color . '; font-weight: bold;">' . $sender . '</span>';
                            } else {
                                echo '<span id="member_info" memberid="' . $arr["sender"] . '" class="clickable"><span style="color: ' . $color . '; font-weight: bold;">' . $sender . '</span></span>';
                            }
                        echo '</div>
					    <div><a href="/membercp.php?action=messages&message_id=' . $arr["id"] . '">' . $subject . '</a></div>
					    <div class="smalldate">' . utc_to_tz($arr["added"]) . '</div>
			    	</div>';

                }
            echo '</div>
	    </div>';
    }
}

stdfoot();
?>