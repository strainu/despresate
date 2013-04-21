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
	<a name="rezultate" />
	<div class="maintitle">Rezultatele căutării<a href="#top" class="toplink small">[sus]</a></div>
	<p>Căutarea nu este încă implementată. Reveniți mai târziu.</p>

</div>


{include file='tpl/footer.tpl'}
