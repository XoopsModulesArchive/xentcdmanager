<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables, $xoopsUser;

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_res_desc.html';

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE ID_RENT=$id";
    $result = $xoopsDB->query($sql);

    [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc, $id_user, $id_cd] = $xoopsDB->fetchRow($result);

    if (0 != $id) {
        $date_rent_tmp = $date_rent;

        $date_back_tmp = $date_back;

        $today = date('Y') . date('m') . date('d') . date('H') . date('i');

        $date_rent = mb_substr($date_rent, 0, 4) . '/' . mb_substr($date_rent, 4, 2) . '/' . mb_substr($date_rent, 6, 2) . ' ' . mb_substr($date_rent, 8, 2) . ':' . mb_substr($date_rent, 10, 2);

        $date_back = mb_substr($date_back, 0, 4) . '/' . mb_substr($date_back, 4, 2) . '/' . mb_substr($date_back, 6, 2) . ' ' . mb_substr($date_back, 8, 2) . ':' . mb_substr($date_back, 10, 2);

        $user_name = reference('users', 'name', 'uid', $id_user);

        $cd_name = reference($module_tables[1], 'name', 'ID_CD', $id_cd);

        $cd_desc = reference($module_tables[1], 'description', 'ID_CD', $id_cd);

        $cd_key = reference($module_tables[1], 'cdkey', 'ID_CD', $id_cd);

        $cd_number = reference($module_tables[1], 'number', 'ID_CD', $id_cd);

        $copy = reference($module_tables[1], 'copy', 'ID_CD', $id_cd);

        $cd_noseq = reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd);

        $id_cat = reference($module_tables[1], 'id_cat', 'ID_CD', $id_cd);

        $id_cd = reference($module_tables[1], 'id_cd', 'ID_CD', $id_cd);

        $prefix = reference($module_tables[0], 'prefix', 'ID_CAT', $id_cat);

        $cd_name .= _MA_CM_RESDESC_TEXTCD . ' ' . $cd_noseq;

        if ($xoopsUser->isAdmin()) {
            $isAdmin = 1;
        } else {
            $isAdmin = 0;
        }

        if (1 == $copy) {
            $cd_name .= _MA_CM_RESDESC_TEXTCOPY;
        }

        $xoopsTpl->assign('restitle', _MA_CM_RESDESC_RESTITLE);

        $xoopsTpl->assign('cdtitle', _MA_CM_RESDESC_CDTITLE);

        $xoopsTpl->assign('date_rent', $date_rent);

        $xoopsTpl->assign('date_back', $date_back);

        $xoopsTpl->assign('user_name', $user_name);

        $xoopsTpl->assign('cd_name', $cd_name);

        $xoopsTpl->assign('id_cd', $id_cd);

        $xoopsTpl->assign('cd_desc', $cd_desc);

        $xoopsTpl->assign('cdkey', $cd_key);

        $xoopsTpl->assign('cd_number', $prefix . $cd_number);

        $xoopsTpl->assign('desc', $myts->displayTarea($desc, 1, 0, 1, 0));

        $xoopsTpl->assign('user_name', $user_name);

        $xoopsTpl->assign('isAdmin', $isAdmin);

        $xoopsTpl->assign('textno', _MA_CM_RESDESC_TEXTNO);

        $xoopsTpl->assign('textname', _MA_CM_RESDESC_TEXTNAME);

        $xoopsTpl->assign('textnoseq', _MA_CM_RESDESC_TEXTNOSEQ);

        $xoopsTpl->assign('textcopy', _MA_CM_RESDESC_TEXTCOPY);

        $xoopsTpl->assign('textcd', _MA_CM_RESDESC_TEXTCD);

        $xoopsTpl->assign('textcddesc', _MA_CM_RESDESC_TEXTCDDESC);

        $xoopsTpl->assign('textcdkey', _MA_CM_RESDESC_TEXTCDKEY);

        $xoopsTpl->assign('textdesc', _MA_CM_RESDESC_TEXTNOTE);

        $xoopsTpl->assign('textdatestart', _MA_CM_RESDESC_TEXTDATESTART);

        $xoopsTpl->assign('textdateend', _MA_CM_RESDESC_TEXTDATEEND);

        $xoopsTpl->assign('textuser', _MA_CM_RESDESC_TEXTUSER);

        if (2 == $status && ($today >= $date_rent_tmp && $today <= $date_back_tmp)) {
            $xoopsTpl->assign('seeCdKey', '1');
        } else {
            $xoopsTpl->assign('seeCdKey', '0');
        }
    } else {
        redirect_header('index.php', 3, _MA_CM_NONID);
    }

    include 'footer.php';
