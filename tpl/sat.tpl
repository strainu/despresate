{include file='tpl/header.tpl'}
<ul class="toc" id="toc">
    <li><a href="/">Acasă</a></li>
    <li><a href="judet.php?id={$countyid}" rel="hiddenmenu">Alte localități ▼</a>
        <div id="hiddenmenu" class="hiddentoc">
            {foreach $commune_list as $othercommune name=othercommunes}
                <a href="sat.php?siruta={$othercommune._siruta}">{$othercommune.denloc|lower|capitalize}</a>
                {if $smarty.foreach.othercommunes.index % 3 == 2}<div  style="clear:both;"></div>{/if}
            {/foreach}
        </div>
    </li>
    <li><a href="data.php?id={$siruta}&f=csv">Date brute</a></li>
    <li><a href="despre.php">Despre</a></li>
    <li><a href="cauta.php">Căutare</a></li>
</ul>
<hr />
<table width="100%" cellpadding="0" cellspacing="0">
  <td valign=top width="300px">
    <div class="leftbar">
      {if $mappage}<div class="leftbarmap"><a href="{$mappage}" class="image" title="{$name} - harta"><img alt="{$name} - harta" src="{$mapthumb}" ></a></div>{/if}
      <div class="leftbarelem map"></div>
      <script>{literal}
        $(document).ready(function() {
            var siruta = {/literal}{$siruta}{literal};
            var url = 'maps/uat-comune/' + siruta + '.geojson';
            $.getJSON(url, function(data) {
              load_leaflet_map($('.map')[0], data);
            });
        });
      {/literal}</script>
      <div class="leftbartitle">Date statistice</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="stats" class="leftbarelem">
         <tr><th>Județ</th><td>{$county}</td></tr>
         <tr><th>Cod SIRUTA</th><td>{$siruta}</td></tr>
         <tr><th>Populație ({$census})</th><td>{$population|commify:0:',':'.'} locuitori</td></tr>
         <tr><th>Suprafață</th><td>{$surface|commify:2:',':'.'} km<sup>2</sup></td></tr>
         <tr><th>Densitate</th><td>{$density|commify:2:',':'.'} loc/km<sup>2</sup></td></tr>
        </table>
       <div class="leftbartitle">Primăria</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="prefect" class="leftbarelem">
         <tr><th>Primar</th><td>{if $mayorid}<a href="http://agenda.grep.ro/person/{$mayorid}" title="Date de contact pentru {$mayor}">{$mayor}</a>{else}{$mayor}{/if}</td></tr>
         <tr><th>Adresă</th><td>{$chaddr}</td></tr>
         <tr><th>Site</th><td><a href="http://{$chsite}" title="Site-ul primăriei {$shortname}">{$chsite}</a></td></tr>
         <tr><th>Email</th><td><a href="mailto:{$chemail}" title="Emailul-ul primăriei {$shortname}">{$chemail}</a></td></tr>
         <tr><th>Telefon</th><td>(0040) {$chtel}</td></tr>
        </table>
       <div class="leftbartitle">Alte informații</div>
         <ul id="otherlinks">
           <li><a href="https://ro.wikipedia.org/wiki/{$shortname}" title="Articolul Wikipedia despre {$shortname}">Articol Wikipedia</a></li>
           <li><a href="https://www.google.ro/search?hl=ro&q={$name}&meta=lr%3Dlang_ro" title="Căutare google după {$name}">Caută pe Google</a></li>
           <li><a href="http://www.facebook.com/share.php?u={$name}+http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" title="Distribuie pe Facebook">Distribuie pe Facebook</a></li>
           <li><a href="http://twitter.com/home?status={$name}+http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" title="Tweet">Distribuie pe Tweeter</a></li>
         </ul>
    </div>
  </td>
  <td valign=top>
    <div class="mainsection">
        <a name="info" />
        <div class="maintitle">Informații generale<a href="#top" class="toplink small">[sus]</a></div>
        <p>{$shortname} este o localitate din România aflată în județul {$county}.</p>
    </div>
    <div class="mainsection">
        <a name="adm" />
        <div class="maintitle">Administrație locală<a href="#top" class="toplink small">[sus]</a></div>
        <p>Primarul localității {$shortname} este {if $mayorid}<a href="http://agenda.grep.ro/person/{$mayorid}" title="Date de contact pentru {$mayor}">{$mayor}</a>{else}<i>{$mayor}</i>{/if} ({$mayorparty}). A fost ales în anul {$mayoryear}.</p>
        <p>Consiliul local rezultat în urma alegerilor din {$clyear} are următoarea componență:</p>
        <ul class="cjcouncil">
            <li><b>Viceprimar:</b>{if $clvice}{$vice.name} {if $vice.party}({$vice.party}){/if}
                {else} Nu dispunem deocamdată de numele viceprimarului din {$shortname}. Dacă dețineți aceste date, vă rugăm să ne contactați.
                {/if}
            </li>
            <li><b>Consilieri:</b> {if $clcouncil}<ul>{foreach $clcouncil as $member}<li>{if $member.id}<a href="http://agenda.grep.ro/person/{$member.id}" title="Date de contact pentru {$member.name}">{$member.name}</a>{else}{$member.name}{/if} {if $member.party}({$member.party}){/if}</li>{/foreach}</ul>
                        {else} Nu dispunem deocamdată de componența consiliului local {$shortname}. Dacă dețineți aceste date, vă rugăm să ne contactați.
                        {/if}</li>
        </ul>
    </div>
    <div class="mainsection">
        <a name="uat" />
        <div class="maintitle">Localități<a href="#top" class="toplink small">[sus]</a></div>
        <p>Satele ce intră în componența localității {$shortname} sunt:</p>
        <ul style="-webkit-column-count: 3; -moz-column-count: 3; -o-column-count: 3; column-count: 3;">
        {foreach $uat as $village}
                <li><b>{$village.denloc|lower|capitalize}</b> {if $village.codp}(cod poștal {$village.codp}){/if}</li>
        {/foreach}
        </ul>
    </div>
    <div class="mainsection">
        <a name="eco" />
        <div class="maintitle">Economie<a href="#top" class="toplink small">[sus]</a></div>
        <p>Nu avem încă informații legate de economia acestei localități.</p>
    </div>
    <div class="mainsection">
        <a name="dem" />
        <div class="maintitle">Demografie<a href="#top" class="toplink small">[sus]</a></div>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
          {literal}google.load("visualization", "1", {packages:["corechart"]});
          google.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['An', 'Locuitori'],{/literal}
            {foreach $demography as $oldcensus}
                ['{$oldcensus.an}', {$oldcensus.populatie}],
            {/foreach}
        {literal}]);

            var options = {
              title: 'Evoluție demografică',
              hAxis: {title: 'An'},
              vAxis: {baseline: 0}
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('demografie'));
            chart.draw(data, options);
          }{/literal}
        </script>
        <div id="demografie" style="width: 50%; height: 300px"></div>
    </div>
    <div class="mainsection">
        <a name="mon" />
		<div class="maintitle">Monumente<a href="#top" class="toplink small">[sus]</a></div>
		<p>Mai jos aveți 10 monumente aleatorii din {$shortname}. Imaginile monumentelor provin de la concursul foto <a href="http://wikilovesmonuments.ro">WikiLovesMonuments România</a></p>
		<p><em>Lista completă a monumentelor istorice din {$shortname} este 
		<a href="https://ro.wikipedia.org/wiki/Lista monumentelor istorice din județul {$county}" title="Lista monumentelor istorice din județul {$county}">disponibilă la Wikipedia.</a></em></p>
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
<script type="text/javascript">
//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
tabdropdown.init("toc", 0)
</script>
{include file='tpl/footer.tpl'}
