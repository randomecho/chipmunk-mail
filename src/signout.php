<?php

/**
 * signout.php -- cleans up session and logs the user out
 *
 *  Cleans up after the user. Resets cookies and terminates session.
 *
 * @copyright 1999-2011 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: signout.php 14084 2011-01-06 02:44:03Z pdontthink $
 * @package squirrelmail
 */

/** This is the signout page */
define('PAGE_NAME', 'signout');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */
define('SM_PATH','../');

require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/prefs.php');
require_once(SM_PATH . 'functions/plugin.php');
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'functions/html.php');

/* Erase any lingering attachments */
sqgetGlobalVar('compose_messages',  $compose_messages,  SQ_SESSION);
if (!empty($compose_messages) && is_array($compose_messages)) {
    foreach($compose_messages as $composeMessage) {
        $composeMessage->purgeAttachments();
    }
}

if (!isset($frame_top)) {
    $frame_top = '_top';
}

/* If a user hits reload on the last page, $base_uri isn't set
 * because it was deleted with the session. */
if (! sqgetGlobalVar('base_uri', $base_uri, SQ_SESSION) ) {
    require_once(SM_PATH . 'functions/display_messages.php');
}

do_hook('logout');

sqsession_destroy();

if ($signout_page) {
    // Status 303 header is disabled. PHP fastcgi bug. See 1.91 changelog.
    //header('Status: 303 See Other');
    header("Location: $signout_page");
    exit; /* we send no content if we're redirecting. */
}

/* internal gettext functions will fail, if language is not set */
set_up_language($squirrelmail_language, true, true);
?>
<!doctype html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="robots" content="noindex,nofollow">
<?php
    if ($theme_css != '') {
?>
   <link rel="stylesheet" type="text/css" href="<?php echo $theme_css; ?>">
<?php
    }
?>
   <title><?php echo $org_title . ' - ' . _("Signout"); ?></title>
    <style>
    body {
      background: <?php echo $color[4]; ?>;
      color: <?php echo $color[8]; ?>;
    }
    div {
      background: <?php echo $color[4]; ?>;
    }
    header {
      background: <?php echo $color[0]; ?>;
    }
    a {
      color: <?php echo $color[7]; ?>;
    }
    a:visited {
      color: <?php echo $color[7]; ?>;
    }
    a:hover, a:active {
      color: <?php echo $color[7]; ?>;
    }
    </style>
</head>
<body>
<?php
$plugin_message = concat_hook_function('logout_above_text');
$plugin_message = 'ahoasf';
echo html_tag( 'header',
    html_tag( 'h1', _("Sign Out") ) ,
    null, null );
echo html_tag( 'div',
    $plugin_message .
    null, null );
echo html_tag( 'div',
    html_tag( 'p', _("You have been successfully signed out."), null ) .
    html_tag( 'p', '<a href="login.php" target="' . $frame_top . '">' .
         _("Click here to log back in.") . '</a>' , null ) .
    null, null );
?>
</body>
</html>
