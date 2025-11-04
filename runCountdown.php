#!/usr/bin/php
<?

$pluginName = basename(dirname(__FILE__));

$skipJSsettings = 1;
include_once("/opt/fpp/www/common.php");
include_once("functions.inc.php");
$logFile = $settings['logDirectory']."/".$pluginName.".log";
$pluginConfigFile = $settings['configDirectory'] . "/plugin." .$pluginName;

//get settings and if no user stored setting, use a default value

if (file_exists($pluginConfigFile)){
	$pluginSettings = parse_ini_file($pluginConfigFile);
}else{
	$pluginSettings = array(); //There have been no settings saved by the user, create empty array
}

if (isset($pluginSettings['ENABLED'])){
    $enabled = $pluginSettings['ENABLED'];
	
}else{
    $enabled ="";
	logEntry("Plugin has not been enabled, exiting");
	exit(0);
}

if (isset($pluginSettings['EVENT_NAME'])){
    $eventName = $pluginSettings['EVENT_NAME'];
}else{
	$eventName ="The Event";
	logEntry("Event Name not specifically defined, using default The Event!");
}

if (isset($pluginSettings['MONTH'])){
    $month = $pluginSettings['MONTH'];
}else{
	$month ="1";
	logEntry("Month not specifically defined, using default of January");
}

if (isset($pluginSettings['DAY'])){
    $day = $pluginSettings['DAY'];
}else{
	$day ="1";
	logEntry("Day not specifically defined, using default of 1");
}

if (isset($pluginSettings['YEAR'])){
    $year = $pluginSettings['YEAR'];
}else{
	$year = date("Y")+1;
	logEntry("Year not specifically defined, using default of next year");
}

if (isset($pluginSettings['HOUR'])){
    $hour = $pluginSettings['HOUR'];
}else{
	$hour = "0";
	logEntry("Hour not specifically defined, using default");
}

if (isset($pluginSettings['MIN'])){
    $minute = $pluginSettings['MIN'];
}else{
	$minute = "0";
	logEntry("Minutes not specifically defined, using default");
}

if (isset($pluginSettings['PRE_TEXT'])){
    $preText = $pluginSettings['PRE_TEXT'];
}else{
	$preText = "It is";
	logEntry("Pre Text not specifically defined, using default");
}

if (isset($pluginSettings['POST_TEXT'])){
    $postText = $pluginSettings['POST_TEXT'];
}else{
	$postText = "until";
	logEntry("Post Text not specifically defined, using default");
}

if (isset($pluginSettings['COUNTUP_PRE_TEXT'])){
    $countUpPreText = $pluginSettings['COUNTUP_PRE_TEXT'];
}else{
	$countUpPreText = "It has been";
	logEntry("Count up Pre Text not specifically defined, using default");
}

if (isset($pluginSettings['COUNTUP_POST_TEXT'])){
    $countUpPostText = $pluginSettings['COUNTUP_POST_TEXT'];
}else{
	$countUpPostText = "since";
	logEntry("Count up Post Text not specifically defined, using default");
}

if (isset($pluginSettings['COMPLETED_MESSAGE'])){
    $completedText = $pluginSettings['COMPLETED_MESSAGE'];
}else{
	$completedText = "It is ".$eventName;
	logEntry("Completed Text not specifically defined, using default");
}

if (isset($pluginSettings['COUNT_UP'])){
    $countup = $pluginSettings['COUNT_UP'];
}else{
	$countup  = "";
	logEntry("Count up not specifically defined, using default of off");
}

if (isset($pluginSettings['INCLUDE_HOURS'])){
    $includeHours = $pluginSettings['INCLUDE_HOURS'];
}else{
	$includeHours = "";
	logEntry("Include hours not specifically defined, using default");
}

if (isset($pluginSettings['INCLUDE_MINUTES'])){
    $includeMinutes = $pluginSettings['INCLUDE_MINUTES'];
}else{
	$includeMinutes = "";
	logEntry("Include minutes not specifically defined, using default");
}

if (isset($pluginSettings['HOST_LOCATION']))
    $hostLocation = $pluginSettings['HOST_LOCATION'];

if (isset($pluginSettings['OVERLAY_MODEL'])){
    $overlayModel = urldecode($pluginSettings['OVERLAY_MODEL']);
}else{
    $overlayModel = reset(GetOverlayList());
	if ($overlayModel ==""){
		logEntry("****ERROR**** Overlay Model not specifically defined and there are no Pixel Overlay Models defined");
	}
    logEntry("Overlay Model not specifically defined, using default Overlay instead");
}


if (isset($pluginSettings['OVERLAY_MODE'])){
    $overlayMode = urldecode($pluginSettings['OVERLAY_MODE']);
	switch ($overlayMode){
		case "1":
			$auto="Enabled";
			break;
		case "2":
			$auto="Transparent";
			break;
		case "3":
			$auto="TransparentRGB";
			break;
		default:
			$auto="Enabled";
	}	
}else{
    $auto="Enabled";
	logEntry("Overlay Mode not specifically defined, using default Full Overlay instead");
}

