{include file='tpl/header.tpl'}
<ul class="toc" id="toc">
	<li><a href="/">Acasă</a></li>
	<li><a href="" rel="hiddenmenu">Toate județele ▼</a>
		<div id="hiddenmenu" class="hiddentoc">
			{foreach $county_list as $othercounty name=othercounties}
				<a href="?id={$othercounty.jud}">{$othercounty.denloc}</a>
				{if $smarty.foreach.othercounties.index % 2 == 1}<div  style="clear:both;"></div>{/if}
			{/foreach}
		</div>
	</li>
	<li><a href="data.php" rel="hiddendata">Date brute ▼</a>
		<div id="hiddendata" class="hiddentoc">
			<a href="data.php?f=csv&type=all&county={$index}&commune=none" style="width:100px">CSV</a>
			<div  style="clear:both;"></div>
			<a href="data.php?f=json&type=all&county={$index}&commune=none" style="width:100px">JSON</a>
		</div>
	</li>
	<li><a href="despre.php">Despre</a></li>
	<li><a href="cauta.php">Căutare</a></li>
</ul>
<hr />
<table cellpadding="0" cellspacing="0">
  <td valign="top" style="min-width:100px">
    <div class="leftbar" itemscope itemtype="http://schema.org/AdministrativeArea">
      {if $mappage}<div class="leftbarmap"><a itemprop="map" href="{$mappage}" class="image" title="{$name} - harta"><img alt="{$name} - harta" src="{$mapthumb}" ></a></div>{/if}
      <meta itemprop="geo" content="http://despresate.strainu.ro/maps/uat-judete/{$siruta}.geojson" />
      <div class="leftbarelem map"></div>
      <script>{literal}
        $(document).ready(function() {
            var siruta = {/literal}{$siruta}{literal};
            var url = 'maps/uat-judete/' + siruta + '.geojson';
            $.getJSON(url, function(data) {
              load_leaflet_map($('.map')[0], data);
            });
        });
      {/literal}</script>
      <div class="leftbartitle">Date statistice</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="stats" class="leftbarelem">
         <tr><th>Nume</th><td itemprop="name">{$name}</td></tr>
         <tr><th>Cod SIRUTA</th><td>{$siruta}</td></tr>
         <tr itemprop="containedIn"><th>Regiune administrativă </th><td>{$region}</td></tr>
         <tr><th>Prescurtare</th><td>{$abbr}</td></tr>
         <tr><th>Regiune istorică</th><td>{$hist_region}</td></tr>
         <tr><th>Populație ({$census})</th><td>{$population|commify:0:',':'.'} locuitori</td></tr>
         <tr><th>Suprafață</th><td>{$surface|commify:2:',':'.'} km<sup>2</sup></td></tr>
         <tr><th>Densitate</th><td>{$density|commify:2:',':'.'} loc/km<sup>2</sup></td></tr>
        </table>
      <span  itemscope itemtype="http://schema.org/GovernmentOrganization">
      <div class="leftbartitle" itemprop="name">Consiliul județean</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="cj" class="leftbarelem">
         <tr><th>Președinte</th><td itemprop="employee">{if $cjpresid}<a href="http://agenda.grep.ro/person/{$cjpresid}" title="Date de contact pentru {$cjpres}">{$cjpres}</a>{else}{$cjpres}{/if}</td></tr>
         <tr><th>Adresă</th><td itemprop="address">{$cjaddr}</td></tr>
         <tr><th>Site</th><td><a itemprop="url" href="http://{$cjsite}" title="Site-ul Consiliului Județean {$name}">{$cjsite}</a></td></tr>
         <tr><th>Email</th><td itemprop="email"><a href="mailto:{$cjemail}" title="Emailul-ul Consiliului Județean {$name}">{$cjemail}</a></td></tr>
         <tr><th>Telefon</th><td itemprop="telephone">(0040) {$cjtel}</td></tr>
         <tr><th>Fax</th><td itemprop="faxNumber">(0040) {$cjfax}</td></tr>
        </table>
       </span>
      <span  itemscope itemtype="http://schema.org/GovernmentOrganization">
       <div class="leftbartitle" itemprop="name">Prefectura</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="prefect" class="leftbarelem">
         <tr><th>Prefect</th><td itemprop="employee">{if $prpresid}<a href="http://agenda.grep.ro/person/{$prpresid}" title="Date de contact pentru {$prpres}">{$prpres}</a>{else}{$prpres}{/if}</td></tr>
         <tr><th>Adresă</th><td itemprop="address">{$praddr}</td></tr>
         <tr><th>Site</th><td><a itemprop="url" href="http://{$prsite}" title="Site-ul Consiliului Județean {$name}">{$prsite}</a></td></tr>
         <tr><th>Email</th><td itemprop="email"><a href="mailto:{$premail}" title="Emailul-ul Consiliului Județean {$name}">{$premail}</a></td></tr>
         <tr><th>Telefon</th><td itemprop="telephone">(0040) {$prtel}</td></tr>
         <tr><th>Fax</th><td itemprop="faxNumber">(0040) {$prfax}</td></tr>
        </table>
       </span>
       <div class="leftbartitle">Alte informații</div>
         <ul id="otherlinks">
           <li><a href="https://ro.wikipedia.org/wiki/{$name}" title="Articolul Wikipedia despre {$name}" target="_blank">Articol Wikipedia</a></li>
           <li><a href="https://www.google.ro/search?hl=ro&q={$name}&meta=lr%3Dlang_ro" title="Căutare google după {$name}" target="_blank">Caută pe Google</a></li>
           <li><a href="http://www.facebook.com/share.php?u={$name}+http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" title="Distribuie pe Facebook" target="_blank">Distribuie pe Facebook</a></li>
           <li><a href="http://twitter.com/home?status={$name}+http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" title="Tweet" target="_blank">Distribuie pe Tweeter</a></li>
         </ul>
    </div>
  </td>
  <td valign=top>
	<div class="mainsection">
		<a name="info" />
		<div class="maintitle">Informații generale<a href="#top" class="toplink small">[sus]</a></div>
                <table class="pagetoc">
                    <tbody>
                        <tr><td>
                            <div class="pagetoctitle"><b>Cuprins</b></div>
                        </td></tr>
                        <tr><td>
                            <ul>
                                <li class="toclevel-1"><a href="#info">Informații generale</a></li>
                                <li class="toclevel-1"><a href="#adm">Administrație locală</a></li>
                                <li class="toclevel-1"><a href="#uat">Localități</a></li>
                                <li class="toclevel-1"><a href="#eco">Economie</a></li>
                                <li class="toclevel-1"><a href="#dem">Demografie</a></li>
                                <li class="toclevel-1"><a href="#mon">Monumente</a></li>
                                <li class="toclevel-1"><a href="#pic">Galerie</a></li>
                            </ul>
                        </td></tr>
                    </tbody>
                </table>
		<p><b>{$name}</b> este un județ al României aflat în regiunea administrativă {$region}. În coloana din stânga 
        aveți la dispoziție câteva informații de bază despre județ, iar mai jos sunt prezente detalii despre
        economia, demografia și cultura județului.</p>
    </div>
    <div class="mainsection">
		<a name="adm" />
		<div class="maintitle">Administrație locală<a href="#top" class="toplink small">[sus]</a></div>
		<p>
		{if $prpres || $prvice}
			{if $prpres}Prefectul județului {$shortname} este <i>{$prpres}</i>, numit de guvern în anul {$pryear}. {/if}
			{if $prvice}{if $pryear == $prviceyear}Tot din{else}Din{/if} {$prviceyear}, viceprefectul județului este <i>{$prvice}</i>. {/if}
		{else}
		Nu dispunem deocamdată de informatii despre prefectul și subprefectul judetului {$shortname}. Dacă aveti aceste date, vă rugăm să ne contactati.
		{/if}
		</p>
		<p>Consiliul județean rezultat în urma alegerilor din {$cjpresyear} are următoarea componență:</p>
		<ul class="cjcouncil">
			<li><b>Președinte:</b> {if $cjpresid}<a href="http://agenda.grep.ro/person/{$cjpresid}" title="Date de contact pentru {$cjpres}">{$cjpres}</a>{else}{$cjpres}{/if} {if $cjpresparty}({$cjpresparty}){/if}</li><!--TODO: partid-->
			<li><b>Vicepreședinți:</b>
				{if $cjvice}<ul> {foreach $cjvice as $vice}<li>{if $vice.id}<a href="http://agenda.grep.ro/person/{$vice.id}" title="Date de contact pentru {$vice.name}">{$vice.name}</a>{else}{$vice.name}{/if} {if $vice.party}({$vice.party}){/if}</li>{/foreach}</ul>
				{else} Nu dispunem deocamdată de numele vicepreședinților CJ {$shortname}. Dacă dețineți aceste date, vă rugăm să ne contactați.
				{/if}
			</li>
			<li><b>Consilieri:</b> {if $cjcouncil}<ul>{foreach $cjcouncil as $member}<li>{if $member.id}<a href="http://agenda.grep.ro/person/{$member.id}" title="Date de contact pentru {$member.name}">{$member.name}</a>{else}{$member.name}{/if} {if $member.party}({$member.party}){/if}</li>{/foreach}</ul>
						{else} Nu dispunem deocamdată de componența consiliului județean {$shortname}. Dacă dețineți aceste date, vă rugăm să ne contactați.
						{/if}</li>
		</ul>
    </div>
    <div class="mainsection">
		<a name="uat" />
		<div class="maintitle">Localități<a href="#top" class="toplink small">[sus]</a></div>
		<p>Unitățile administrativ-teritoriale ale județului {$shortname} sunt:</p>
		<ul style="-webkit-column-count: 3; -moz-column-count: 3; -o-column-count: 3; column-count: 3;">
		{foreach $uat as $village}
				<li><a href="sat.php?siruta={$village._siruta}">{$village.denloc|lower|capitalize}</a></li>
		{/foreach}
		</ul>
    </div>
    <div class="mainsection">
		<a name="eco" />
		<div class="maintitle">Economie și buget<a href="#top" class="toplink small">[sus]</a></div>
		<p>Nu avem încă informații legate de economia acestui județ.</p>
    </div>
    <div class="mainsection">
		<a name="dem" />
		<div class="maintitle">Demografie<a href="#top" class="toplink small">[sus]</a></div>
		În graficul de pe prima linie este prezentată evoluția demografică a județului de-a lungul istoriei, iar graficele de sub el prezintă distribuția populației după naționalitate și religie la recensământul din anul {$demography[sizeof($demography) - 1].an}.
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
          {literal}google.load("visualization", "1", {packages:["corechart"]});
          google.setOnLoadCallback(drawChart);
          function drawChart() {
            var hist_data = google.visualization.arrayToDataTable([
              ['An', 'Locuitori'],{/literal}
            {foreach $demography as $oldcensus}
                ['{$oldcensus.an}', {$oldcensus.populatie}],
            {/foreach}
        {literal}]);
        
            var nat_data = google.visualization.arrayToDataTable([
              ['Naționalitate', 'Locuitori'],{/literal}
            {foreach $nationalities as $nationality}
                ['{$nationality.name}', {$nationality.populatie}],
            {/foreach}
        {literal}]);
        
            var rel_data = google.visualization.arrayToDataTable([
              ['Religie', 'Locuitori'],{/literal}
            {foreach $religions as $religion}
                ['{$religion.name}', {$religion.populatie}],
            {/foreach}
        {literal}]);

            var hist_options = {
              title: 'Evoluție demografică',
              hAxis: {title: 'An'},
              vAxis: {baseline: 0},
              legend: {position: 'right'},
            };

            var nat_options = {
              title: 'Componența etnică',
              legend: {position: 'right', alignment: 'center'},
            };

            var rel_options = {
              title: 'Componența confesională',
              legend: {position: 'right', alignment: 'center'},
            };

            var hist_chart = new google.visualization.ColumnChart(document.getElementById('demografie'));
            hist_chart.draw(hist_data, hist_options);
            var nat_chart = new google.visualization.PieChart(document.getElementById('nationalitate'));
            nat_chart.draw(nat_data, nat_options);
            var rel_chart = new google.visualization.PieChart(document.getElementById('religie'));
            rel_chart.draw(rel_data, rel_options);
          }{/literal}
        </script>
        <div id="graphs">
            <div id="demografie" class="graph full"></div>
            <div id="nationalitate" class="graph half"></div>
            <div id="religie" class="graph half"></div>
        </div>
    </div>
    <div class="mainsection">
		<a name="mon" />
		<div class="maintitle">Monumente<a href="#top" class="toplink small">[sus]</a></div>
		<p>Mai jos aveți 10 monumente aleatorii din {$name|lcfirst}. Imaginile monumentelor provin de la concursul foto <a href="http://wikilovesmonuments.ro">WikiLovesMonuments România</a></p>
		<p><em>Lista completă a monumentelor istorice din {$name|lcfirst} este 
		<a href="https://ro.wikipedia.org/wiki/Lista monumentelor istorice din {$name|lcfirst}" title="Lista monumentelor istorice din {$name|lcfirst}">disponibilă la Wikipedia.</a></em></p>
		<table class="monumentstable">
		<tr><th class="monumentsheader">Cod</th><th class="monumentsheader">Denumire</th><th class="monumentsheader">Arhitect</th><th class="monumentsheader">Poză</th>
		{foreach $monuments as $monument}
		<tr>
			<td>{$monument.cod}</td>
			<td>{$monument.denumire}</td>
			<td>{if $monument.arhitect}{$monument.arhitect}{else}N/A{/if}</td>
			<td style="text-align: center;">{if $monument.imagine}<img src="{$monument.thumburl}" width="{if ($monument.thumbh < $monument.thumbw)}60px{else}40px{/if}" height="{if ($monument.thumbh > $monument.thumbw)}60px{else}40px{/if}"/>{/if}</td>
		</tr>
		{/foreach}
		</table>
    </div>
    <div class="mainsection">
		<a name="pic" />
		<div class="maintitle">Galerie<a href="#top" class="toplink small">[sus]</a></div>
                <div style="text-align:center; padding-left:10px">
		{include file='tpl/pic.tpl'}
                </div>
                <div  style="clear:both;"></div>
    </div>
  </td>
</table>
{include file='tpl/footer.tpl'}
