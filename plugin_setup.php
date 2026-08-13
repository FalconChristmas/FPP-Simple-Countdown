<?php


include_once "/opt/fpp/www/common.php";
include_once 'functions.inc.php';
include_once 'version.inc';

$pluginName = basename(dirname(__FILE__));


$logFile = $settings['logDirectory']."/".$pluginName.".log";

$showScrollDiv="display:none";
$showScrollOptionsDiv="display:none";
if (isset($pluginSettings['SCROLL_SPEED'])){
	$scrollSpeed= $pluginSettings['SCROLL_SPEED'];
	if ($scrollSpeed==0){
		$showScrollDiv	="display:block";
	}else{
		$showScrollDiv ="display:none";
		$showScrollOptionsDiv ="display:block";
	}
	
}

$showCountUpDiv="display:none";
$showCompleteDiv= "display:block";
if (isset($pluginSettings['COUNT_UP'])){
	$countUp= $pluginSettings['COUNT_UP'];
	if ($countUp=="ON"){
		$showCountUpDiv	="display:block";
		$showCompleteDiv= "display:none";
	}else{
		$showCountUpDiv ="display:none";
	}
	
}

$gitURL = "https://github.com/FalconChristmas/FPP-Simple-Countdown.git";

$overlayModelData = array();
$modelsList = GetModels("");
if (is_array($modelsList)) {
	foreach ($modelsList as $m) {
		$name = "";
		$wd = 0;
		$ht = 0;
		if (is_array($m)) {
			if (isset($m["Name"])) $name = trim($m["Name"]);
			if (isset($m["Width"])) $wd = intval($m["Width"]);
			elseif (isset($m["width"])) $wd = intval($m["width"]);
			if (isset($m["Height"])) $ht = intval($m["Height"]);
			elseif (isset($m["height"])) $ht = intval($m["height"]);
		}
		if ($name != "" && $wd > 0 && $ht > 0) {
			$overlayModelData[$name] = array("w" => $wd, "h" => $ht);
		}
	}
}

?>

<html>
<head>
<style>

* {
  box-sizing: border-box;
}

.subheader {
  background-color: #f1f1f1;
  padding: 20px;
  text-align: center;
}
/* FPP10 dark mode only — base/light (and FPP9) rendering left unchanged. */
[data-bs-theme="dark"] .subheader { background-color: var(--bs-secondary-bg); }
[data-bs-theme="dark"] #currentColor { border-color: var(--bs-border-color); }
[data-bs-theme="dark"] #scroll-container { border-color: var(--bs-border-color); }

.col-1 {width: 8.33%;}
.col-2 {width: 16.66%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%;}
.col-5 {width: 41.66%;}
.col-6 {width: 50%;}
.col-7 {width: 58.33%;}
.col-8 {width: 66.66%;}
.col-9 {width: 75%;}
.col-10 {width: 83.33%;}
.col-11 {width: 91.66%;}
.col-12 {width: 100%;}

[class*="col-"] {
  float: left;
  padding: 15px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}

.subheader ~ .row select {
  display: inline-block;
  width: auto;
  float: none;
  vertical-align: middle;
}

@media screen and (max-width: 1000px) {
  div.graphic {
    display: none;
  }
}

.matrix-tool-bottom-panel {
	padding-top: 0px !important;
}

.red {
	background: #ff0000;
}

.green {
	background: #00ff00;
}

.blue {
	background: #0000ff;
}

.white {
	background: #ffffff;
}

.black {
	background: #000000;
}

.colorButton {
	-moz-transition: border-color 250ms ease-in-out 0s;
	transition: border-color 250ms ease-in-out 0s;
	background-clip: padding-box;
	border: 2px solid rgba(0, 0, 0, 0.25);
	border-radius: 50% 50% 50% 50%;
	cursor: pointer;
	display: inline-block;
	height: 20px;
	margin: 1px 2px;
	width: 20px;
}

#currentColor {
    border: 2px solid #000000;
}
#scroll-container {
	width: 100%;
	max-width: 1000px;
  	border: 3px solid black;
  	border-radius: 5px;
  	overflow: hidden;
}

#scroll-text {
	font-weight: bold; 
	font-size: 30px;
	position: relative;
	transform: none;
	-moz-transform: none;
	-webkit-transform: none;
	text-align: center;
}

/* for Firefox */
@-moz-keyframes my-animation {
  from { -moz-transform: translateX(100%); }
  to { -moz-transform: translateX(-100%); }
}

/* for Chrome */
@-webkit-keyframes my-animation {
  from { -webkit-transform: translateX(100%); }
  to { -webkit-transform: translateX(-100%); }
}

