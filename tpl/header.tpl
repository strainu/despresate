<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{$name} - Despre sate</title>
  <link rel="stylesheet" href="style.css" /> 
</head>
<body>
  <table width="970">
    <td width="250">
      <a href="/" title="Informații statistice despre localitățile din România">
        <img itemprop="image" src="images/logo.png" border=0
        alt="Informații statistice despre localitățile din România">
      </a>
    </td>

    <td width="300">
      {* The search form *}
      <form action="" method="get">
        <input type="hidden" name="cid" value="search" />
        <input type="text" size="18" name="q" id="search_form" value="{$escaped_query}" id="q" />
        <input type="submit" value="Caută" id="cauta" />
      </form>
      <div class="small gray">
        ex: "județul Prahova", "Vadu Lat", "Bucsani, Dâmbovița", etc.
      </div>
    </td>

    <td align="right" valign="top">
      {*include file="login_bar.tpl"*}
      <div class="title">{$name}</div>
    </td>
  </table>
  <hr class="endheader" />
