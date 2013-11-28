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
	<table class="pagetoc">
               <tbody>
                    <tr><td>
                        <div class="pagetoctitle"><b>Cuprins</b></div>
                    </td></tr>
                    <tr><td>
                        <ul>
                            <li class="toclevel-1"><a href="#despre">Despre proiect</a></li>
                            <li class="toclevel-1"><a href="#copyright">Drepturi de autor</a></li>
                            <li class="toclevel-1"><a href="#multumiri">Mulțumiri</a></li>
                        </ul>
                    </td></tr>
                </tbody>
            </table>
	<a name="despre" />
	<div class="maintitle">Despre proiect<a href="#top" class="toplink small">[sus]</a></div>

	<p>Despre Sate este o platformă cu informații statistice despre fiecare sat, comună, oraș sau județ din România. Informațiile vor putea fi actualizate de oricine dorește, atât timp cât există o sursă care să susțină informația respectivă.</p>

	<a name="copyright" />
	<div class="maintitle">Drepturi de autor<a href="#top" class="toplink small">[sus]</a></div>
	<p>Informațiile din acest site au surse diferite și pot fi folosite conform condițiilor enumerate mai jos. Pe măsură ce vor deveni disponibile sub o licență mai permisivă, informațiile vor fi mutate dintr-o categorie în alta.</p>
	<p>Datele privitoare la suprafața comunelor au ca sursă biroul european de statistică Eurostat. Ele pot fi folosite atât în scopuri comerciale cât și necomerciale cu menționarea sursei.</p>
	<p>Limitele unităților administrativ-teritoriale provin din cadrul proiectului <a href="http://www.politicalcolours.ro/">Political Colours</a> și pot fi folosite conform <a href="http://www.politicalcolours.ro/colab.html">notiței de copyright</a> de pe site-ul respectiv.</p>
	<p>Datele despre monumentele istorice (cu excepția coordonatelor și pozelor) provin din monitorul oficial, nefăcând deci obiectul dreptului de autor. Pentru drepturile de autor asupra imaginilor, vezi mai jos.</p>
	<p>Datele despre populație provin de pe site-ul <a href="http://www.insse.ro">Institutului Național de Statistică</a> și pot fi reutilizate conform <a href="http://www.legi-internet.ro/legislatie-itc/altele/legea-nr1092007-reutilizarea-informatiilor.html">legii 109/2007 privind reutilizarea informațiilor din instituțiile publice</a>, cu modificările și completările ulterioare.</p>
	<p><a href="http://date.gov.ro/dataset/siruta">Codurile SIRUTA</a>, <a href="http://date.gov.ro/dataset/coduri-postale-romania">codurile poștale</a> și <a href="http://date.gov.ro/dataset/retea_scolara_2013-2014">lista unităților de învățământ</a> provin de pe site-ul <a href="http://date.gov.ro">date.gov.ro</a> și sunt licențiate sub termenii licenței <a href="http://data.gov.ro/base/images/logoinst/OGL-ROU-1.0.pdf">OGL-ROU-1.0</a>.</p>
	<p>Toate celelalte informații disponibile provin de pe site-urile instituțiilor publice și pot fi reutilizate conform <a href="http://www.legi-internet.ro/legislatie-itc/altele/legea-nr1092007-reutilizarea-informatiilor.html">legii 109/2007 privind reutilizarea informațiilor din instituțiile publice</a>, cu modificările și completările ulterioare.</p>
	<p>Licențele pozelor sunt diferite, în funcție de sursă. Sursa este menționată sub imagine, împreună cu o legătură spre pagina ce conține licența imaginii.</p>

	<a name="multumiri" />
	<div class="maintitle">Mulțumiri<a href="#top" class="toplink small">[sus]</a></div>
	<p>Mulțumim tuturor oamenilor care ne-au ajutat cu diverse părți ale proiectului: lui <a href="http://nicubunu.ro/">Nicu Buculei</a> pentru siglă, lui <a href="http://grep.ro">Alex Morega</a> pentru hărți și integrarea cu <a href="http://agenda.grep.ro/">agenda</a>, echipei Political Colours pentru datele prețioase și pentru proiectul foarte util, lui Bogdan Manolea pentru diversele sfaturi legislative și nu numai, participanților la hackathonul <a href="http://wiki.osgeo.org/wiki/FOSS4G_CEE_2013_Open_GeoData_Hackathon">FOSS4G-CEE 2013</a> pentru programare și datele oferite.</p>

</div>


{include file='tpl/footer.tpl'}
