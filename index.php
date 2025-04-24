<?php
// This block MUST be at the very top of the page!
@ob_start('ob_gzhandler');
if(isset($_GET['icon']))
{
	$e=$_GET['icon'];
	
header('Cache-control: max-age=2592000');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T',time()+2592000));
header('Content-type: image/gif');
print base64_decode(isset($I[$e]) ? $I[$e] : $I['file']);
exit;
}

// Set website name here
$websitename = 'IP Stats Analyzer';

// Log IP & count visitors
$host = $_SERVER["HTTP_HOST"];
$userip = $_SERVER["REMOTE_ADDR"];
$vis_ip = $_SERVER["REMOTE_ADDR"];
$user_agent = $_SERVER["HTTP_USER_AGENT"];

// IP-adres van de bezoeker ophalen
$ip_address = $_SERVER['REMOTE_ADDR'];

if (file_exists('count_file.txt')) {
    $fil = fopen('count_file.txt', "r");
    $dat = fread($fil, filesize('count_file.txt'));
    //echo "Visits: ", $dat+1, "<br></br> ";
    fclose($fil);
    $fil = fopen('count_file.txt', "w");
    fwrite($fil, $dat + 1);
} else {
    $fil = fopen('count_file.txt', w);
    fwrite($fil, 1);
    echo '1';
    fclose($fil);
}
//$fp = fopen('ip.txt', 'a') or die("Unable to open file!");
//echo "<br>";
//fwrite($fp, $userip . '-' . date("d/m/Y") . ',');


$fp = fopen('ip.txt', 'a') or die("Unable to open file!");

// Extra gegevens ophalen en toevoegen
$log_data = sprintf(
    "IP: %s | Date: %s | Continent: %s | Continent code: %s | Country: %s | Country code: %s | Region: %s | Regio Name: %s | City: %s | Valuta: %s | ISP: %s\n",
    $userip,
    date("d/m/Y H:i:s"),
    "Europe",         // Continent (statisch of API-output)
    "EU",             // Continentcode (statisch of API-output)
    "The Netherlands", // Land (statisch of API-output)
    "NL",             // Landcode (statisch of API-output)
    "NH",             // Regio (statisch of API-output)
    "North Holland",  // Regio Naam (statisch of API-output)
    "Amsterdam",      // Stad (statisch of API-output)
    "EUR",            // Valuta (statisch of API-output)
    "Internet Utilities NA LLC" // ISP (statisch of API-output)
);

// Schrijf de gegevens naar ip.txt
fwrite($fp, $log_data);

// Sluit het bestand
fclose($fp);


// Start configs
$self = basename(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);
$sitename = '<a href=https://' . $host . ' >' . print $websitename; ?> . '<a>';
//$date='M-d-y'; // date format
$date = 'j-n-Y h:i';
//date("Y-m-d H:i:s");
$ignore = array('.','..','.htaccess','index.php','documentation.txt','icon.php','Thumbs.db','graph.js','cgi-bin',$self,'count_file.txt','ip.txt','logo.png','stats.php','Chart.js','data.php'); // ignore these files
// End configs
$root = dirname(__FILE__);
$dir = isset($_GET['dir']) ? $_GET['dir'] : '';
if (strstr($dir, '..')) $dir = '';
$path = "$root/$dir/";
$dirs = $files = array();
if (!is_dir($path) || false == ($h = opendir($path))) exit('Directory does not exist.');
while (false !== ($f = readdir($h))) {
    if (in_array($f, $ignore)) continue;
    if (is_dir($path . $f)) $dirs[] = array('name' => $f, 'date' => filemtime($path . $f), 'url' => $self . '?dir=' . rawurlencode(trim("$dir/$f", '/')));
    else $files[] = array('name' => $f, 'size' => filesize($path . $f), 'date' => filemtime($path . $f), 'url' => trim("$dir/" . rawurlencode($f), '/'));
}
closedir($h);
$current_dir_name = basename($dir);
$up_dir = dirname($dir);
$up_url = ($up_dir != '' && $up_dir != '.') ? $self . '?dir=' . rawurlencode($up_dir) : $self;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
<title><?php print $host; ?></title>