if (isset($pluginSettings['FONT'])){
    $font = urldecode($pluginSettings['FONT']);
}else{
    $font = reset(getFontsInstalled());
    logEntry("Font not specifically defined, using default of first font found");
}

if (isset($pluginSettings['FONT_SIZE'])){
    $fontSize = urldecode($pluginSettings['FONT_SIZE']);
}else{
    $fontSize = "20";
    logEntry("Font Size not specifically defined, using default size of 20 instead");
}

if (isset($pluginSettings['FONT_ANTIALIAS'])){
    $fontAntialias = urldecode($pluginSettings['FONT_ANTIALIAS']);
}else{
    $fontAntialias = "";
    logEntry("AntiAlias not specifically defined, using default no AntiAlias instead");
}

if (isset($pluginSettings['SCROLL_SPEED'])){
    $scrollSpeed = urldecode($pluginSettings['SCROLL_SPEED']);
}else{
    $scrollSpeed = "20";
    logEntry("Scroll Speed not specifically defined, using default of 20 instead");
}

$Position="R2L";
if ($scrollSpeed=="0"){
	$Position="Center";
}

if (isset($pluginSettings['DURATION'])){
    $duration = urldecode($pluginSettings['DURATION']);
}else{
    $duration = "20";
    logEntry("Duration not specifically defined, using default of 20 instead");
}

if (isset($pluginSettings['COLOR'])){
    $color = urldecode($pluginSettings['COLOR']);
}else{
    $color = "#ffffff";
    logEntry("Color not specifically defined, using default of White instead");
}

$strEventDate = $year."-".$month."-".$day." ".$hour.":".$minute.":00";

logEntry( "event date: ".$strEventDate);


$date1 = strtotime($strEventDate);

$date2 = time();
$subTime = $date1 - $date2;
$elapsed=false;

if ($subTime<0){
	$elapsed=true;
}

$y = abs($subTime/(60*60*24*365));
$d = abs(($subTime/(60*60*24))%365);
$h = abs(($subTime/(60*60))%24);
$m = abs(($subTime/60)%60 +1);
if ($elapsed){
	$messagePreText = $countUpPreText;
	$messagePostText = $countUpPostText;
	$m +=1;	
}else{
	$messagePreText = $preText;
	$messagePostText = $postText;	
}

logEntry( "Difference between ".date('Y-m-d H:i:s',$date1)." and ".date('Y-m-d H:i:s',$date2)." is:".$y." years ".$d." days ".$h." hours ".$m." minutes");

$messageText = $messagePreText;
if ($y >= 1){
	if ($y >=2){
		$messageText .= intval($y). " years ";
	} else {
		$messageText .= intval($y). " year ";
	}
} else {
	$messageText .= " ";
}

if ($d >= 1){
	if ($d >=2){
		$messageText .= intval($d). " days ";
	} else {
		$messageText .= intval($d). " day ";
	}
	if($includeHours == "ON"){
		if ($h >=2) {
			$messageText .= intval($h). " hours ";
		} else {
			if ($h >= 1) {
				$messageText .= intval($h). " hour ";
			}
		}
	}
	if($includeMinutes == "ON"){
		if($includeHours == "OFF"){
			$m += $h *60;
		}
		if ($m >=2) {
			$messageText .= intval($m). " minutes ";
		} else {
			$messageText .= intval($m). " minute ";
		}		
	}
}else {
	if ($h >=2) {
			$messageText .= intval($h). " hours ";
		} else {
			if ($h >= 1) {
				$messageText .= intval($h). " hour ";
			}
		}
	if ($m >=2) {
			$messageText .= intval($m). " minutes ";
		} else {
			$messageText .= intval($m). " minute ";
		}	
	
} 
if ($elapsed && $countup!="ON"){
		$messageText= $completedText;
	}else{
		$messageText .= " ".$messagePostText. " ".$eventName;
	}

$messageText = preg_replace('!\s+!', ' ', $messageText);

logEntry("messageText= ".$messageText);
//error_log("RunEventDate.php- messageText= ".$messageText);
logEntry("ScrollText options-hostLocation=  ".$hostLocation. " overlayModel= ".$overlayModel. " Position= " .$Position. " Font = " .$font. " fontsize= " .$fontSize. " fontColor= " .$color. " scrollSpeed= " .$scrollSpeed. " Auto= " .$auto." duration= " .$duration);

ScrollText($hostLocation, $overlayModel, $messageText, $Position, $font, $fontSize, $color, $scrollSpeed, $fontAntialias, $duration, $auto);

	exit(0); //is this needed?
	
?>
