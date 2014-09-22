<?php
if (!file_exists('config.php')) {
  die("Config file not found! Read the readme file to create your configuration file first");
}
require_once 'config.php';
require_once 'class.db.php';

$db = new db("mysql:host=" . SERVER . ";dbname=" . DATABASE, USER, PASSWORD);
if ($db) {
  $db->setErrorCallbackFunction("kill");
} else {
  die("Could not connect to the database");
}

$css = filesize("css/styles.css");

$header = '
    <meta charset="utf-8" />
    <base href="' . URL . '" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="img/favicon.ico" />

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.4.2.0.css" rel="stylesheet" />
    <link href="css/styles.'.$css.'.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->';

$header .= PRODUCTION ? 
  '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>' :
  '<script src="js/jq.1.11.1.js"></script>';

$header .= '<script src="js/bootstrap.min.js"></script>';

$tag_parents = $db->query("SELECT SQL_CACHE id, name FROM tags_parents ORDER BY parent_order");

$menu = array(
  2 => array(
    'label' => 'About', 
    'url' => 'about.php', 
    'menu' => array(
      1 => array('label' => 'About MFA Tools', 'url' => 'page/about'),
      4 => array('label' => 'Wish List', 'url' => 'page/wishlist'),
      3 => array('label' => 'Team', 'url' => 'page/team'),
      5 => array('label' => 'Contact Us', 'url' => 'page/contact'),
      6 => array('label' => 'Mailing List', 'url' => 'page/mailinglist'),
      7 => array('label' => 'Version History', 'url' => 'page/version'),
    ),
  ),
  3 => array(
    'label' => 'Current Research', 
    'url' => 'mfa.php',
    'menu' => array(
      1 => array('label' => 'List of Projects', 'url' => 'research/list'),
      2 => array('label' => 'Add a Project', 'url' => 'research/add'),
    ),
  ),
  6 => array(
    'label' => 'OMAT', 
    'url' => 'mfa.php',
    'menu' => array(
      1 => array('label' => 'How it Works', 'url' => 'omat/about'),
      2 => array('label' => 'Create a Project', 'url' => 'omat/add'),
      4 => array('label' => 'Dashboard', 'url' => 'page/login'),
 //     3 => array('label' => 'List', 'url' => 'omat/list'),
    ),
  ),
  5 => array(
    'label' => 'Publication Collections', 
    'url' => 'publications/collections',
  ),
  4 => array(
    'label' => 'Publication Database', 
    'url' => 'publications/list',
    'menu' => array(
      1 => array('label' => 'Full list', 'url' => 'publications/list'),
      2 => array('label' => 'Search', 'url' => 'publications/search'),
      3 => array('label' => 'Add', 'url' => 'publications/add'),
    ),
  ),
);
foreach ($tag_parents as $row) {
  $menu[5]['menu'][$row['id']] = array('label' => $row['name'], 'url' => 'publications/collections/'.$row['id']);
}

if (LOCAL) {
  $menu[5]['menu'][99] = array('label' => 'Regional MFAs', 'url' => 'page/casestudy');
}

function mailadmins($message, $subject, $from = false) {
  $message = utf8_decode($message);
  $from = $from ? $from : "noreply@mfa-tools.net";
  $headers = 'From: MFA Tools<noreply@mfa-tools.net>' . "\r\n" .
      'Reply-To: ' . $from . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
  mail(WEBMASTER_MAIL, $subject, $message, $headers);
  if (LOCAL) {
    echo '<pre>' . $message . '</pre>';
  }
}

function kill($message) {
    if (PRODUCTION) {
      mailadmins($message . "\n\n" . getinfo(), "MySQL Error - MFA Tools");
      header("HTTP/1.0 404 Not Found");
      header("Location: " . URL . "404");
      exit();
    } else {
      echo $message;
      die();
    }
}

function format_date($format,$date){
	
	if($date == "0000-00-00" || $date == "0000-00-00 00:00:00" || $date == "1969-12-31"){
	  return "";  
	}

	$return = date($format,strtotime($date));
	return $return;
}

function seems_utf8($str)
{
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

function remove_accents($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '');

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

function flatten($string) {
	$string = utf8_decode($string);
	$string = remove_accents($string);
	$array_rewrite = array("amp-amp" => "and", "-s-" => "s-", "-amp-oacute-" => "o", "amp-" => "");
	$string = strtolower($string);
	$string = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $string);
	$string = preg_replace("/([^a-z0-9]+)/", "-", $string);
	$string = trim($string, "-");
	$string = strtr($string, $array_rewrite);
	
	return $string;
}

