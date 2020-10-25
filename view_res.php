<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/kernel/groupperm.php';
    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_view_res.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $id = $_GET['id'];

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD = $id";
    $result = $xoopsDB->query($sql);
    [$ID_CD, $name, $number, $desc, $nogroup, $copy, $status, $id_cat, $id_group] = $xoopsDB->fetchRow($result);

    if ($permObject->checkRight('category_read', $id_cat, $xoopsUser->getGroups(), $xoopsModule->mid())) {
        $cd_name = $name . _MA_CM_VIEWDESC_TEXTCD . $nogroup;

        if (1 == $copy) {
            $cd_name .= _MA_CM_VIEWDESC_TEXTCOPY;
        }

        $prefix = reference($module_tables[0], 'prefix', 'ID_CAT', $id_cat);

        $cd_name .= ' (' . $prefix . $number . ')';

        if (!empty($_GET['month'])) {
            if ($_GET['month'] <= 12 && $_GET['month'] >= 1) {
                $month = $_GET['month'];
            } else {
                $month = date('m');
            }
        } else {
            $month = date('m');
        }

        if (!empty($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $year = date('Y');
        }

        if (!empty($_GET['rd'])) {
            $rd = $_GET['rd'];
        } else {
            $rd = $xoopsModuleConfig['resview_display'];
        }

        if (!empty($_POST['year_box'])) {
            $year = $_POST['year_box'];

            redirect_header("view_res.php?id=$id&month=1&year=$year", 1, 'Changement de l\'année');
        }

        $month_start = mktime(0, 0, 0, $month, 1, $year);

        $month_text = date('F', $month_start);

        $tableau = buildResMonthCalendar($month, $year, $id, $rd);

        $monthMenu = buildResMonthMenu($year, $id);

        $xoopsTpl->assign('cdReturned', "<div class='cdReturnedLegend'>" . _MA_CM_VIEWRES_CDRETURNED . '</div>');

        $xoopsTpl->assign('cdLateNApproved', "<div class='cdLateNApprovedLegend'>" . _MA_CM_VIEWRES_CDLATENAPPROVED . '</div>');

        $xoopsTpl->assign('cdLateApproved', "<div class='cdLateApprovedLegend'>" . _MA_CM_VIEWRES_CDLATEAPPROVED . '</div>');

        $xoopsTpl->assign('cdToComeNApproved', "<div class='cdToComeNApprovedLegend'>" . _MA_CM_VIEWRES_CDTOCOMENAPPROVED . '</div>');

        $xoopsTpl->assign('cdToComeApproved', "<div class='cdToComeApprovedLegend'>" . _MA_CM_VIEWRES_CDTOCOMEAPPROVED . '</div>');

        $xoopsTpl->assign('legend', _MA_CM_VIEWRES_LEGEND);

        $xoopsTpl->assign('month_text', $month_text);

        $xoopsTpl->assign('year_text', $year);

        $xoopsTpl->assign('cd', $cd_name);

        $xoopsTpl->assign('tableau', $tableau);

        $xoopsTpl->assign('monthMenu', $monthMenu);

        include 'footer.php';
    } else {
        redirect_header('index.php', 5, _MA_CM_NORIGHTS);
    }