@keyframes my-animation {
  from {
    -moz-transform: translateX(100%);
    -webkit-transform: translateX(100%);
    transform: translateX(100%);
  }
  to {
    -moz-transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
  }
}

/* for Firefox */
@-moz-keyframes my-animation-l2r {
  from { -moz-transform: translateX(-100%); }
  to { -moz-transform: translateX(100%); }
}

/* for Chrome */
@-webkit-keyframes my-animation-l2r {
  from { -webkit-transform: translateX(-100%); }
  to { -webkit-transform: translateX(100%); }
}

@keyframes my-animation-l2r {
  from {
    -moz-transform: translateX(-100%);
    -webkit-transform: translateX(-100%);
    transform: translateX(-100%);
  }
  to {
    -moz-transform: translateX(100%);
    -webkit-transform: translateX(100%);
    transform: translateX(100%);
  }
}

@keyframes my-animation-b2t {
  from { top: 100%; }
  to { top: -100%; }
}

@keyframes my-animation-t2b {
  from { top: -100%; }
  to { top: 100%; }
}

#scroll-text.center {
  animation: none;
  -moz-animation: none;
  -webkit-animation: none;
  transform: none;
  -moz-transform: none;
  -webkit-transform: none;
  text-align: center;
}

.modal-header .close {
	background: none;
	border: 0;
	cursor: pointer;
	float: right;
	font-size: 21px;
	font-weight: 700;
	line-height: 1;
	opacity: 0.5;
}

#previewMatrixContainer {
	display: flex;
	justify-content: center;
	max-width: 100%;
	overflow: hidden;
}

#previewMatrixCanvas {
	background: #000;
	display: block;
}

#previewMatrixInfo {
	font-size: 13px;
	margin-bottom: 8px;
}

#previewMessageText {
	font-size: 13px;
	margin-top: 8px;
	word-break: break-all;
}
</style>
</head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="subheader">
	<h1><?php echo $pluginName . " Version: ". $pluginVersion;?> Installation Instructions</h1>
</div>
<div class="row">
	<div class="col-7">	
	<p><b>This plugin requires ACCURATE date and time for its calculation.</b></p>
		<h4>Configuration:</h4>
		<ul>
			<li>Configure the date and time of your event</li>
			<li>Enter in the Pre Text and Post Text that will appear in your countdown</li>
			<li>Enter the name of your Target date</li>
			<li>Make sure you have your Pixel Overlay Model Selected (usually your Matrix)</li>
			<li>The Countdown will display immediatly when activated by an FPP Command or Command Preset</li>
			<li>If the remaining time is less than a day, the plugin will automatically display the hours and minutes remaining.</li>
			<li>You can configure the plugin to display a message once the target date/time has been reached or</li>
			<li>Have the plugin start counting up from the target date/time.
		</ul>
		<h4>Operation:</h4>
		<ul>
			<li>The Simple Countdown is triggered by an FPP Command (Run Simple Countdown)</li>
			<li>The Countdown will display one time per FPP Command</li>
			<li>If you want a repeating Countdown, you can create a repeating schedule</li>
			<li>Just make sure that you put a pause in your playlist</li>
			<li>Refer to the FPP Manual for more information</li>
			<li><a href="https://falconchristmas.github.io/FPP_Manual.pdf" target="_blank">FPP Manual</a></li>
		</ul>
	</div>
	<div class="col-5 graphic">
		<img src="images/plugin/FPP-Simple-Countdown/countdownRGB.gif" alt="animated countdown">
	</div>			
