// ==UserScript==
// @name        Extragere date primarii
// @namespace   http://www.strainu.ro/
// @include     http://www.dpfbl.mai.gov.ro/harta_judete.html
// @version     1
// This script is meant to extract all existing sites from http://www.dpfbl.mai.gov.ro/harta_judete.html and linked pages
// ==/UserScript==

var result = "";
    
function loadXMLDoc(url)
{
    //alert(url);
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var parser = new DOMParser();
            var xmlDoc = parser.parseFromString(xmlhttp.responseText, "text/html");
            siruta = xmlDoc.getElementsByClassName("cod_siruta");
            page = xmlDoc.getElementsByClassName("webpage");
            sql = "";
            for (i=1;i<siruta.length;i++){
                code = parseInt(siruta[i].childNodes[1].textContent);
                pg = page[i].childNodes[1].textContent;
                if(!code || code > 1000)
                    continue;
                sql += "UPDATE `judet` SET `sitecj`='" + pg + "' WHERE `siruta`=" + code + ";\n";
            }
            last_result = result;
            result += sql;
            if (result == last_result)
                alert(result);
        }
    }
    xmlhttp.open("GET",url,false);
    xmlhttp.send();
}
    
loadXMLDoc("AB.html");
loadXMLDoc("AR.html");
loadXMLDoc("AG.html");
loadXMLDoc("BC.html");
loadXMLDoc("BH.html");
loadXMLDoc("BN.html");
loadXMLDoc("BT.html");
loadXMLDoc("BV.html");
loadXMLDoc("BR.html");
loadXMLDoc("BZ.html");
loadXMLDoc("CS.html");
loadXMLDoc("CL.html");
loadXMLDoc("CJ.html");
loadXMLDoc("CT.html");
loadXMLDoc("CV.html");
loadXMLDoc("DB.html");
loadXMLDoc("DJ.html");
loadXMLDoc("GL.html");
loadXMLDoc("GR.html");
loadXMLDoc("GJ.html");
loadXMLDoc("HR.html");
loadXMLDoc("HD.html");
loadXMLDoc("IL.html");
loadXMLDoc("IS.html");
loadXMLDoc("IF.html");
loadXMLDoc("MM.html");
loadXMLDoc("MH.html");
loadXMLDoc("MS.html");
loadXMLDoc("NT.html");
loadXMLDoc("OT.html");
loadXMLDoc("PH.html");
loadXMLDoc("SM.html");
loadXMLDoc("SJ.html");
loadXMLDoc("SB.html");
loadXMLDoc("SV.html");
loadXMLDoc("TR.html");
loadXMLDoc("TM.html");
loadXMLDoc("TL.html");
loadXMLDoc("VS.html");
loadXMLDoc("VL.html");
loadXMLDoc("VN.html");
loadXMLDoc("B.html");