<style type="text/css">
    @media (min-width: 1081px) {
        body {
            font-size: 1em;
        }
    }

    @media (max-width: 1080px) {
        body {
            font-size: 1.3em;
        }
    }

    #topheader {
        background-color: #191919;
        color: #fff;
        height: 4.2em;
        margin-top: -1.5em;
        margin-left: -0.5em;
        margin-right: -0.5em;
        margin-bottom: 1.25em;
    }

    #topheader h2 {
        padding-top: 0.7em;
        text-align: center;
    }

    #topheader h2 a {
        color: #fff;
        font-weight: 400;
        text-decoration: none;
        vertical-align: middle;
    }

    h2 {
        margin: 0em;
    }

    body {
        font-family: sans-serif;
        color: black;
        padding-top: 1em;
        cursor: default;
        background-color: #114a83;
    }

    #idx #idx {
        padding-left: 0.5em;
        margin-left: 1em;
    }

    #idx {
        border: 3px solid #fff;
        width: 720px;
        margin: 1em auto 1em auto;
        border-radius: 0.5em;
    }

    #idx td.center {
        text-align: center;
    }

    #idx .rounded.gray {
        height: 3em;
    }

    #idx img {
        margin-right: 0.3em;
        margin-bottom: -0.25em;
        height: 1.1em;
    }

    #idx tr {
        line-height: 1.5em;
    }

    #idx tr td img {
        padding-left: 0.75em;
    }

    #idx tr:hover,
    #firstline:hover {
        background-color: #e9e9e9!important;
    }

    #idx tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    #idx tr:nth-child(odd) {
        background-color: #fff;
    }

    #idx table {
        color: #606060;
        width: 100%;
    }

    #idx span.link {
        color: #0066DF;
        cursor: pointer;
    }

    #idx .rounded {
        padding: 1em 0.7em 1em 1em;
        -moz-border-radius: 6px;
    }

    #idx .gray {
        background-color: #fafafa;
        border-bottom: 1px solid #e5e5e5;
    }

    #idx p {
        padding: 0px;
        margin: 0px;
        line-height: 1.4em;
        background-color: #fff;
    }

    #idx p.location {
        text-align: left;
        width: 100%;
        margin-left: 0.5em;
        margin-top: 1em;
        margin-bottom: 1em;
        color: #707070;
    }

    #idx p.left {
        float: left;
        width: 40%;
        color: #606060;
        margin-top: 0.8em;
        margin-left: 0.5em;
    }

    #idx p.right {
        float: right;
        color: #707070;
        line-height: 1em;
        margin-top: 1em;
    }

    #idx strong {
        font-family: "Trebuchet MS", tahoma, arial;
        font-size: 1.2em;
        font-weight: bold;
        color: #202020;
        padding-bottom: 0.3em;
        margin: 0em;
    }

    #idx a:link {
        color: #0e0e0e;
        text-decoration: none;
    }

    #idx a:visited {
        color: #003366;
    }

    #idx a:hover {
        text-decoration: none;
    }

    #idx a:active {
        color: #9DCC00;
    }
</style>

 <link rel="icon" type="image/png" href="logo.png"> <!-- Verwijst naar je logo -->


<div id="topheader">
    <h2>
        <a class="ptitle" style="text-transform:uppercase;font-family:system-ui;font-weight:700;" href="/">
            <?php print $websitename; ?>
        </a>
    </h2>
</div>
<script type="text/javascript">
<!--
var _c1='';
var _c2='';
var _ppg=500;
var _cpg=1;
var _files=[];
var _dirs=[];
var _tpg=null;
var _tsize=0;
var _sort='date';
var _sdir={'type':0,'name':0,'size':0,'date':1};
var idx=null;
var tbl=null;

function _obj(s) {
    return document.getElementById(s);
}

function _ge(n) {
    n = n.substr(n.lastIndexOf('.') + 1);
    return n.toLowerCase();
}

function _nf(n, p) {
    if (p >= 0) {
        var t = Math.pow(10, p);
        return Math.round(n * t) / t;
    }
}

function _s(v, u) {
    if (!u) u = 'B';
    if (v > 1024 && u == 'B') return _s(v / 1024, 'KB');
    if (v > 1024 && u == 'KB') return _s(v / 1024, 'MB');
    if (v > 1024 && u == 'MB') return _s(v / 1024, 'GB');
    return _nf(v, 1) + '&nbsp;' + u;
}

function _f(name, size, date, url, rdate) {
    _files[_files.length] = {
        'dir': 0,
        'name': name,
        'size': size,
        'date': date,
        'type': _ge(name),
        'url': url,
        'rdate': rdate,
        'icon': '<?php print $self ?>?icon=' + _ge(name)
    };
    _tsize += size;
}

function _d(name, date, url) {
    _dirs[_dirs.length] = {
        'dir': 1,
        'name': name,
        'date': date,
        'url': url,
        'icon': '<?php print $self ?>?icon=dir'
    };
}

function _np() {
    _cpg++;
    _tbl();
}

function _pp() {
    _cpg--;
    _tbl();
}

function _sb(l, r) {
    return (l['type'] == r['type']) ? 0 : (l['type'] > r['type'] ? 1 : -1);
}

function _sa(l, r) {
    return (l['size'] == r['size']) ? 0 : (l['size'] > r['size'] ? 1 : -1);
}

function _sc(l, r) {
    return (l['rdate'] == r['rdate']) ? 0 : (l['rdate'] > r['rdate'] ? 1 : -1);
}

function _sd(l, r) {
    var a = l['name'].toLowerCase();
    var b = r['name'].toLowerCase();
    return (a == b) ? 0 : (a > b ? 1 : -1);
}

