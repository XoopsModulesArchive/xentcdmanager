<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_group_desc.html';

    $id = $_GET['id'];

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[3]) . " WHERE ID_GROUP=$id";
    $result = $xoopsDB->query($sql);
    $group = $xoopsDB->fetchArray($result);
    $group['name'] = $myts->displayTarea($group['name'], 1, 0, 1, 0);
    $group['description'] = $myts->displayTarea($group['description'], 1, 0, 1, 0);
    $xoopsTpl->append('group', $group);

    $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group=$id";
    $result1 = $xoopsDB->query($sql1);

    while ($cd_from_group = $xoopsDB->fetchArray($result1)) {
        $xoopsTpl->append('cd_from_group', $cd_from_group);
    }

    $xoopsTpl->assign('group_title', _MA_CM_GROUPDESC_TITLE);

    include 'footer.php';