function encrypt($string)
{
	if (!defined("SALT") || SALT == "") {
		kill("SALT not defined", "critical");
		return false;
	} else {
		return hash("sha512", $string.SALT);
	}
}

function br2nl($string){
	return str_replace("<br />","",$string);
}

function html($string,$clean=true) {
  if (!mb_check_encoding($string, "UTF-8")) {
    $string = utf8_decode($string);
  }
  $convert = array('’' => "'", '“' => "'", '”' => "'", '–' => "-");
  $string = strtr($string, $convert);
  $string = nl2br(htmlspecialchars($string, ENT_QUOTES, "UTF-8"));
	return($string);
}

function mysql_clean($string, $type=false) {
  $string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;
	$string = mysqli_real_escape_string(mysqli_connect(SERVER,USER,PASSWORD,DATABASE),$string);
	
	if ($type == "wildcard")
	{
		return addcslashes($string, "%_"); 
	}
	else
	{
		return "{$string}";
	}
}

function mail_clean($string, $type=NULL, $msafe="no") // check body elements of message
{
	$bad_strings = array("content-","mime-","multipart/mixed","bcc:","acc:","@yourdomain.com", ".txt");
	$spam_strings = array("a href", "[url]", "viagra");
	
	foreach($bad_strings as $bad_string)
	{
			if(stristr($string, $bad_string))
					kill ("bsafe injection", "mail injection");
	}
	
	foreach($spam_strings as $bad_string)
	{
			if(stristr($string, $bad_string))
					kill ("This is a spam message", "spam");
	}
	
	if ($type != "box") // if the input is a large box, then new lines are accepted
	{
			if(preg_match("/(%0A|%0D|\n+|\r+)/i", $string))
					kill ("bsafe injection newlines", "mail injection");
	
	}
	
	return $string;
}

function getinfo($option='all'){ 
$remoteinfo="================= DETAILS ================ 
Time => ".date("r")."
IP => ". $_SERVER['REMOTE_ADDR']."
Browser => " . $_SERVER['HTTP_USER_AGENT'] . "
File => " . $_SERVER['PHP_SELF'] . "
Server => " . $_SERVER['SERVER_NAME'] . "
Method => " . $_SERVER['REQUEST_METHOD'] . "
Query => " . $_SERVER['QUERY_STRING'] . "
HTTP Language => " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "
Referer => " . $_SERVER['HTTP_REFERER'] . "
URL => " . $_SERVER['REQUEST_URI']."\n\n";

$post = "============== POST VARIABLES ============ \n".
(($_POST) ? print_r($_POST, true) . "\n\n" : $post="POST IS EMPTY\n\n");

$files = "============== FILES VARIABLES ============ \n".
(($_FILES) ? print_r($_FILES, true) . "\n\n" : $files="FILES IS EMPTY\n\n");

$cookies = "============ COOKIE VARIABLES =========== \n".
(($_COOKIE) ? print_r($_COOKIE, true) . "\n\n": $cookies ="NO COOKIES\n\n");

$sql=(mysql_error()) ? "================ SQL ERROR ================ \n".mysql_error() : '';

$backtrace="================== PHP DEBUG ================ \n".print_r(debug_backtrace(),true); 

$lasterror="============== PHP LAST ERROR ============ \n".print_r(error_get_last(),true);
	
		switch($option){
				case 'url':
						$info = "URL: {$_SERVER['REQUEST_URI']}";
						break;
						
				case 'post':
						$info = $post;
						break;
		
				case 'cookies':
						$info = $cookies;
						break;
						
				case 'remote':
						$info = $remoteinfo;
						break;
						
				case 'php':
						$info = $lasterror.$backtrace;
						break;
						
				case 'sql':
						$info = $sql;
						break;

				case 'files':
						$info = $files;
						break;
						
				case 'all':
				default:
						$info = $url.$post.$remoteinfo.$cookies.$files;
						break;
		}
		return $info;
} 

function formatTime($time)
{
	$hours = floor($time/60);
	if ($hours > 0)
	{
		$minutes = (int)$time-($hours*60);
		if ($minutes < 10) { $minutes = "0$minutes"; }
		$return = "$hours:$minutes";
		$return .= " h";
	}
	elseif ($time == 0) { $return = "0:00"; } 
	elseif ($time < 10) { $return = (int)$time . " min"; }
	else { $return = $time . " min"; }
	return $return;
}

function truncate($string,$length=100,$append="&hellip;") {
  $string = trim($string);

  if(strlen($string) > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }

  return $string;
}

$version = '1.0';

?>
