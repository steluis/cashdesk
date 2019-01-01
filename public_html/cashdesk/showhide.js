if(document.getElementById && document.createElement){
document.write('<style type="text/css">*.toggle{display:none}</style>');
window.onload=function(){
    /*le modifiche allo script vanno solo fatte qui*/
    Attiva("amount","mostra stato amount","nascondi stato amount");
    Attiva("commenti","mostra commenti","nascondi commenti");
    Attiva("immagine","visualizza immagine","nascondi immagine");
    }
}

function Attiva(id,s1,s2){
var el=document.getElementById(id);
el.style.display="none";
var c=document.createElement("div");
var link=document.createElement("a");
link.href="#";
link.appendChild(document.createTextNode(s1));
link.onclick=function(){
    link.firstChild.nodeValue = (link.firstChild.nodeValue==s1) ? s2 : s1;
    el.style.display=(el.style.display=="none") ? "block" : "none";
    return(false);
    }
c.appendChild(link);
el.parentNode.insertBefore(c,el);
}