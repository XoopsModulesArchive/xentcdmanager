<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables, $xoopsUser;

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_cd_done.html';

    $id_cd = $_POST['id'];
    $id_user = $xoopsUser->getVar('uid');
    $id_group = $_POST['id_group'];

    $day_start = formatOneDigitIntoTwoDigit($_POST['cd_reserve_start_day']);
    $month_start = formatOneDigitIntoTwoDigit($_POST['cd_reserve_start_month']);
    $year_start = formatOneDigitIntoTwoDigit($_POST['cd_reserve_start_year']);
    $hour_start = formatOneDigitIntoTwoDigit($_POST['cd_reserve_starthour']);
    $minute_start = formatOneDigitIntoTwoDigit($_POST['cd_reserve_startminute']);

    $day_end = formatOneDigitIntoTwoDigit($_POST['cd_reserve_end_day']);
    $month_end = formatOneDigitIntoTwoDigit($_POST['cd_reserve_end_month']);
    $year_end = formatOneDigitIntoTwoDigit($_POST['cd_reserve_end_year']);
    $hour_end = formatOneDigitIntoTwoDigit($_POST['cd_reserve_endhour']);
    $minute_end = formatOneDigitIntoTwoDigit($_POST['cd_reserve_endminute']);

    $desc = $_POST['desc'];

    $date_start = (string)$year_start . (string)$month_start . (string)$day_start . (string)$hour_start . (string)$minute_start;
    $date_end = (string)$year_end . (string)$month_end . (string)$day_end . (string)$hour_end . (string)$minute_end;
    $date_now = date('Y') . date('m') . date('d') . date('H') . date('i');

    #echo "test";
    #echo $_POST['cd_group_checkbox'];
    #echo $id_group;
    if (!empty($_POST['cd_group_checkbox'])) {
        $reserveGroup = $_POST['cd_group_checkbox'];
    } else {
        $reserveGroup = 0;
    }

    $sqlIsRent = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE id_cd=$id_cd
    	AND (status <> 3)
    	AND ((date_rent <= $date_start AND date_back > $date_start)
        OR (date_rent <= $date_end AND date_back > $date_end))";

    $resultIsRent = $xoopsDB->query($sqlIsRent);
    [$ID_RENT] = $xoopsDB->fetchRow($resultIsRent);
    $date_returned = '';

    $bodyMsg = '';
    if (($date_start <= $date_end) && ($date_start >= $date_now && $date_end >= $date_now)) {
        if (!empty($ID_RENT)) {
            // ici, l'usager ne peut réserver le cd à pendant le laps de temps qu'il désire

            $xoopsTpl->assign('res_title', _MA_CM_DONE_ERRORCANTRESERVE);
        } else {
            $sql = 'SELECT MAX(res_group) AS max_res_group FROM ' . $xoopsDB->prefix($module_tables[2]);

            $result = $xoopsDB->query($sql);

            $max_res_group = $xoopsDB->fetchArray($result);

            if (empty($max_res_group['max_res_group'])) {
                $max_res_group['max_res_group'] = 0;
            }

            $res_group = (int)($max_res_group['max_res_group'] + 1);

            if (0 == $reserveGroup) {
                $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[2]) . " (date_rent, date_back, date_returned, status, description, id_user, id_cd, isPrint, emailSent, res_group) VALUES($date_start, $date_end, '$date_returned', 1, '$desc', $id_user, $id_cd, 0, 0, $res_group)";

                $result = $xoopsDB->query($sql);

                $copy = reference($module_tables[1], 'copy', 'ID_CD', $id_cd);

                if (1 == $copy) {
                    $copy = _MA_CM_PRINT_TEXTCOPY;
                } else {
                    $copy = '';
                }

                $cd_name = reference($module_tables[1], 'name', 'ID_CD', $id_cd) . _MA_CM_PRINT_TEXTCD . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy;

                $user_name = reference('users', 'uname', 'uid', $id_user);

                $cd_number = reference($module_tables[1], 'number', 'ID_CD', $id_cd);

                $date_start = mb_substr($date_start, 0, 4) . '/' . mb_substr($date_start, 4, 2) . '/' . mb_substr($date_start, 6, 2) . ' ' . mb_substr($date_start, 8, 2) . ':' . mb_substr($date_start, 10, 2);

                $date_end = mb_substr($date_end, 0, 4) . '/' . mb_substr($date_end, 4, 2) . '/' . mb_substr($date_end, 6, 2) . ' ' . mb_substr($date_end, 8, 2) . ':' . mb_substr($date_end, 10, 2);

                $bodyMsg .= _MA_CM_PRINT_COMMAND . "\n\n" . _MA_CM_PRINT_USERNAME . $user_name . "\n" . _MA_CM_PRINT_CDNO . $cd_number . "\n" . _MA_CM_PRINT_CDNAME . $cd_name . "\n" . _MA_CM_PRINT_CDDATESTART . "$date_start\n" . _MA_CM_PRINT_CDDATEBACK . (string)$date_end;
            } else {
                $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group = $id_group";

                $result1 = $xoopsDB->query($sql1);

                $bodyMsg .= _MA_CM_PRINT_COMMAND;

                while (false !== ($group = $xoopsDB->fetchRow($result1))) {
                    $id_cd = $group[0];

                    $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[2]) . " (date_rent, date_back, date_returned, status, description, id_user, id_cd, isPrint, emailSent, res_group) VALUES($date_start, $date_end, '$date_returned', 1, '$desc', $id_user, $id_cd, 0, 0, $res_group)";

                    $result = $xoopsDB->query($sql);

                    $copy = reference($module_tables[1], 'copy', 'ID_CD', $id_cd);

                    if (1 == $copy) {
                        $copy = _MA_CM_PRINT_TEXTCOPY;
                    } else {
                        $copy = '';
                    }

                    $cd_name = reference($module_tables[1], 'name', 'ID_CD', $id_cd) . _MA_CM_PRINT_TEXTCD . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy;

                    $user_name = reference('users', 'uname', 'uid', $id_user);

                    $cd_number = reference($module_tables[1], 'number', 'ID_CD', $id_cd);

                    $date_start_t = mb_substr($date_start, 0, 4) . '/' . mb_substr($date_start, 4, 2) . '/' . mb_substr($date_start, 6, 2) . ' ' . mb_substr($date_start, 8, 2) . ':' . mb_substr($date_start, 10, 2);

                    $date_end_t = mb_substr($date_end, 0, 4) . '/' . mb_substr($date_end, 4, 2) . '/' . mb_substr($date_end, 6, 2) . ' ' . mb_substr($date_end, 8, 2) . ':' . mb_substr($date_end, 10, 2);

                    $bodyMsg .= "\n\n" . _MA_CM_PRINT_USERNAME . $user_name . "\n" . _MA_CM_PRINT_CDNO . $cd_number . "\n" . _MA_CM_PRINT_CDNAME . $cd_name . "\n" . _MA_CM_PRINT_CDDATESTART . "$date_start_t\n" . _MA_CM_PRINT_CDDATEBACK . (string)$date_end_t;
                }
            }

            // envoi du bon de commande par email

            $email = reference('users', 'email', 'uid', $id_user);

            $xoopsMailer = getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setToEmails($xoopsModuleConfig['adminemail']);

            #$xoopsMailer->setFromEmail($email);

            $xoopsMailer->setFromName(reference('users', 'uname', 'uid', $id_user));

            $xoopsMailer->setSubject(_MA_CM_DONE_MAILSUBJECT);

            $xoopsMailer->setBody($bodyMsg);

            $xoopsMailer->send();

            $sql2 = 'SELECT ID_RENT FROM ' . $xoopsDB->prefix($module_tables[2]) . ' ORDER BY ID_RENT DESC LIMIT 1';

            $result2 = $xoopsDB->query($sql2);

            $rent_result2 = $xoopsDB->fetchRow($result2);

            $id_rent = $rent_result2[0];

            $xoopsTpl->assign('msg', _MA_CM_DONE_RESAPPROVED);

            $xoopsTpl->assign('print', "<a href='print.php?id=$id_rent' target='_blank'>" . _MA_CM_DONE_PRINT . '</a>');
        }
    } else {
        redirect_header("cd_reserve.php?id=$id_cd", 5, _MA_CM_DONE_ERRORENDHIGHERTHANSTART);
    }

    include 'footer.php';
