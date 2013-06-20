<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{$name} - Despre sate</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.css">
  <!--[if lte IE 8]><link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.ie.css"><![endif]-->
  <script src="./local.min.js" type="text/javascript"></script>
  <script src="./map.js" type="text/javascript"></script>
  <script src="./jquery-1.8.3.min.js" type="text/javascript"></script>
  <script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script>
<script>
  (function() {
    var cx = '009028326909464764794:WMX241262072';
    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
  })();
</script>
</head>
<body>
  <a name="top" />
  <table>
    <td width="15%">
      <a href="/" title="Informații statistice despre localitățile din România">
        <img itemprop="image" src="images/logo_120.png" border=0 width="120px" height="120px" 
        alt="Informații statistice despre localitățile din România">
      </a>
    </td>

    <td width="30%">
      {* The search form *}
      <form action="cauta.php" method="get">
        <input type="hidden" name="cid" value="search" />
        <input type="text" size="26" name="q" id="search_form" value="{$escaped_query}" id="q" />
        <input type="submit" value="Caută" id="cauta" />
      </form>
<!--gcse:searchbox-only></gcse:searchbox-only-->
      <div class="small gray">
        ex: "județul Prahova", "Vadu Lat", "Bucsani, Dâmbovița", etc.
      </div>
    </td>

    <td align="right" valign="top">
      {include file="tpl/login_bar.tpl"}
      <div class="title">{$name}</div>
    </td>
  </table>
  <hr class="endheader" />
