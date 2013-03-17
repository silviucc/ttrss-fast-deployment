<html>
<head>
	<title>Tiny Tiny RSS : Login</title>
	<link rel="stylesheet" type="text/css" href="lib/dijit/themes/claro/claro.css"/>
	<link rel="stylesheet" type="text/css" href="tt-rss.css">
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="lib/dojo/dojo.js"></script>
	<script type="text/javascript" src="lib/dijit/dijit.js"></script>
	<script type="text/javascript" src="lib/dojo/tt-rss-layer.js"></script>
	<script type="text/javascript" src="lib/prototype.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" charset="utf-8" src="errors.php?mode=js"></script>
	<script type="text/javascript">
		Event.observe(window, 'load', function() {
			init();
		});
	</script>
	<style type="text/css">
	body#ttrssLogin {
		padding : 2em;
		font-size : 14px;
	}

	fieldset {
		margin-left : auto;
		margin-right : auto;
		display : block;
		width : 400px;
		border-width : 0px;
	}

	input.input {
		font-family : sans-serif;
		font-size : medium;
		border-spacing : 2px;
		border : 1px solid #b5bcc7;
		padding : 2px;
	}

	label {
		width : 120px;
		margin-right : 20px;
		display : inline-block;
		text-align : right;
		color : gray;
	}

	div.header {
		border-width : 0px 0px 1px 0px;
		border-style : solid;
		border-color : #88b0f0;
		margin-bottom : 1em;
		padding-bottom : 5px;
	}

	div.footer {
		margin-top : 1em;
		padding-top : 5px;
		border-width : 1px 0px 0px 0px;
		border-style : solid;
		border-color : #88b0f0;
		text-align : center;
		color : gray;
		font-size : 12px;
	}

	div.footer a {
		color : gray;
	}

	div.footer a:hover {
		color : #88b0f0;
	}

	div.row {
		padding : 0px 0px 5px 0px;
	}

	div.row-error {
		color : red;
		text-align : center;
		padding : 0px 0px 5px 0px;
	}

	</style>
</head>

<body id="ttrssLogin" class="claro">

<script type="text/javascript">
function init() {
	dojo.require("dijit.form.Button");
	dojo.require("dijit.form.CheckBox");
	dojo.require("dijit.form.Form");
	dojo.require("dijit.form.Select");
	dojo.require("dijit.form.TextBox");
	dojo.require("dijit.form.ValidationTextBox");

	dojo.parser.parse();

	fetchProfiles();

	dijit.byId("bw_limit").attr("checked", getCookie("ttrss_bwlimit") == 'true');

	document.forms.loginForm.login.focus();
}

function fetchProfiles() {
	try {
		var query = "?op=getProfiles&login=" + param_escape(document.forms["loginForm"].login.value);

		if (query) {
			new Ajax.Request("public.php",	{
				parameters: query,
				onComplete: function(transport) {
					if (transport.responseText.match("select")) {
						$('profile_box').innerHTML = transport.responseText;
						dojo.parser.parse('profile_box');
					}
			} });
		}

	} catch (e) {
		exception_error("fetchProfiles", e);
	}
}


function gotoRegForm() {
	window.location.href = "register.php";
	return false;
}

function bwLimitChange(elem) {
	try {
		var limit_set = elem.checked;

		setCookie("ttrss_bwlimit", limit_set,
			<?php print SESSION_COOKIE_LIFETIME ?>);

	} catch (e) {
		exception_error("bwLimitChange", e);
	}
}
</script>

<?php $return = urlencode($_SERVER["REQUEST_URI"]) ?>

<form action="public.php?return=<?php echo $return ?>"
	dojoType="dijit.form.Form" method="POST" id="loginForm" name="loginForm">

<input dojoType="dijit.form.TextBox" style="display : none" name="op" value="login">

<div class='header'>
	<img src="images/logo_wide.png">
</div>

<div class='form'>

	<fieldset>
		<?php if ($_SESSION["login_error_msg"]) { ?>
		<div class="row-error">
			<?php echo $_SESSION["login_error_msg"] ?>
		</div>
			<?php $_SESSION["login_error_msg"] = ""; ?>
		<?php } ?>
		<div class="row">
			<label><?php echo __("Login:") ?></label>
			<input name="login" class="input"
				onchange="fetchProfiles()" onfocus="fetchProfiles()" onblur="fetchProfiles()"
				style="width : 220px"
				required="1"
				value="<?php echo $_SESSION["fake_login"] ?>" />
		</div>

		<div class="row">
			<label><?php echo __("Password:") ?></label>
			<input type="password" name="password" required="1"
					style="width : 220px" class="input"
					value="<?php echo $_SESSION["fake_password"] ?>"/>
		</div>

		<div class="row">
			<label><?php echo __("Language:") ?></label>
			<?php
				print_select_hash("language", $_COOKIE["ttrss_lang"], get_translations(),
					"style='width : 220px; margin : 0px' dojoType='dijit.form.Select'");
			?>
		</div>

		<div class="row">
			<label><?php echo __("Profile:") ?></label>

			<span id='profile_box'><select disabled='disabled' dojoType='dijit.form.Select'
				style='width : 220px; margin : 0px'>
				<option><?php echo __("Default profile") ?></option></select></span>

		</div>

		<div class="row">
			<label>&nbsp;</label>
			<input dojoType="dijit.form.CheckBox" name="bw_limit" id="bw_limit" type="checkbox"
				onchange="bwLimitChange(this)">
			<label style='display : inline' for="bw_limit"><?php echo __("Use less traffic") ?></label>
		</div>

		<div class="row" style='text-align : right'>
			<button dojoType="dijit.form.Button" type="submit"><?php echo __('Log in') ?></button>
			<?php if (defined('ENABLE_REGISTRATION') && ENABLE_REGISTRATION) { ?>
				<button onclick="return gotoRegForm()" dojoType="dijit.form.Button">
					<?php echo __("Create new account") ?></button>
			<?php } ?>
		</div>

	</fieldset>


</div>

<div class='footer'>
	<a href="http://tt-rss.org/">Tiny Tiny RSS</a>
	<?php if (!defined('HIDE_VERSION')) { ?>
		 v<?php echo VERSION ?>
	<?php } ?>
	&copy; 2005&ndash;<?php echo date('Y') ?> <a href="http://fakecake.org/">Andrew Dolgov</a>
</div>

</form>

</body></html>
