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

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_cd_desc.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $id = $_GET['id'];

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id";

    $result = $xoopsDB->query($sql);
    $cd = $xoopsDB->fetchArray($result);

    if ($permObject->checkRight('category_read', $cd['id_cat'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
        if (0 == $cd['copy']) {
            $cd['copy'] = _MA_CM_CDDESC_NO;
        } else {
            $cd['copy'] = _MA_CM_CDDESC_YES;
        }

        if ($xoopsUser->isAdmin()) {
            $cd['cdkey'] = $cd['cdkey'];
        } else {
            $cd['cdkey'] = '';
        }

        $cd['cat'] = reference($module_tables[0], 'name', 'ID_CAT', $cd['id_cat']);

        if (0 == $cd['id_group']) {
            $cd['group'] = '';
        } else {
            $cd['group'] = reference($module_tables[3], 'name', 'ID_GROUP', $cd['id_group']);
        }

        $cd['prefix'] = reference($module_tables[0], 'prefix', 'ID_CAT', $cd['id_cat']);

        $cd['description'] = $myts->displayTarea($cd['description'], 1, 0, 1, 0);

        // on doit savoir le cd est loué ou non pour afficher s'il est en stock ou non

        $icd = $cd['ID_CD'];

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE id_cd=$icd";

        $result = $xoopsDB->query($sql);

        $date_now = date('Y') . date('m') . date('d') . date('H') . date('i');

        $isRent = 0;

        while (false !== ($rent = $xoopsDB->fetchArray($result))) {
            $isRent = 1;

            if ($date_now >= $rent['date_rent'] && $date_now <= $rent['date_back']) {
                $img = 'images/light_red.gif';
            } else {
                $img = 'images/light_green.gif';
            }
        }

        if (0 == $isRent) {
            $img = 'images/light_green.gif';
        }

        $xoopsTpl->append('cd', $cd);

        $xoopsTpl->assign('img', $img);

        $xoopsTpl->assign('textinstock', _MA_CM_CDDESC_INSTOCK);

        $xoopsTpl->assign('textno', _MA_CM_CDDESC_TEXTNO);

        $xoopsTpl->assign('textname', _MA_CM_CDDESC_TEXTNAME);

        $xoopsTpl->assign('textdesc', _MA_CM_CDDESC_TEXTDESC);

        $xoopsTpl->assign('textnoseq', _MA_CM_CDDESC_TEXTNOSEQ);

        $xoopsTpl->assign('textcopy', _MA_CM_CDDESC_TEXTCOPY);

        $xoopsTpl->assign('textgroup', _MA_CM_CDDESC_TEXTGROUP);

        $xoopsTpl->assign('textlanguage', _MA_CM_CDDESC_TEXTLANGUAGE);

        $xoopsTpl->assign('textdatep', _MA_CM_CDDESC_TEXTDATEP);

        $xoopsTpl->assign('textcdkey', _MA_CM_CDDESC_TEXTCDKEY);

        $xoopsTpl->assign('textcd', _MA_CM_CDDESC_TEXTCD);

        $xoopsTpl->assign('textcat', _MA_CM_CDDESC_TEXTCAT);

        $xoopsTpl->assign('cd_reserve', _MA_CM_CDDESC_RESERVE);

        $xoopsTpl->assign('cddesc_title', _MA_CM_CDDESC_TITLE);

        $xoopsTpl->assign('cd_viewres', _MA_CM_CDDESC_VIEWRES);

        $xoopsTpl->assign('cd_editcd', _MA_CM_CDDESC_EDITCD);

        $xoopsTpl->assign('month', date('m'));

        $xoopsTpl->assign('year', date('Y'));

        $xoopsTpl->assign('isAdmin', $xoopsUser->isAdmin());

        include 'footer.php';
    } else {
        redirect_header('index.php', 5, _MA_CM_NORIGHTS);
    }
