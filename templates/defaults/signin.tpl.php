<?php require_once("backend/Login/loginInitialise.inc.php"); ?>

<?php if (!$guestInfo && !$isAdmin) { ?>
    <button class="default-button" onclick="window.location.href = '<?php echo $googleUrl ?>'">Sign In</button>
<?php } else { ?>
    <button class="default-button" onclick="window.location.href = 'backend/Login/logout.php'">Logout</button>
<?php } ?>