</div>			
<div class="row">
	<div class="col-12">
		<p>ENABLE PLUGIN: <?PrintSettingCheckbox("Event Date Plugin", "ENABLED", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "", $changedFunction=""); ?> </p>
		<p>Target Date: <? PrintSettingSelect("MONTH", "MONTH", 0, 0, $defaultValue= "1", getMonths(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?>
		<? PrintSettingSelect("DAY", "DAY", 0, 0, $defaultValue= "1", getDaysOfMonth(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?>
		<? PrintSettingSelect("YEAR", "YEAR", 0, 0, $defaultValue= date("Y")+1, getYears(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?>
		Hour: <? PrintSettingSelect("HOUR", "HOUR", 0, 0, $defaultValue= "0", getHours(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?>
		Min: <? PrintSettingSelect("MIN", "MIN", 0, 0, $defaultValue= "0", getMinutes(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?></p>
		<p>Pre Text: <?  PrintSettingTextSaved("PRE_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "It is", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbspxx days xx hours</p>
		<p>Post Text <?  PrintSettingTextSaved("POST_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "until", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>Target Title: <?  PrintSettingTextSaved("EVENT_NAME", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "The Event!", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
	
		<div id ="showCompleted" style= "<? echo $showCompleteDiv; ?>">
			<p>Countdown Completed Text: <?  PrintSettingTextSaved("COMPLETED_MESSAGE", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "Countdown Completed!", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		</div>
		<div id = "showCountUp" style= "<? echo $showCountUpDiv; ?>">
			<p>Count Up Pre Text: <?  PrintSettingTextSaved("COUNTUP_PRE_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "It has been", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>Count Up Post Text: <?  PrintSettingTextSaved("COUNTUP_POST_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "since", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		</div>
		
		<p>Count up: <?PrintSettingCheckbox("COUNT_UP", "COUNT_UP", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "ShowCountUp", $changedFunction = ""); ?> 
		&nbsp With this set, when the target date/time is reached, the counter will count up using the Count Up text. If not, it will use the Completed Text.</p>
		<p><h3>If the remaining time is more than a day then you can select to include the hours and/or minutes.</br>
		</h3></p>
		<p>Include Hours: <?PrintSettingCheckbox("INCLUDE_HOURS", "INCLUDE_HOURS", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputTextHours", $changedFunction = ""); ?> </p>
<p>Include Minutes: <?PrintSettingCheckbox("INCLUDE_MINUTES", "INCLUDE_MINUTES", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputTextHours", $changedFunction = ""); ?> </p>
<div id="showSeconds" style= "<? echo $showScrollDiv; ?>"><p>Include Seconds: <?PrintSettingCheckbox("INCLUDE_SECONDS", "INCLUDE_SECONDS", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputTextSeconds", $changedFunction = ""); ?> <span id="INCLUDE_SECONDS_tip" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="auto" data-bs-title="Seconds are only displayed when there is less than an hour remaining. In count up mode, seconds are only displayed until an hour has elapsed."><img id="INCLUDE_SECONDS_img" src="images/redesign/help-icon.svg" class="icon-help" alt="INCLUDE_SECONDS help icon"></span></p></div>
<p>Your message will appear as:</p>
		<div id="scroll-container" >
			<div id="scroll-text">Countdown </div>
		</div>
		
		<br /><div>Font: <? PrintSettingSelect("fontSelect", "FONT", 0, 0, $defaultValue="", getFontsInstalled(), $pluginName, $callbackName = "updateFont", $changedFunction = ""); ?>
		Font Size: <? PrintSettingSelect("FONT_SIZE", "FONT_SIZE", 0, 0, $defaultValue="20", getFontSizes(), $pluginName, $callbackName = "updateOutputText", $changedFunction = ""); ?>
		Anti-Aliased: <?PrintSettingCheckbox("FONT_ANTIALIAS", "FONT_ANTIALIAS", 0, 0, "1", "", $pluginName , ""); ?></div> 
		
		<div id= "divCanvas" class='ui-tabs-panel matrix-tool-bottom-panel'>
			<table border=0>
				<tr><td valign='top'>
				<div>
					<table border=0>
						<tr><td valign='top'>Pallette:</td>
							<td><div class='colorButton red' onClick='setColor("#ff0000");'></div>
								<div class='colorButton green' onClick='setColor("#00ff00");'></div>
								<div class='colorButton blue' onClick='setColor("#0000ff");'></div>
								<div class='colorButton white' onClick='setColor("#ffffff");'></div>
								<div class='colorButton black' onClick='setColor("#000000");'></div>
							</td>
						</tr>
						<tr><td>Current Color:</td><td><span id='currentColor'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
						<tr><td colspan='2'>Show Color Picker: <? PrintSettingCheckbox("Show Color Picker", "ShowColorPicker", 0, 0, "1", "0", $pluginName, "ShowColorPicker"); ?></td></tr>
						<tr><td valign='top' colspan='2'>
						<div id="colpicker"></div>
						</td></tr>
					</table>
				</div>
				</td></tr>
			</table>
		</div>
<p><b>If you set the scroll speed to 0, then the message will display on the center of the matrix <br/>
for the number of seconds set in the Duration (or Forever)</b></p> 
		<p>Scroll Speed: <? PrintSettingSelect("SCROLL_SPEED", "SCROLL_SPEED", 0, 0, $defaultValue="20", getScrollSpeed(), $pluginName, $callbackName = "ShowDuration", $changedFunction = ""); ?></p>
		<div id="scrollOptions" style= "<? echo $showScrollOptionsDiv; ?>">
			<p>Scroll Direction: <? PrintSettingSelect("SCROLL_DIRECTION", "SCROLL_DIRECTION", 0, 0, $defaultValue="horizontal", getScrollDirection(), $pluginName, $callbackName = "updateScroll", $changedFunction = ""); ?></p>
			<p>Invert Scroll: <?PrintSettingCheckbox("SCROLL_INVERT", "SCROLL_INVERT", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateScroll", $changedFunction = ""); ?></p>
		</div>
		<div id="showDuration" style= "<? echo $showScrollDiv; ?>">
			<p>Duration: <? PrintSettingSelect("DURATION", "DURATION", 0, 0, $defaultValue="10", getDuration(), $pluginName, $callbackName = "", $changedFunction = ""); ?></p>
		</div>
		
		<p>Matrix Name: <? PrintSettingSelect("OVERLAY_MODEL", "OVERLAY_MODEL", 0, 0, $defaultValue="", $values = GetOverlayList(), $pluginName, $callbackName = "", $changedFunction = ""); ?>
		<span id="OVERLAY_MODEL_tip" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="auto" data-bs-title="If this is blank, then you need to configure the correct Pixel Overlay Model"><img id="OVERLAY_MODEL_img" src="images/redesign/help-icon.svg" class="icon-help" alt="OVERLAY_MODEL help icon"></span></p>
		<p><button type="button" id="previewMatrixBtn" class="btn btn-primary btn-xs">Preview Matrix</button>
		<span id="PREVIEW_MATRIX_tip" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="auto" data-bs-title="Opens a popup showing exactly how the countdown will look on your configured Matrix"><img id="PREVIEW_MATRIX_img" src="images/redesign/help-icon.svg" class="icon-help" alt="Preview Matrix help icon"></span></p>
		<p>Overlay Mode: <? PrintSettingSelect("OVERLAY_MODE", "OVERLAY_MODE", 0, 0, "", Array("Full Overlay" => "1", "Transparent" => "2", "Transparent RGB" => "3"), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
		<p><h3>The Overlay mode determines how you want your message to display.</h3>
		<ul>
			<li>Full Overlay - This will blank out the model and only display your message</li>
			<li>Transparent - This will display your message over the top of whatever is displaying on your matrix <br/>
			but the colors will blend slightly with what is currently being displayed</li>
			<li>Transparent RGB - This will display your message over the top of whatever is displaying on your matrix <br/>
			the colors will override what is currently being displayed</li> 
		</ul>
		
		<p>To report a bug, please file it on the Simple Countdown plugin project on Git:<a href= "<? echo $gitURL;?>" target=blank>Simple Countdown Repository</a> </p>
		<p>Host Location: <?  PrintSettingTextSaved("HOST_LOCATION", 0, 0, $maxlength = 16, $size = 16, $pluginName, $defaultValue = "127.0.0.1", $callbackName = "", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>The default location of 127.0.0.1 is used if you want to display your Countdown on an Overlay Model directly connected to this device. <br />
		You can send the Countdown text to another FPP device by entering that IP address for the Host Location. The Host location will need <br />
		to have the Pixel Overlay Model defined and this FPP will need to have the Pixel Overlay Model defined exactly as the Host FPP Device</p>
	</div>
</div>

<div class="modal fade" id="previewMatrixModal" tabindex="-1" role="dialog" aria-labelledby="previewMatrixModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="previewMatrixModalLabel">Matrix Preview</h4>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div id="previewMatrixInfo"></div>
				<div id="previewMatrixContainer">
					<canvas id="previewMatrixCanvas"></canvas>
				</div>
				<div id="previewMessageText"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
var overlayModelData = <?php echo json_encode($overlayModelData); ?>;
</script>

<script>
var previewAnimation = null;
var previewSignature = '';
ShowColorPicker();
ShowDuration(true);
setInterval(updateOutputText, 1000);
document.getElementById('SCROLL_SPEED').addEventListener('change', function() {
	ShowDuration();
	updateOutputText();
});

function ShowColorPicker() {
	if ($('#ShowColorPicker').is(':checked')) {
            $('#colpicker').show();
    } else {
            $('#colpicker').hide();
    }
}

function setColor(color, updateColpicker = true) {
	if (color.substring(0,1) != '#')
		color = '#' + color;
    pluginSettings['COLOR'] = color;
    $.ajax({
		url: 'api/plugin/<?php echo $pluginName; ?>/settings/COLOR',
		data: '' + color,
		method: 'PUT',
		timeout: 1000,
		async: false,
		success: function () {
			$.jGrowl('COLOR Changed.', { themeState: 'success' });
		},
		fail: function () {
			DialogError('Save Setting', 'Failed to save COLOR setting.');
		}
	});
    $('#currentColor').css('background-color', color);
	currentColor = color;
    if (updateColpicker)
		$('#colpicker').colpickSetColor(color);
	updateOutputText();
		
}
    var colpickTimer = null;
	$('#colpicker').colpick({
		flat: true,
		layout: 'rgbhex',
		color: '#ff0000',
		submit: false,
		onChange: function(hsb,hex,rgb,el,bySetColor) {
            if (bySetColor)
                return;

            if (colpickTimer != null)
                clearTimeout(colpickTimer);

            colpickTimer = setTimeout(function() { setColor('#'+hex, false); }, 500);
		}
	});

    if (pluginSettings.hasOwnProperty('COLOR') && pluginSettings['COLOR'] != '') {
        currentColor = pluginSettings['COLOR'];
        $('#currentColor').css('background-color', currentColor);
    }

updateOutputText();
	
function updateOutputTextHours(updateOutput){
	updateOutputText();	
}

function updateOutputTextSeconds(updateOutput){
	updateOutputText();	
}

function updateOutputText(){
	var messageText= getMessageText();
	var textEl = document.getElementById("scroll-text");
	var containerEl = document.getElementById("scroll-container");
	textEl.innerHTML = messageText;
	if (typeof(currentColor) != 'undefined' && currentColor != '')
		textEl.style.color = currentColor;
	clearScrollDirection();
	textEl.classList.add('center');
	containerEl.classList.remove('vertical');
	textEl.style.textAlign = 'center';
}

function updateScrollDirection(direction, invert){
	var textEl = document.getElementById("scroll-text");
	var containerEl = document.getElementById("scroll-container");
	var isVertical = (direction == 'vertical');
	containerEl.classList.toggle('vertical', isVertical);
	textEl.style.textAlign = isVertical ? 'center' : '';

	var speed = parseInt(document.getElementById('SCROLL_SPEED').value);
	if (speed <= 0) {
		return;
	}

	var cw = containerEl.clientWidth;
	var ch = containerEl.clientHeight;
	var range = document.createRange();
	range.selectNodeContents(textEl);
	var rect = range.getBoundingClientRect();
	var tw = rect.width || textEl.scrollWidth;
	var th = rect.height || textEl.scrollHeight;

	var fromVal;
	var toVal;
	var base;
	if (isVertical) {
		base = ch;
		if (invert) {
			fromVal = -th;
			toVal = ch;
		} else {
			fromVal = ch;
			toVal = -th;
		}
	} else {
		base = cw;
		if (invert) {
			fromVal = -tw;
			toVal = cw;
		} else {
			fromVal = cw;
			toVal = -tw;
		}
	}
	var duration = Math.max(1, (1.5 * base) / speed);

	if (textEl.animate) {
		var signature = (isVertical ? 'v' : 'h') + (invert ? '1' : '0') + ':' + Math.round(duration * 10) + ':' + textEl.innerHTML.length;
		if (previewAnimation && previewSignature === signature) {
			return;
		}
		if (previewAnimation) {
			previewAnimation.cancel();
		}
		textEl.style.animation = 'none';
		var translate = isVertical ? 'translateY' : 'translateX';
		previewAnimation = textEl.animate([
			{ transform: translate + '(' + fromVal + 'px)' },
			{ transform: translate + '(' + toVal + 'px)' }
		], { duration: duration * 1000, iterations: Infinity, easing: 'linear' });
		previewSignature = signature;
	} else {
		textEl.style.animation = '';
		if (isVertical) {
			textEl.style.transform = 'none';
			textEl.style.webkitTransform = 'none';
			textEl.style.mozTransform = 'none';
		} else {
			textEl.style.transform = '';
			textEl.style.webkitTransform = '';
			textEl.style.mozTransform = '';
		}
		textEl.style.animationName = isVertical ? (invert ? 'my-animation-t2b' : 'my-animation-b2t') : (invert ? 'my-animation-l2r' : 'my-animation');
		textEl.style.webkitAnimationName = textEl.style.animationName;
		textEl.style.mozAnimationName = textEl.style.animationName;
		textEl.style.animationDuration = duration + 's';
		textEl.style.webkitAnimationDuration = duration + 's';
		textEl.style.mozAnimationDuration = duration + 's';
	}
}

function clearScrollDirection(){
	var textEl = document.getElementById("scroll-text");
	var containerEl = document.getElementById("scroll-container");
	if (previewAnimation) {
		previewAnimation.cancel();
		previewAnimation = null;
	}
	previewSignature = '';
	containerEl.classList.remove('vertical');
	textEl.style.animation = '';
	textEl.style.textAlign = '';
	textEl.style.animationName = '';
	textEl.style.webkitAnimationName = '';
	textEl.style.mozAnimationName = '';
	textEl.style.animationDuration = '';
	textEl.style.webkitAnimationDuration = '';
	textEl.style.mozAnimationDuration = '';
	textEl.style.transform = '';
	textEl.style.webkitTransform = '';
	textEl.style.mozTransform = '';
}

function updateScroll(setting){
	updateOutputText();
}

function updateFont(){
	updateOutputText();
}

function getMessageText(){
	var elapsed = false; 
	var eventName = document.getElementById("EVENT_NAME").value;
	var eventMonth = parseInt(document.getElementById("MONTH").value)-1;
	var eventDay = document.getElementById("DAY").value;
	var eventYear = document.getElementById("YEAR").value;
	var eventHour = document.getElementById("HOUR").value;
	var eventMin = document.getElementById("MIN").value;
	var preText = document.getElementById("PRE_TEXT").value;
	var postText = document.getElementById("POST_TEXT").value;
	var CountUpPreText = document.getElementById("COUNTUP_PRE_TEXT").value;
	var CountUpPostText = document.getElementById("COUNTUP_POST_TEXT").value;	
	var completedText = document.getElementById("COMPLETED_MESSAGE").value;
	var incHours = document.getElementById("INCLUDE_HOURS").checked;
	var incMin = document.getElementById("INCLUDE_MINUTES").checked;
	var incSec = document.getElementById("INCLUDE_SECONDS").checked && document.getElementById('showSeconds').style.display != "none";
	var countup = document.getElementById("COUNT_UP").checked;
	var eventDate = new Date(eventYear, eventMonth, eventDay, eventHour, eventMin);
	var currentDate= new Date();
	var rawTimeDiff = (eventDate - currentDate)/1000;
	var showSeconds = incSec == true && Math.abs(rawTimeDiff) < 3600; 
	var yearsToDate = rawTimeDiff/(60*60*24*365);
	var daysToDate = (rawTimeDiff/(60*60*24))%365;
	var hoursToDate = (rawTimeDiff/(60*60))%24;
	var minutesToDate = (rawTimeDiff/60)%60 +1;
	var secondsToDate = rawTimeDiff%60;
	var messageText;
	var messagePreText;
	var messagePostText;
	if (rawTimeDiff<0){
		elapsed= true;
	}
	if (elapsed && !countup){
		messageText= completedText;
		return messageText;
	}
				
	yearsToDate= Math.floor(Math.abs(yearsToDate));
	daysToDate= Math.floor(Math.abs(daysToDate));
	hoursToDate =Math.floor(Math.abs(hoursToDate));
	minutesToDate= Math.floor(Math.abs(minutesToDate));
	secondsToDate= Math.floor(Math.abs(secondsToDate));
	
	if (elapsed && countup){
		messagePreText= CountUpPreText;
		messagePostText= CountUpPostText;
		minutesToDate+= 1;
	}else{
		messagePreText= preText;
		messagePostText= postText;	
	}

	messageText = messagePreText;

	if (yearsToDate >= 1){
		if (yearsToDate >=2){
			messageText += " " + yearsToDate + " years ";
		}else {
			messageText += " " + yearsToDate + " year ";
		}
	}else{
		messageText += " ";
	}

	if (daysToDate >= 1){
		if (daysToDate >=2){
			messageText += daysToDate + " days ";
		} else {
			messageText += daysToDate + " day ";			
		}

		if(incHours == true){			
			if (hoursToDate >=2) {
				messageText += hoursToDate + " hours ";
			} else {
				if (hoursToDate >= 1) {
					messageText += hoursToDate + " hour ";
				}
			}
		}
		
		if(incMin == true){
			if(incHours == false){
				minutesToDate += hoursToDate*60;
			}
			if (minutesToDate >=2) {
				messageText += minutesToDate + " minutes ";
			} else {
				messageText += minutesToDate + " minute ";
			}	
		}
		
		if(showSeconds == true){
			if (secondsToDate >=2) {
				messageText += secondsToDate + " seconds ";
			} else {
				messageText += secondsToDate + " second ";
			}
		}	
	}else {
			
		if (hoursToDate >=2) {
			messageText += hoursToDate + " hours ";
		} else {
			if (hoursToDate >= 1) {
					messageText += hoursToDate + " hour ";
			}
		}
		
		if (minutesToDate >=2) {
			messageText += minutesToDate + " minutes ";
		} else {
			messageText += minutesToDate + " minute ";
		}	

		if(showSeconds == true){
			if (secondsToDate >=2) {
				messageText += secondsToDate + " seconds ";
			} else {
				messageText += secondsToDate + " second ";
			}
		}
	}           
    
	messageText += messagePostText + " " + eventName;
	return messageText.replace(/^\s+/, '');
}

function ShowDuration(skipSave){
	var scrollSpeed = document.getElementById('SCROLL_SPEED').value;
	if (scrollSpeed ==0){
		document.getElementById('showDuration').style.display = "block";
		document.getElementById('showSeconds').style.display = "block";
		document.getElementById('scroll-text').classList.add('center');
		document.getElementById('scrollOptions').style.display = "none";
		document.getElementById('SCROLL_DIRECTION').value = "horizontal";
		document.getElementById('SCROLL_INVERT').checked = false;
		if (!skipSave){
			SetPluginSetting('<?php echo $pluginName; ?>', 'SCROLL_DIRECTION', 'horizontal', 0, 0, null);
			SetPluginSetting('<?php echo $pluginName; ?>', 'SCROLL_INVERT', 'OFF', 0, 0, false);
		}
		updateOutputText();
	}else{
		document.getElementById('showDuration').style.display = "none";
		document.getElementById('showSeconds').style.display = "none";
		document.getElementById('INCLUDE_SECONDS').checked = false;
		document.getElementById('scroll-text').classList.remove('center');
		document.getElementById('scrollOptions').style.display = "block";
		updateOutputText();
	}
}

function ShowCountUp(){
	if (document.getElementById('COUNT_UP').checked == true){
		document.getElementById('showCountUp').style.display = "block";
		document.getElementById('showCompleted').style.display = "none";
		updateOutputText();
	}else{
		document.getElementById('showCountUp').style.display = "none";
		document.getElementById('showCompleted').style.display = "block";
		updateOutputText();
	}	
}

var previewModelData = null;
var previewAnimFrame = null;

document.getElementById('previewMatrixBtn').addEventListener('click', function() { openMatrixPreview(); });

function openMatrixPreview(){
	var model = document.getElementById('OVERLAY_MODEL').value;
	var modelData = overlayModelData[model];
	if (!model || !modelData){
		DialogError('Preview Matrix', 'No Pixel Overlay Model selected or the model dimensions are not available. Please select a valid Matrix in the Matrix Name setting.');
		return;
	}
	var canvas = document.getElementById('previewMatrixCanvas');
	canvas.width = modelData.w;
	canvas.height = modelData.h;
	previewModelData = modelData;

	var container = document.getElementById('previewMatrixContainer');
	var availW = container.clientWidth;
	if (!availW || availW < 40) availW = 860;
	var cellSize = 4;
	var dispW = modelData.w * cellSize;
	var dispH = modelData.h * cellSize;
	var maxH = 460;
	var s = Math.min(1, (availW - 2) / dispW, maxH / dispH);
	s = Math.max(s, 0.05);
	canvas.style.width = Math.max(1, Math.round(dispW * s)) + 'px';
	canvas.style.height = Math.max(1, Math.round(dispH * s)) + 'px';

	updatePreviewInfo(model);

	loadPreviewFont(document.getElementById('FONT').value);

	if (previewAnimFrame)
		cancelAnimationFrame(previewAnimFrame);
	renderPreviewFrame();
	previewAnimFrame = requestAnimationFrame(previewRenderLoop);

	showPreviewModal();
}

var previewFontUrl = '/plugin.php?plugin=<?php echo $pluginName; ?>&page=font.php&nopage=1&name=';
var previewFontsLoaded = {};

function getPreviewFontFamily(fontName){
	return 'FPP-Preview-' + fontName.replace(/[^a-zA-Z0-9]+/g, '-');
}

function loadPreviewFont(fontName){
	var family = getPreviewFontFamily(fontName);
	if (previewFontsLoaded.hasOwnProperty(family) || !window.FontFace || !fontName)
		return;
	previewFontsLoaded[family] = false;
	fetch(previewFontUrl + encodeURIComponent(fontName))
		.then(function(resp){
			if (!resp.ok)
				throw new Error('font not found');
			return resp.blob();
		})
		.then(function(blob){
			return new Promise(function(resolve, reject){
				var textFile = new FileReader();
				textFile.onload = function(){ resolve(textFile.result); };
				textFile.onerror = reject;
				textFile.readAsArrayBuffer(blob);
			});
		})
		.then(function(buffer){
			var font = new FontFace(family, buffer);
			return font.load();
		})
		.then(function(font){
			document.fonts.add(font);
			previewFontsLoaded[family] = true;
		})
		.catch(function(){
			previewFontsLoaded[family] = false;
		});
}

function previewRenderLoop(){
	renderPreviewFrame();
	previewAnimFrame = requestAnimationFrame(previewRenderLoop);
}

function renderPreviewFrame(){
	if (!previewModelData)
		return;
	var canvas = document.getElementById('previewMatrixCanvas');
	var ctx = canvas.getContext('2d');
	var w = canvas.width, h = canvas.height;
	var msg = getMessageText();
	var fontSize = parseInt(document.getElementById('FONT_SIZE').value);
	if (!fontSize || fontSize < 1) fontSize = 1;
	var fontName = document.getElementById('FONT').value || 'Sans';
	var color = (typeof currentColor != 'undefined' && currentColor) ? currentColor : '#ffffff';
	var speed = parseInt(document.getElementById('SCROLL_SPEED').value);
	if (!speed || speed < 0) speed = 0;
	var direction = document.getElementById('SCROLL_DIRECTION').value;
	var invert = document.getElementById('SCROLL_INVERT').checked;

	ctx.clearRect(0, 0, w, h);
	ctx.fillStyle = '#000000';
	ctx.fillRect(0, 0, w, h);
	ctx.fillStyle = color;
	var fontCss = fontName;
	if (fontName != 'Sans' && previewFontsLoaded[getPreviewFontFamily(fontName)] === true)
		fontCss = getPreviewFontFamily(fontName);
	ctx.font = fontSize + 'px "' + fontCss + '", sans-serif';
	ctx.textBaseline = 'middle';
	ctx.textAlign = 'center';

	var tw = ctx.measureText(msg).width;
	var th = fontSize;
	var x, y;

	if (speed > 0){
		var horizontal = (direction != 'vertical');
		var travel = (horizontal ? tw + w : th + h);
		var t = (Date.now() * speed / 1000) % travel;
		if (horizontal){
			x = (invert ? -tw/2 + t : w + tw/2 - t);
			y = h/2;
		}else{
			x = w/2;
			y = (invert ? -th/2 + t : h + th/2 - t);
		}
	}else{
		x = w/2;
		y = h/2;
	}
	ctx.fillText(msg, x, y);

	var info = document.getElementById('previewMessageText');
	if (info.innerHTML != ('Message: "' + msg + '"')){
		info.innerHTML = 'Message: "' + msg + '"';
	}
}

function updatePreviewInfo(model){
	var fontName = document.getElementById('FONT').value || 'Sans';
	var fontSize = parseInt(document.getElementById('FONT_SIZE').value) || 20;
	var color = (typeof currentColor != 'undefined' && currentColor) ? currentColor : '#ffffff';
	var speed = parseInt(document.getElementById('SCROLL_SPEED').value) || 0;
	var direction = document.getElementById('SCROLL_DIRECTION').value;
	var invert = document.getElementById('SCROLL_INVERT').checked;
	var modeSel = document.getElementById('OVERLAY_MODE');
	var mode = (modeSel.selectedOptions.length ? modeSel.selectedOptions[0].text : 'Full Overlay');
	var scroll = 'Centered, Duration ' + (document.getElementById('DURATION').value || '10') + 's';
	if (speed > 0)
		scroll = speed + ' px/s ' + direction + (invert ? ' (inverted)' : '');
	var el = document.getElementById('previewMatrixInfo');
	el.innerHTML = '<b>Matrix:</b> ' + model + ' (' + previewModelData.w + 'w x ' + previewModelData.h + 'h)' +
		' &nbsp;|&nbsp; <b>Font:</b> ' + fontName +
		' <b>Size:</b> ' + fontSize +
		' <b>Color:</b> ' + color +
		' &nbsp;|&nbsp; <b>Mode:</b> ' + mode +
		'<br/><b>Scroll:</b> ' + scroll;
}

function showPreviewModal(){
	var modalEl = document.getElementById('previewMatrixModal');
	if (window.bootstrap && bootstrap.Modal){
		bootstrap.Modal.getOrCreateInstance(modalEl).show();
	}else if (window.jQuery && jQuery.fn.modal){
		jQuery(modalEl).modal('show');
	}else if (window.jQuery){
		jQuery(modalEl).show();
	}
}

if (window.jQuery){
	jQuery('#previewMatrixModal').on('hidden.bs.modal', function(){
		if (previewAnimFrame){
			cancelAnimationFrame(previewAnimFrame);
			previewAnimFrame = null;
		}
	});
}

</script>
</html>