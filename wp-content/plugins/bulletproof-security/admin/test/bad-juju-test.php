<html>
<head>
<title>Bad JuJu Test - php.ini disable_functions Test</title>
</head>
<body>
<div id="badJuJu" style=" border-color:#000000; border-style:solid; height:100px;">
<?php
show_source("bad-juju-test.php");
?>
</div>
<h3>If the box above does not contain any text or HTML code then the php.ini disable_functions test was successful. This also means that your php.ini file is set up correctly. Check your PHP Error Log and you should see an error in your log that looks like this:<br /><br />
[03-May-2011 18:14:36] PHP Warning:  show_source() has been disabled for security reasons in /home/content/xx/xxxxxxx/html/bad-juju-test.php on line 9</h3>

<h3>This test will not work by default for the 1&1, DreamHost and MediaTemple Master php.ini files due to additional php.ini modifications required first before using this test.</h3>

<h3>If the box above does contain text or HTML code then your php.ini file may be set up correctly, but the new php.ini file you have added or changes you have made in your php.ini file may not show up in your PHP server configuration file for up to 15 minutes. Usually new changes or modifications to your php.ini file will be displayed within 5 minutes. Click on the PHP Info Viewer tab menu and click on the View PHPINFO button to check if your php.ini changes are displayed in the phpinfo file yet. Look under the <strong>Configuration PHP Core</strong> section in your phpinfo file and look for the disable_functions Directive. If you do not see a list of functions that are disabled then either your PHP server has not been updated yet or you have made a mistake in setting up your php.ini file. Wait at least 5 minutes and then click the View PHPINFO button again or if your phpinfo popup window is still open then refresh the popup window after 5 minutes.<br /><br />
<strong>Example of what you should see in your phpinfo popup window under the disable_functions Directive:</strong>
<h2><img src="../images/bad-juju-test.png" style="float:left; padding:0px;"/></h3>
</body>
</html>