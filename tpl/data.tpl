{include file='tpl/header.tpl'}
<ul class="toc" id="toc">
	<li><a href="/">Acasă</a></li>
	<li><a href="" rel="hiddenmenu">Toate județele ▼</a>
		<div id="hiddenmenu" class="hiddentoc">
			{foreach $county_list as $othercounty name=othercounties}
				<a href="judet.php?id={$othercounty.jud}">{$othercounty.denloc}</a>
				{if $smarty.foreach.othercounties.index % 2 == 1}<div  style="clear:both;"></div>{/if}
			{/foreach}
		</div>
	</li>
	<li><a href="data.php">Date brute</a></li>
	<li><a href="despre.php">Despre</a></li>
	<li><a href="cauta.php">Căutare</a></li>
</ul>
<hr />

<div class="mainsection">
	<a name="query" />
	<div class="maintitle">Export de date<a href="#top" class="toplink small">[sus]</a></div>
	<!--p>Funcționalitatea de exportare a unor date nu este încă disponibilă.</p-->
	<form action="" method="get">
		<p>Doresc să descarc 
			<select name="type">
				<option value="stats">datele statistice</option>
				<option value="leaders">conducătorii</option>
				<option value="all" selected>toate datele</option>
			</select> despre
			<select name="county">
				<option value="all" selected>toate județele</option>
			{foreach $county_list as $othercounty name=othercounties}
				<option value="{$othercounty.jud}">{$othercounty.denloc}</option>
			{/foreach}
			</select>, UATul
			<select name="commune">
				<option value="none" selected>niciunul (date județ)</option>
				<option value="all">toate</option>
			</select> în format 
			<select name="f">
				<option value="csv" selected>CSV</option>
				<option value="html">HTML</option>
				<option value="json">JSON</option>
			</select>
			<input type="submit" value="Obține datele!" />
		</p>
	</form>

	<a name="exportat" />
	<div class="maintitle">Fișiere pre-exportate<a href="#top" class="toplink small">[sus]</a></div>
	<p>Puteți descărca următoarele date:
		<ul>
			<li> <a href="data/despresate.sql.bz2">Un dump al bazei de date</a> (SQL)</li>
			<li> <a href="data/pcj.csv">Președinții consiliilor județene (2008-2012)</a> (CSV)</li>
			<li> <a href="data/primari.csv">Primari (1996-2012)</a> (CSV)</li>
			<li> <a href="data/commune_historic_population.csv">Populația județelor și comunelor (1930-1992)</a> (CSV)</li>
			<li> <a href="data/demografie.csv">Populația județelor și comunelor (2011)</a> (CSV)</li>
		</ul>
	</p>
</div>


{include file='tpl/footer.tpl'}
