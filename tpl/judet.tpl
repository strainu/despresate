{include file='tpl/header.tpl'}
<table width=970 cellpadding=0 cellspacing=0>
  <td valign=top width=340>
    <div class="leftbar">
      {if $mappage}><div class="leftbarmap">><a href="{$mappage}" class="image" title="{$name} - harta"><img alt="{$name} - harta" src="{$mapthumb}" ></a></div>{/if}
      <div class="leftbartitle">Date statistice</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="stats" class="leftbarelem">
         <tr><th>Regiune</th><td>{$region}</td></tr>
         <tr><th>Populație ({$census})</th><td>{$population} locuitori</td></tr>
         <tr><th>Suprafață</th><td>{$surface} km<sup>2</sup></td></tr>
         <tr><th>Densitate</th><td>{$density|commify:2:',':'.'} loc/km<sup>2</sup></td></tr>
        </table>
      <div class="leftbartitle">Consiliul județean</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="cj" class="leftbarelem">
         <tr><th>Președinte</th><td>{$cjpres}</td></tr>
         <tr><th>Adresă</th><td>{$cjaddr}</td></tr>
         <tr><th>Site</th><td><a href="http://{$cjsite}" title="Site-ul Consiliului Județean {$name}">{$cjsite}</a></td></tr>
         <tr><th>Email</th><td><a href="mailto:{$cjemail}" title="Emailul-ul Consiliului Județean {$name}">{$cjemail}</a></td></tr>
         <tr><th>Telefon</th><td>(0040) {$cjtel}</td></tr>
         <tr><th>Fax</th><td>(0040) {$cjfax}</td></tr>
        </table>
       <div class="leftbartitle">Prefectura</div>
        <table cellspacing="0" cellpadding="0" width="100%" id="prefect" class="leftbarelem">
         <tr><th>Prefect</th><td>{$prpres}</td></tr>
         <tr><th>Adresă</th><td>{$praddr}</td></tr>
         <tr><th>Site</th><td><a href="http://{$prsite}" title="Site-ul Consiliului Județean {$name}">{$prsite}</a></td></tr>
         <tr><th>Email</th><td><a href="mailto:{$premail}" title="Emailul-ul Consiliului Județean {$name}">{$premail}</a></td></tr>
         <tr><th>Telefon</th><td>(0040) {$prtel}</td></tr>
         <tr><th>Fax</th><td>(0040) {$prfax}</td></tr>
        </table>
       <div class="leftbartitle">Alte informații</div>
         <ul id="otherlinks">
           <li><a href="https://ro.wikipedia.org/wiki/{$name}" title="Articolul Wikipedia despre {$name}">Articol Wikipedia</a></li>
           <li><a href="https://www.google.ro/search?hl=ro&q={$name}&meta=lr%3Dlang_ro" title="Căutare google după {$name}">Caută pe Google</a></li>
           <!--li><a href="{$name}" title="Articolul Wikipedia despre {$name}">Articol Wikipedia</a></li-->
         </ul>
    </div>
  </td>
  <td>&nbsp;</td>
</table>
{include file='tpl/footer.tpl'}
