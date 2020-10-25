<?php

    //echo "<link rel='stylesheet' type='text/css' media='all' href='include/admin.css'>";

    require __DIR__ . '/admin_buttons.php';
    include '../../../mainfile.php';
    require dirname(__DIR__, 3) . '/include/cp_header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once dirname(__DIR__) . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentcdmanager/include/functions.php';

    global $xoopsModule;

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');

    if (is_object($xoopsUser)) {
        $xoopsModule = XoopsModule::getByDirname('xentcdmanager');

        if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . '/', 1, _NOPERM);

            exit();
        }
    } else {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);

        exit();
    }

    $module_id = $xoopsModule->getVar('mid');
    $oAdminButton = new AdminButtons();
    $oAdminButton->AddTitle(_AM_CM_ADMINTITLE);

    $oAdminButton->AddButton(_AM_CM_MENU_INDEX, 'index.php', 'index');
    $oAdminButton->AddButton(_AM_CM_MENU_ADMINCAT, 'admincat.php', 'admincat');
    $oAdminButton->AddButton(_AM_CM_MENU_ADMINCD, 'admincd.php', 'admincd');
    $oAdminButton->AddButton(_AM_CM_MENU_ADMINGROUP, 'admingroup.php', 'admingroup');
    $oAdminButton->AddButton(_AM_CM_MENU_ADMINRENT, 'adminrent.php', 'adminrent');
    $oAdminButton->AddButton(_AM_CM_RENT_ADMIN_ADMINPERM, 'adminperm.php', 'adminperm');

    $oAdminButton->AddTopLink(_AM_CM_MENU_PREFERENCES, XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module_id);
    $oAdminButton->addTopLink(_AM_CM_MENU_UPDATEMODULE, XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=xentcdmanager');

    $myts = MyTextSanitizer::getInstance();
