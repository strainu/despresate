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
    <li><a href="data.php" rel="hiddendata">Date brute ▼</a>
        <div id="hiddendata" class="hiddentoc">
            <a href="data.php?f=csv&type=all&commune={$siruta}" style="width:100px">CSV</a>
            <div  style="clear:both;"></div>
            <a href="data.php?f=json&type=all&commune={$siruta}" style="width:100px">JSON</a>
        </div>
    </li>
    <li><a href="despre.php">Despre</a></li>
    <li><a href="cauta.php">Căutare</a></li>
</ul>
<hr />
<table width="100%" cellpadding="0" cellspacing="0">
  <td valign=top width="300px">
    <div class="leftbar" itemscope itemtype="http://schema.org/AdministrativeArea">
      {if $mappage}<div class="leftbarmap"><a itemprop="map" href="{$mappage}" class="image" title="{$name} - harta"><img alt="{$name} - harta" src="{$mapthumb}" ></a></div>{/if}
      <meta itemprop="geo" content="http://despresate.strainu.ro/maps/uat-comune/{$siruta}.geojson" />
      <div class="leftbarelem map"></div>
      <script>{literal}
        $(document).ready(function() {
            var siruta = {/literal}{$siruta}{literal};
            var url = 'maps/uat-comune/' + siruta + '.geojson';
            $.getJSON(url, function(data) {
				var url_points = 'sat_puncte.php?siruta=' + siruta;
				$.getJSON(url_points, function(data2) {
					load_leaflet_map($('.map')[0], data, data2);
				});
            });
        });
      {/literal}</script>
      <div class="leftbartitle">Date statistice</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="stats" class="leftbarelem">
         <tr><th>Nume</th><td itemprop="name">{$name}</td></tr>
         <tr itemprop="containedIn"><th>Județ </th><td><a href="judet.php?id={$countyid}" >{$shortcounty}</a></td></tr>
         <tr><th>Cod SIRUTA</th><td>{$siruta}</td></tr>
         <tr><th>Populație ({$census})</th><td>{$population|commify:0:',':'.'} locuitori</td></tr>
         <tr><th>Suprafață</th><td>{$surface|commify:2:',':'.'} km<sup>2</sup></td></tr>
         <tr><th>Densitate</th><td>{$density|commify:2:',':'.'} loc/km<sup>2</sup></td></tr>
        </table>
       <div class="leftbartitle">Primăria</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="prefect" class="leftbarelem" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
         <tr><th>Primar</th><td>{if $mayorid}<a href="http://agenda.grep.ro/person/{$mayorid}" title="Date de contact pentru {$mayor}">{$mayor}</a>{else}{$mayor}{/if}</td></tr>
         <tr><th>Adresă</th><td itemprop="streetAddress">{$chaddr}</td></tr>
         <tr><th>Site</th><td itemprop="url"><a href="http://{$chsite}" title="Site-ul primăriei {$shortname}">{$chsite}</a></td></tr>
         <tr><th>Email</th><td itemprop="email"><a href="mailto:{$chemail}" title="Emailul-ul primăriei {$shortname}">{$chemail}</a></td></tr>
         <tr><th>Telefon</th><td itemprop="telephone">(0040) {$chtel}</td></tr>
        </table>
       <div class="leftbartitle">Alte informații</div>
         <ul id="otherlinks">
           <li><a href="https://ro.wikipedia.org/wiki/{$wikipedia}" title="Articolul Wikipedia despre {$shortname}" target="_blank">Articol Wikipedia</a></li>
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
                                <li class="toclevel-1"><a href="#edu">Educație</a></li>
                                <li class="toclevel-1"><a href="#dem">Demografie</a></li>
                                <li class="toclevel-1"><a href="#mon">Monumente</a></li>
                                <li class="toclevel-1"><a href="#pic">Galerie</a></li>
                            </ul>
                        </td></tr>
                    </tbody>
                </table>
        <p>{$shortname} este {$type.article} {$type.term} aflat{if $type.article == "o"}ă{/if} în <a href="judet.php?id={$countyid}" >{$county}</a>, România.</p>
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
        {if count($uat) }
            <p>{$type.articulated|capitalize} {$shortname} are în componență {if count($uat) > 1}următoarele {$uat|@count} localități{else}localitatea{/if}:</p>
            <ul style="-webkit-column-count: 3; -moz-column-count: 3; -o-column-count: 3; column-count: 3;">
            {foreach $uat as $village}
                <li><b>{$village.denloc|lower|capitalize}</b> {if $village.codp}(cod poștal {$village.codp}){/if}</li>
            {/foreach}
            </ul>
        {else}
            <p>{$shortname} nu are în componență nicio localitate.</p>
        {/if}
    </div>
    <div class="mainsection">
        <a name="eco" />
        <div class="maintitle">Economie și buget<a href="#top" class="toplink small">[sus]</a></div>
        <p>Nu avem încă informații legate de economia acestei localități.</p>
    </div>
    <div class="mainsection">
	<a name="edu" />
        <div class="maintitle">Educație<a href="#top" class="toplink small">[sus]</a></div>
        <p>Există {sizeof($schools)} instituții de învățământ (școli, case ale elevilor etc.) în {$shortname}.</p><ul>{foreach $schools as $school}<li>{if $school.web}<a href="http:/	/{$school.web}" title="Site-ul {$school.nume}">{$school.nume}</a>{else}{$school.nume}{/if} {if $school.limba}({$school.limba}){/if}</li>{/foreach}</ul>
    </div>
    <div class="mainsection">
        <a name="dem" />
        <div class="maintitle">Demografie<a href="#top" class="toplink small">[sus]</a></div>
	<p>În graficul de pe prima linie este prezentată evoluția demografică a localității de-a lungul istoriei, iar graficele de sub el prezintă distribuția populației după naționalitate și religie la recensământul din anul {$demography[sizeof($demography) - 1].an}.</p>
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
        <div class="maintitle">Cultură și monumente<a href="#top" class="toplink small">[sus]</a></div>
        {if count($monuments) }
            <p>Mai jos aveți {$monuments|@count} monumente aleatorii din {$shortname}. Imaginile monumentelor provin de la concursul foto <a href="http://wikilovesmonuments.ro">WikiLovesMonuments România</a></p>
            <p><em>Lista completă a monumentelor istorice din {$shortname} este 
            <a href="https://ro.wikipedia.org/wiki/Lista monumentelor istorice din {$county}" title="Lista monumentelor istorice din {$county}">disponibilă la Wikipedia.</a></em></p>
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
        {else}
            <p>Nu există niciun monument istoric în {$shortname}. Puteți vedea alte monumente istorice din {$county} <a href="https://ro.wikipedia.org/wiki/Lista monumentelor istorice din {$county}" title="Lista monumentelor istorice din {$county}">pe Wikipedia.</a></p>
        {/if}
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
