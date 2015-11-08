<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';
require_once './utility.php';
require_once './xml/validation.php';

?>
<?php
/*
<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="races">
				<attnum name="id" val="'.$conditions->id.'"/>
			</section>
		</section>
	</section>
</params>

<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="messages">
				<attnum name="number" val="1"/>
				<attstr name="message0" val="msgserver"/>
			</section>
			<section name="races">
				<attnum name="id" val="'.$myDb->lastInsertId.'"/>
			</section>
		</section>
	</section>
</params>

<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="laps">
				<attnum name="id" val="'.$myDb->lastInsertId.'"/>
			</section>
			<section name="messages">
				<attnum name="number" val="3"/>
				<attstr name="message0" val="So you have\n a good lap on the go"/>
				<attstr name="message1" val="good to kwno"/>
				<attstr name="message2" val="best lap"/>
			</section>
		</section>
	</section>
</params>
				
<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="login">
				<attstr name="sessionid" val="'.$user['sessionid'].'"/>
				<attnum name="id" val="'. $user['id'].'"/>
			</section>
		</section>
	</section>
</params>


$string='<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val=""/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="login">
				<attstr name="sessionid" val=""/>
				<attnum name="id" val=""/>
			</section>
		</section>
	</section>
</params>';
*/
$webserverversion= 1;

$string='<?xml version="1.0" encoding="UTF-8"?>
<params>
</params>';

$xmlreply= new SimpleXMLElement($string);
//$xmlreply->preserveWhiteSpace = false;
//$xmlreply->formatOutput = true;



$params = $xmlreply->xpath('/params')[0];//size"[@label='Large']");

$params->addAttribute('name','webServerReply');

$content = $params->addChild('section');
$content->addAttribute('name', 'content');

$version =  $content->addChild('attnum');
$version->addAttribute('name','webServerVersion');
$version->addAttribute('val',$webserverversion);

$date =  $content->addChild('attnum');
$date->addAttribute('name','date');
$date->addAttribute('val',time());

$error =  $content->addChild('attstr');
$error->addAttribute('name','error');
$error->addAttribute('val','this is an error');

$content = $params->addChild('reply');
$content->addAttribute('name', 'content');

//echo $xmlreply->asXML();
$domxml = new DOMDocument('1.0');
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;
$domxml->loadXML($xmlreply->asXML());
echo $domxml->saveXML();


?>
