<?php

$updaterBase = 'https://projects.bluewindlab.net/wpplugin/zipped/plugins/';
$pluginRemoteUpdater = $updaterBase . 'bkbm/notifier_bkbm_rkbur.php';
new WpAutoUpdater(BKBRKB_ADDON_CURRENT_VERSION, $pluginRemoteUpdater, BKBRKB_ADDON_UPDATER_SLUG);
