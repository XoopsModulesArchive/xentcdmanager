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

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_cd_reserve.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id";

        $result = $xoopsDB->query($sql);

        $cd = $xoopsDB->fetchArray($result);

        if ($permObject->checkRight('category_read', $cd['id_cat'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
            if (0 == $cd['copy']) {
                $cd['copy'] = '';
            } else {
                $cd['copy'] = _MA_CM_CDRESERVE_COPY;
            }

            $cd['prefix'] = reference($module_tables[0], 'prefix', 'ID_CAT', $cd['id_cat']);

            $xoopsTpl->append('cd', $cd);

            $postuler = new XoopsThemeForm('', 'postuler', 'done.php');

            $postuler->setExtra("enctype='multipart/form-data'"); //de xoops-doc

            $postuler->addElement(new XoopsFormHidden('id', $id));

            $postuler->addElement(new XoopsFormHidden('id_group', $cd['id_group']));

            $postuler->addElement(makeDaySelect('cd_reserve_start_day', _MA_CM_CDRESERVE_STARTDATE));

            $postuler->addElement(makeMonthSelect('cd_reserve_start_month'));

            $postuler->addElement(makeYearSelect('cd_reserve_start_year', '', (int)date('Y'), (int)date('Y') + 1));

            $postuler->addElement(new XoopsFormText(_MA_CM_CDRESERVE_STARTHOUR, 'cd_reserve_starthour', 3, 2, (int)date('H') + 1));

            $postuler->addElement(new XoopsFormText('', 'cd_reserve_startminute', 3, 2, '00'));

            $postuler->addElement(makeDaySelect('cd_reserve_end_day', _MA_CM_CDRESERVE_ENDDATE));

            $postuler->addElement(makeMonthSelect('cd_reserve_end_month'));

            $postuler->addElement(makeYearSelect('cd_reserve_end_year', '', (int)date('Y'), (int)date('Y') + 1));

            $postuler->addElement(new XoopsFormText(_MA_CM_CDRESERVE_ENDHOUR, 'cd_reserve_endhour', 3, 2, (int)date('H') + 2));

            $postuler->addElement(new XoopsFormText('', 'cd_reserve_endminute', 3, 2, '00'));

            if (0 != $cd['id_group']) {
                $chk = new XoopsFormCheckBox('', 'cd_group_checkbox');

                $chk->addOption(1, _MA_CM_CDRESERVE_GROUPRESERVE);

                $postuler->addElement($chk);
            }

            $postuler->addElement(new XoopsFormLabel('<br>'));

            $postuler->addElement(new XoopsFormTextArea(_MA_CM_CDRESERVE_NOTE, 'desc'));

            $postuler->addElement(new XoopsFormButton('Reserve', 'add', _MA_CM_CDRESERVE_RESERVE, 'submit'));

            $xoopsTpl->assign('res_text1', _MA_CM_CDRESERVE_RESTEXT1);

            $xoopsTpl->assign('res_text2', _MA_CM_CDRESERVE_RESTEXT2);

            $xoopsTpl->assign('isRent', 'no');

            $postuler->assign($xoopsTpl);

            $xoopsTpl->assign('res_title', _MA_CM_CDRESERVE_TITLE);
        } else {
            redirect_header('index.php', 5, _MA_CM_NORIGHTS);
        }
    } else {
        redirect_header('index.php', 3, _MA_CM_NONID);
    }

    include 'footer.php';
