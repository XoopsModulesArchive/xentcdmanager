<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/kernel/groupperm.php';
    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables, $xoopsUser;

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_index.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $id = 0;

    if (!empty($_GET['tri'])) {
        $tri = $_GET['tri'];
    } else {
        $tri = 0;
    }

    // Pour afficher le chemin en haut du tableau ex (INDEX | ODESIA | PROGRAMMES|)
    $a = [];
    $a = getCatTree($id);

    $path = "| <a href='index.php'>" . _MA_CM_INDEX_CATTREEROOT . '</a>';
    for ($x = count($a) - 1; $x >= 0; $x--) {
        $path .= $myts->displayTarea($a[$x], 1, 0, 1, 0) . ' | ';
    }
    // FIN

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . ' WHERE id_cat_parent = 0 ';
    switch ($tri) {
        // par nom
        case 2:
            $sql .= 'ORDER BY name';
            break;
        //par préfix
        case 3:
            $sql .= 'ORDER BY prefix';
            break;
    }
    $result = $xoopsDB->query($sql);

    while (false !== ($cat_data = $xoopsDB->fetchArray($result))) {
        if (!empty($cat_data['ID_CAT'])) {
            if ($permObject->checkRight('category_read', $cat_data['ID_CAT'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
                // trouver le nombre de cd dans la cat

                $id_cat = $cat_data['ID_CAT'];

                $countcd = getNumberOfCdInCat($id_cat, 0, true);

                $cat_data['name'] = $myts->displayTarea($cat_data['name'], 1, 0, 1, 0) . ' (' . $countcd . ' cd)';

                $cat_data['prefix'] = $myts->displayTarea($cat_data['prefix'], 1, 0, 1, 0);

                $xoopsTpl->append('cat_data_list', $cat_data);

                $xoopsTpl->assign('hasSubCat', 1);
            }
        } else {
            $xoopsTpl->assign('hasSubCat', 0);
        }
    }

    $xoopsTpl->assign('path', $path);
    $xoopsTpl->assign('isPrefix', $xoopsModuleConfig['isPrefix']);
    $xoopsTpl->assign('idtext', _MA_CM_INDEX_IDTEXT);
    $xoopsTpl->assign('nametext', _MA_CM_INDEX_NAMETEXT);
    $xoopsTpl->assign('desctext', _MA_CM_INDEX_DESCTEXT);
    $xoopsTpl->assign('prefixtext', _MA_CM_INDEX_PREFIXTEXT);
    $xoopsTpl->assign('hasNoSubCatText', _MA_CM_SCATLIST_HASNOSUBCATTEXT);

    include 'footer.php';
