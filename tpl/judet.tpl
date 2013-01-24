<div class="infobox" style="width:300px; float:right">
<div class="plaintext" style="width:300px">
<table class="infocaseta" cellspacing="0" style="font-size: 88%; padding:0; line-height: 1.5em;">
<tr>
<td colspan="2" class="antet harta">{$name}</td>
</tr>
<tr>
<td colspan="2" style="text-align: center; padding: 0.7em 0.8em 0.7em 0.8em;;">
<div class="floatnone"><a href="{$imgpage}" class="image" title="{$imgname}"><img alt="{$imgname}" src="{$imgthumb}" width="250" /></a></div>
<small>{$imgname}</small></td>
</tr>
<tr class="mergedrow">
<td colspan="2" align="center">
<center>
<div style="width:290px; float: none; clear: none">
<div>
{if $mappage}<div style="position: relative;"><a href="{$mappage}" class="image" title="{$name} - harta"><img alt="{$name} - harta" src="{$mapthumb}" width="250" /></a></div>
<div style="font-size: 90%; padding-top:3px;"></div>
</div>
</div>
<small>Localizarea județului pe harta României</small></center>{/if}
</td>
</tr>
<!--tr class="mergedbottomrow">
<th colspan="2" style="text-align: center; font-size: small; padding-bottom: 0.7em;">Coordonate: <span style="white-space: nowrap;"><span id="coordinates"><span class="latitude">{$lat}</span><span class="longitude">{$lon}</span></span></span></th>
</tr-->
<tr>
<td colspan="2">
<hr /></td>
</tr>
<tr class="mergedtoprow">
<th>Țară</th>
<th class="adr">România</th>
</tr>
<tr>
<td colspan="2">
<hr /></td>
</tr>
<tr class="mergedtoprow">
<th>Suprafață</td>
<td>{$surface} km<sup>2</sup></td>
</tr>
<tr class="mergedtoprow">
<th>Populație ({$census})</td>
<td>{$population} locuitori</td>
</tr>
<tr class="mergedtoprow">
<th>Densitate</td>
<td>{$density|commify:2:',':'.'} loc./km<sup>2</sup></td>
</tr>
<tr>
<td colspan="2">
<hr /></td>
</tr>
</div>
</div>

<div class="plaintext" style="width:800px">

<p>
<strong>{$name}</strong>
</p>
