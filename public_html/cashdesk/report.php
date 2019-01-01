<?
/*******************************************************************************
* CASH DESK - DATA ENTRY TO PRINT FINAL REPORT                                 *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
*******************************************************************************/

?>
<HTML>
<head>
<link rel="stylesheet" type="text/css" media="all" href="skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="calendar-it.js"></script>

<script type="text/javascript">

function IsValidTime(timeStr,nome) {
// Checks if time is in HH:MM:SS AM/PM format.
// The seconds and AM/PM are optional.

var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

var matchArray = timeStr.match(timePat);
if (matchArray == null) {
alert("Formato non valido. Scrivere l'ora nel formato hh:mm:ss");
document.timeform.elements[nome].value = '';
return false;
}
hour = matchArray[1];
minute = matchArray[2];
second = matchArray[4];
ampm = matchArray[6];

if (second=="") { second = null; }
if (ampm=="") { ampm = null }

if (hour < 0  || hour > 23) {
alert("Le ore devono essere tra 1 e 12 o tra 0 e 23");
document.timeform.elements[nome].value = '';
return false;
}

if  (hour > 12 && ampm != null) {
alert("Non inserire AM o PM");
document.timeform.elements[nome].value = '';
return false;
}
if (minute<0 || minute > 59) {
alert ("I minuti devono essere compresi tra 0 e 59.");
document.timeform.elements[nome].value = '';
return false;
}
if (second != null && (second < 0 || second > 59)) {
alert ("I secondi devono essere compresi tra 0 e 59.");
document.timeform.elements[nome].value = '';
return false;
}
return false;
}


var oldLink = null;
function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  link.style.fontWeight = 'bold';
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
    cal.callCloseHandler();
}

function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  var cal = new Calendar(0, null, flatSelected);

  cal.weekNumbers = false;

  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("%A, %B %e");

  cal.create(parent);

  cal.show();
}
</script>


</head>

<BODY bgcolor="#FFFFFF">


<br /><br />
<div align="center">
<b>Rapporto Finale</b><br><br><br>
<form name="timeform" method=post action="report_finale.php" target="_blank">
<table border=1>
<tr>
<td>
<b>Da:</b></td><td> <input type="text" name="date3" id="sel3" size="10"><input type="reset" value="Calendario" onclick="return showCalendar('sel3', '%d-%m-%Y');">
</td>
<br /><br />
</tr>
<tr>
<td>
<b>A:</b></td><td> <input type="text" name="date2" id="sel2" size="10"><input type="reset" value="Calendario" onclick="return showCalendar('sel2', '%d-%m-%Y');">
</td>
<br /><br />
</tr>
</table>
<br><br>
<br>
<input type="submit" value="Produzione Rapporto" name="caricadati">
</form>
<br><br><br>

<form name="impostazioni" method=post action="../index.php"> 
<input type="submit" value="HOME" name="home">
</form>
</div>

</BODY>
</HTML>

