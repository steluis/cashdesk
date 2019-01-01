<?
/*******************************************************************************
* CASH DESK - SHOW HD OR SD CARD FREE SPACE                                    *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Stefano Luise                                                       *
*******************************************************************************/
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="generator" content="Dev-PHP 3.01">

<style type="text/css">
body{font:12px Arial,sans-serif;background:#ffffff}
h3{font-size:1.4em;color: #3980F4}

dl.stat{float:left;width:260px}

dl.stat dt{float:left;width:150px;
    height:18px;line-height:18px;
    margin: 2px 0;padding:0;text-align:right}

dl.stat dd{float:right;
    width:100px !important; width /**/:104px;
    height:16px;line-height:16px;
    padding:1px;border:1px solid #CCC;margin:1px 0;
    text-align:center}

dl.stat dd span{display:block;width:100px;
    background:#ECECEC url(progressBk.png) no-repeat 0 0;
    color:#002F7E}
</style>



</head>
<body bgcolor="#000000">
<?
$diskTotal = disk_total_space("/");
$dikFree = disk_free_space("/");
$diskTotal = $diskTotal/1000000000;		
$dikFree = $dikFree/1000000000;				

$percent_Free = $dikFree/$diskTotal;
$percent_Free = round($percent_Free*100,2);
$pox_Free = round(100-$percent_Free, 2);
?>
<dl class="stat">
<dt>Risorse disponibili:</dt>
<dd><span style="background-position:-<?echo $pox_Free; ?>px 0"><?echo $percent_Free;?>%</span></dd>
</dl>
</body>
</html>
