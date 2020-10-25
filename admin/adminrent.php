<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentcdmanager/class/xoopstopic.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('adminrent');

    function RentAdmin()
    {
        global $xoopsDB, $module_tables, $xoopsModuleConfig;

        OpenTable();

        echo "<div class='adminHeader'>" . _AM_CM_RENT_ADMIN_ADMINRENT . '</div><br>';

        $today = date('Y') . date('m') . date('d') . date('H') . date('i');

        if (!empty($_GET['rescat'])) {
            $res_cat = $_GET['rescat'];
        } else {
            $res_cat = 2;
        }

        if (!empty($_GET['start'])) {
            $start = $_GET['start'];
        } else {
            $start = 0;
        }

        $sql = '';

        $title = '';

        $number_records = 0;

        $rec_display = $xoopsModuleConfig['records_display'];

        switch ($res_cat) {
            // cd en retard et non-approuvé
            case 1:
                $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE STATUS=1 AND date_back < $today";
                $title = _AM_CM_RENT_ADMIN_CDLATENAPPROVED;
                break;
            // cd en retard et approuvé
            case 2:
                $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE STATUS=2 AND date_back < $today";
                $title = _AM_CM_RENT_ADMIN_CDLATEAPPROVED;
                break;
            // cd remis et approuvé
            case 3:
                $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . ' WHERE STATUS=3';
                $title = _AM_CM_RENT_ADMIN_CDRETURNED;
                break;
            // cd à venir/en cours approuvé
            case 4:
                $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE STATUS=2 AND date_back >= $today";
                $title = _AM_CM_RENT_ADMIN_CDTOCOMEAPPROVED;
                break;
            // cd à venir/en cours non-approuvé
            case 5:
                $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE STATUS=1 AND date_back >= $today";
                $title = _AM_CM_RENT_ADMIN_CDTOCOMENAPPROVED;
                break;
        }

        $result = $xoopsDB->query($sql);

        $number_records = $xoopsDB->getRowsNum($result);

        $sql .= " ORDER BY DATE_BACK ASC LIMIT $start, " . $rec_display;

        RentShowRents($title, $sql, $res_cat, $number_records);

        buildRentActionMenu();

        CloseTable();
    }

    function RentShowRents($title, $sql, $res_cat, $number_records)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $result = $xoopsDB->query($sql);

        [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $description, $id_user, $id_cd, $isPrint, $emailRent] = $xoopsDB->fetchRow($result);

        echo " <table width='100%' class='outer' cellspacing='1'>
            <tr>
                <th colspan='2'>" . $title . '</th>';

        if (2 == $res_cat) {
            echo '<th>' . _AM_CM_RENT_ADMIN_EMAILSENT . '</th>';
        }

        echo '</tr> ';

        while (!empty($ID_RENT)) {
            $date_rent = mb_substr($date_rent, 0, 4) . '/' . mb_substr($date_rent, 4, 2) . '/' . mb_substr($date_rent, 6, 2) . ' ' . mb_substr($date_rent, 8, 2) . ':' . mb_substr($date_rent, 10, 2);

            $date_back = mb_substr($date_back, 0, 4) . '/' . mb_substr($date_back, 4, 2) . '/' . mb_substr($date_back, 6, 2) . ' ' . mb_substr($date_back, 8, 2) . ':' . mb_substr($date_back, 10, 2);

            echo "
                	<tr>
                    	<td class='head' width='35%'>$date_rent&nbsp;&nbsp;" . _AM_CM_RENT_ADMIN_TO . "&nbsp;&nbsp;$date_back</td>";

            $copy = reference($module_tables[1], 'copy', 'ID_CD', $id_cd);

            echo "<td class='even' width='53%'><a href='adminrent.php?op=RentReturnRent&id=$ID_RENT'><img src='../images/return.gif'></img></a>";

            if (0 == $copy) {
                $copy = '';
            } else {
                $copy = _AM_CM_RENT_ADMIN_COPYTEXT;
            }

            if (1 == $res_cat || 2 == $res_cat || 4 == $res_cat) {
                if (2 == $res_cat) {
                    echo "<a href='adminrent.php?op=RentEmailLateRent&id=$ID_RENT'><img src='../images/email.gif'></img></a>";
                }

                if (4 == $res_cat) {
                    echo "<a href='adminrent.php?op=RentUnRentRent&id=$ID_RENT'><img src='../images/unrent.gif'></img></a>";
                }

                echo '&nbsp;&nbsp;&nbsp;' . reference($module_tables[1], 'number', 'ID_CD', $id_cd) . ' - ' . reference($module_tables[1], 'name', 'ID_CD', $id_cd) . _AM_CM_RENT_ADMIN_CDTEXT . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy . '<b> (' . reference('users', 'uname', 'uid', $id_user) . ')</b></td>';
            } elseif (5 == $res_cat) {
                echo "<a href='adminrent.php?op=RentCdApproval&id=$ID_RENT'><img src='../images/rent.gif'></img></a>&nbsp;&nbsp;&nbsp;" . reference($module_tables[1], 'number', 'ID_CD', $id_cd) . ' - ' . reference($module_tables[1], 'name', 'ID_CD', $id_cd) . _AM_CM_RENT_ADMIN_CDTEXT . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy . '<b> (' . reference('users', 'uname', 'uid', $id_user) . ')</b></td>';
            } else {
                echo "<td class='even'>&nbsp;&nbsp;&nbsp;" . reference($module_tables[1], 'number', 'ID_CD', $id_cd) . ' - ' . reference($module_tables[1], 'name', 'ID_CD', $id_cd) . _AM_CM_RENT_ADMIN_CDTEXT . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy . '<b> (' . reference('users', 'uname', 'uid', $id_user) . ')</b></td>';
            }

            if (2 == $res_cat) {
                if (1 == $emailRent) {
                    echo "<td class='even'><center>x</center></td>";
                } else {
                    echo "<td class='even'><center></center></td>";
                }
            }

            echo '</tr>
                ';

            [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $description, $id_user, $id_cd, $isPrint, $emailRent] = $xoopsDB->fetchRow($result);
        }

        echo '</table>';

        $rec_display = $xoopsModuleConfig['records_display'];

        // nombre de pages

        for ($x = 1; $x <= (ceil($number_records / $rec_display)); $x++) {
            if (1 == $x) {
                echo "<a href=adminrent.php?rescat=$res_cat>";
            } else {
                echo "<a href=adminrent.php?rescat=$res_cat&start=" . (($x * $rec_display) - $rec_display) . '>';
            }

            echo _AM_CM_RENT_ADMIN_PAGE . " $x</a>&nbsp;&nbsp;";
        }

        echo '<br><br>';
    }

    function RentReturnRent()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];

            $today = date('Y') . date('m') . date('d') . date('H') . date('i');

            $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[2]) . " SET date_returned='$today', status=3 WHERE ID_RENT=$id";

            $result = $xoopsDB->queryF($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('adminrent.php', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('adminrent.php', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('adminrent.php', 5, _AM_CM_DB_NONCONFORMID);
        }
    }

    function RentUnRentRent()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];

            $today = date('Y') . date('m') . date('d') . date('H') . date('i');

            $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[2]) . " SET date_returned='$today', status=1 WHERE ID_RENT=$id";

            $result = $xoopsDB->queryF($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('adminrent.php', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('adminrent.php', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('adminrent.php', 5, _AM_CM_DB_NONCONFORMID);
        }
    }

    function RentCdApproval()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];

            $today = date('Y') . date('m') . date('d') . date('H') . date('i');

            $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[2]) . " SET status=2 WHERE ID_RENT=$id";

            $result = $xoopsDB->queryF($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('adminrent.php', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('adminrent.php', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('adminrent.php', 5, _AM_CM_DB_NONCONFORMID);
        }
    }

    function RentEmailLateRent($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[2]) . " SET emailSent=1 WHERE ID_RENT=$id";

        $result = $xoopsDB->queryF($sql);

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE ID_RENT=$id";

        $result = $xoopsDB->query($sql);

        [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $description, $id_user, $id_cd, $isPrint, $emailRent] = $xoopsDB->fetchRow($result);

        $copy = reference($module_tables[1], 'copy', 'ID_CD', $id_cd);

        if (1 == $copy) {
            $copy = _AM_CM_RENT_ADMIN_COPYTEXT;
        } else {
            $copy = '';
        }

        $user_name = reference('users', 'uname', 'uid', $id_user);

        $cd_name = reference($module_tables[1], 'number', 'ID_CD', $id_cd);

        $cd_name .= ' - ' . reference($module_tables[1], 'name', 'ID_CD', $id_cd);

        $cd_name .= _AM_CM_RENT_ADMIN_CDTEXT . reference($module_tables[1], 'nogroup', 'ID_CD', $id_cd) . $copy;

        $bodyMsg = _MA_CM_RENT_ADMIN_MAILTEXT1 . "$user_name, ";

        $bodyMsg .= "\n\n" . _MA_CM_RENT_ADMIN_MAILTEXT2 . $cd_name;

        $bodyMsg .= "\n\n" . _MA_CM_RENT_ADMIN_MAILTEXT3 . "\n\n" . $xoopsModuleConfig['adminemail'];

        $xoopsMailer = getMailer();

        $xoopsMailer->useMail();

        $xoopsMailer->setToEmails($xoopsModuleConfig['adminemail']);

        #$xoopsMailer->setFromEmail($email);

        $xoopsMailer->setFromName(reference('users', 'uname', 'uid', $id_user));

        $xoopsMailer->setSubject(_MA_CM_RENT_ADMIN_MAILSUBJECT);

        $xoopsMailer->setBody($bodyMsg);

        $xoopsMailer->send();

        RentAdmin();
    }

    function RentCleanDB()
    {
        global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        OpenTable();

        $postuler = new XoopsThemeForm(_MA_CM_RENT_CLEAN_TITLE, 'cleanDB', 'adminrent.php?op=RentAreYouSureCleanDB');

        $postuler->addElement(makeDaySelect('day', _MA_CM_RENT_CLEAN_DAY));

        $postuler->addElement(makeMonthSelect('month', _MA_CM_RENT_CLEAN_MONTH));

        $postuler->addElement(makeYearSelect('year', _MA_CM_RENT_CLEAN_YEAR, (int)date('Y'), (int)date('Y') + 1));

        $postuler->addElement(new XoopsFormButton('', 'Go', _MA_CM_RENT_CLEAN_GO, 'submit'));

        $postuler->display();

        buildRentActionMenu();

        CloseTable();
    }

    function RentAreYouSureCleanDB($day, $month, $year)
    {
        global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        OpenTable();

        echo "
	    <table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'>
	        <tr class='bg4'>
	            <td valign='top'>
	                <form name='delrent' id='modcat' action='adminrent.php' method='post' onsubmit='return xoopsFormValidate_delrent();' enctype='multipart/form-data'>
	                    <table width='100%' class='outer' cellspacing='1'>
	                        <tr>
	                            <th colspan='2'>" . _AM_CM_RENT_CLEAN_CONFIRM . "</th>
	                        </tr>
	                        <tr valign='top' align='left'>
	                            <td class='head'>" . _AM_CM_RENT_CLEAN_AREYOUSURE . formatOneDigitIntoTwoDigit($day) . '/' . formatOneDigitIntoTwoDigit($month) . '/' . $year . "</td>
	                            <td class='even'>
	                                <input type='hidden' name='day' id='day' value='$day'>
                                    <input type='hidden' name='month' id='month' value='$month'>
                                    <input type='hidden' name='year' id='year' value='$year'>

	                                <input type='hidden' name='op' id='op' value=''>
	                                <input type='submit' class='formButton' name='yes'  id='yes' value='" . _AM_CM_RENT_CLEAN_YES . "' onmouseover='document.delrent.op.value=\"RentDoneCleanDB\"'>
	                                <input type='submit' class='formButton' name='no'  id='no' value='" . _AM_CM_RENT_CLEAN_NO . "' onmouseover='document.delrent.op.value=\"RentCleanDB\"'>
	                            </td>
	                        </tr>
	                    </form>
	                </table><br>";

        buildRentActionMenu();

        echo '</td>
	        </tr>



	    </table>
	    ';

        CloseTable();
    }

    function RentDoneCleanDB($day, $month, $year)
    {
        global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $day = formatOneDigitIntoTwoDigit($day);

        $month = formatOneDigitIntoTwoDigit($month);

        $year = $year;

        $sql = 'DELETE FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE LEFT(date_back,8) <='$year$month$day';";

        $xoopsDB->query($sql);

        echo $sql;

        if (0 == $xoopsDB->errno()) {
            redirect_header('adminrent.php', 3, _AM_CM_DB_UPDATED);
        } else {
            redirect_header('adminrent.php', 5, $xoopsDB->error());
        }
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

    $op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
        case 'RentReturnRent':
            RentReturnRent();
            break;
        case 'RentUnRentRent':
            RentUnRentRent();
            break;
        case 'RentCdApproval':
            RentCdApproval();
            break;
        case 'RentEmailLateRent':
            RentEmailLateRent($_GET['id']);
            break;
        case 'RentCleanDB':
            RentCleanDB();
            break;
        case 'RentAreYouSureCleanDB':
            RentAreYouSureCleanDB($day, $month, $year);
            break;
        case 'RentDoneCleanDB':
            RentDoneCleanDB($day, $month, $year);
            break;
        default:
            RentAdmin();
            break;
    }

    // *************************** Fin de NTS **********************************

     xoops_cp_footer();
