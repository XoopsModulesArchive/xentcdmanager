<?php

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[4]);
    $result = $xoopsDB->query($sql);

    $count = 0;

    $myts = MyTextSanitizer::getInstance();

    // afficher les cat dans le combo box du search
    while ($searchcat = $xoopsDB->fetchArray($result)) {
        $searchcat['count'] = $count;

        $searchcat['name'] = $myts->displayTarea($searchcat['name'], 1, 0, 1, 0);

        $xoopsTpl->append('searchcat', $searchcat);

        $count++;
    }

    $sql = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix($module_tables[1]);
    $result = $xoopsDB->query($sql);
    $cd_c = $xoopsDB->fetchRow($result);

    $search = _MA_CM_HEADER_SEARCH;
    $menu = _MA_CM_HEADER_MENU;
    $mod_titre = $xoopsModuleConfig['module_title'];
    $version = $xoopsModuleConfig['version'];

    $xoopsTpl->assign('lang', $lang);
    $xoopsTpl->assign('search', $search);
    $xoopsTpl->assign('menu', $menu);
    $xoopsTpl->assign('version', $version);
    $xoopsTpl->assign('count_cd', _MA_CM_FOOTER_COUNTCD . $cd_c[0]);

    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" media="all" href="include/xentcdmanager.css">');

    require XOOPS_ROOT_PATH . '/footer.php';