function _srt(c) {
    switch (c) {
        case 'type':
            _sort = 'type';
            _files.sort(_sb);
            if (_sdir['type']) _files.reverse();
            break;
        case 'name':
            _sort = 'name';
            _files.sort(_sd);
            if (_sdir['name']) _files.reverse();
            break;
        case 'size':
            _sort = 'size';
            _files.sort(_sa);
            if (_sdir['size']) _files.reverse();
            break;
        case 'date':
            _sort = 'date';
            _files.sort(_sc);
            if (_sdir['date']) _files.reverse();
            break;
    }
    _sdir[c] = !_sdir[c];
    _obj('sort_type').style.fontStyle = (c === 'type' ? 'normal' : 'normal');
    _obj('sort_name').style.fontStyle = (c === 'name' ? 'normal' : 'normal');
    _obj('sort_size').style.fontStyle = (c === 'size' ? 'normal' : 'normal');
    _obj('sort_date').style.fontStyle = (c === 'date' ? 'normal' : 'normal');
    _tbl();
    return false;
}

function _head()
{
	if(!idx)return;
	_tpg=Math.ceil((_files.length+_dirs.length)/_ppg);
	idx.innerHTML='<div class="rounded gray" style="padding:0.5em 1em 0.5em 0.7em;color:#202020">' +
		'<p style="display:none;margin-top:0.5em;margin-left:0.5em; font-weight:700;color:#2e2e2e;">Current directory: <?php print$current_dir_name==''?'root':$current_dir_name?>' +
		'<span style="margin-left: 2em; text-align:right; margin-right:0;"><?php print$dir!=''?'&nbsp;':''?></span></p>' + 
		'<p class="left">' +
			'<span style="font-weight:700;" >Sort: </span><span class="link" onmousedown="return _srt(\'type\');" id="sort_type">Type</span>, <span class="link" onmousedown="return _srt(\'name\');" id="sort_name">Name</span>, <span class="link" onmousedown="return _srt(\'size\');" id="sort_size">Size</span>, <span class="link" onmousedown="return _srt(\'date\');" id="sort_date">Date</span>' +
		'</p>' +
		'<p class="right">' +
			(_files.length+_dirs.length) + ' objects in this folder, ' + _s(_tsize) + ' total.' +
		'</p>' +
		'<div style="clear:both;"></div>' +
	'</div><div id="idx_tbl"></div>';
	tbl=_obj('idx_tbl');
}

function _tbl()
{
	var _cnt=_dirs.concat(_files);if(!tbl)return;if(_cpg>_tpg){_cpg=_tpg;return;}else if(_cpg<1){_cpg=1;return;}var a=(_cpg-1)*_ppg;var b=_cpg*_ppg;var j=0;var html='';
	if(_tpg>1)html+='<p style="padding:0.5em 0.5em 0em 0.7em;color:#202020;text-align:right;"><span class="link" onmousedown="_pp();return false;">Previous</span> ('+_cpg+'/'+_tpg+') <span class="link" onmousedown="_np();return false;">Next</span></p>';
	html+='<table cellspacing="0" cellpadding="5" border="0">'
	html+='<div id="firstline" style="width:100%;height:1.8em;background-color:#f1f1f1;"><a style="vertical-align:sub;color:#0066DF;padding-left:3em;letter-spacing: 0.1em;color: #0e0e0e;" href="<?php print$up_url; ?>">&#171; &#171; &#171; </a></div>';
	for(var i=a;i<b&&i<(_files.length+_dirs.length);++i)
	{
		var f=_cnt[i];var rc=j++&1?_c1:_c2;
		html+='<tr style="background-color:'+rc+'"><td><img src="'+f['icon']+'" alt="" /> &nbsp;<a href="'+f['url']+'">'+f['name']+'</a></td><td class="center" style="width:5em;text-align:right;">'+(f['dir']?'':_s(f['size']))+'</td><td class="center" style="width:9em;text-align:right;padding-right:0.75em;">'+f['date']+'</td></tr>';
	}
	html+='<tr><td style="color:#fff0;">1<td><td></td></tr>';
	tbl.innerHTML=html+'</table>';
}
<?php foreach($dirs as $d) { print sprintf("_d('%s','%s','%s');\n",addslashes($d['name']),date($date,$d['date']),addslashes($d['url'])); } ?>
<?php foreach($files as $f) { print sprintf("_f('%s',%d,'%s','%s',%d);\n",addslashes($f['name']),$f['size'],date($date,$f['date']),addslashes($f['url']),$f['date']); } ?>

window.onload=function()
{
	idx=_obj('idx'); _head(); _srt('name');
};
-->
</script>
</head>
<body>
	<?php file_put_contents('ip.txt', $ip_address . PHP_EOL, FILE_APPEND); ?>
	<div id="idx"><!-- do not remove --></div>
	<div style="display:inline-block;text-align:center;width:100%;color:#dbdbdb;margin-top:0.5em;">
    		<?php echo "Visits: ", $dat+1, " , "; ?>
    	<a href='https://<?php print $host; ?>' style='color:#dbdbdb;text-decoration:none;'>  <?php print $host; ?> &copy; <?php echo date("Y"); ?></a><br></br>
</div>
</body>
</html>

// <div style="display:inline-block;textt-align-left;height:0px!important;display:none!important;"><?php echo file_get_contents( "count_file.txt" ); ?> </div>