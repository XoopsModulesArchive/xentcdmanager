<?php

    include '../../mainfile.php';

    global $xoopsConfig;
    $lang = $xoopsConfig['language'];

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');
