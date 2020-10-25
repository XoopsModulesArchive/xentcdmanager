<?php

    include '../../mainfile.php';
    require __DIR__ . '/include/functions.php';

    echo '<title>' . _MA_CM_PRINT_PRINT . '</title>';

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

    $id = $_GET['id'];

    $res_gr = reference($module_tables[2], 'res_group', 'ID_RENT', $id);

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE res_group=$res_gr";
    $result = $xoopsDB->query($sql);
    [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc_rent, $id_user_rent, $id_cd_rent, $isPrint, $res_group] = $xoopsDB->fetchRow($result);

    #if ($isPrint == 0){

        while (!empty($ID_RENT)) {
            $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id_cd_rent";

            $result1 = $xoopsDB->query($sql1);

            [$ID_CD, $name_cd, $no_cd, $desc_cd, $nogroup_cd, $copy_cd, $status, $id_cat_cd, $id_group_cd] = $xoopsDB->fetchRow($result1);

            if (1 == $copy_cd) {
                $copy = _MA_CM_PRINT_TEXTCOPY;
            } else {
                $copy = '';
            }

            $cd_name = $name_cd . _MA_CM_PRINT_TEXTCD . $nogroup_cd . $copy;

            $cd_number = $no_cd;

            $date_start = mb_substr($date_rent, 0, 4) . '/' . mb_substr($date_rent, 4, 2) . '/' . mb_substr($date_rent, 6, 2) . ' ' . mb_substr($date_rent, 8, 2) . ':' . mb_substr($date_rent, 10, 2);

            $date_end = mb_substr($date_back, 0, 4) . '/' . mb_substr($date_back, 4, 2) . '/' . mb_substr($date_back, 6, 2) . ' ' . mb_substr($date_back, 8, 2) . ':' . mb_substr($date_back, 10, 2);

            $user_name = reference('users', 'uname', 'uid', $id_user_rent);

            echo "
	            <table width='100%'>
	                <tr>
	                    <td>
	                        <br><br>
	                        <table width='50%' align='left' border='1' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' cellpadding='2'>
	                            <tr>
	                                <td>
	                                    <font size='4'><center>" . _MA_CM_PRINT_COMMAND . '</center></font><br>' .
                                        _MA_CM_PRINT_USERNAME . "
	                                    <b>$user_name</b><br><br>" .
                                        _MA_CM_PRINT_CDNO . "
	                                    <b>$cd_number</b><br>" .
                                        _MA_CM_PRINT_CDNAME . "
	                                    <b>$cd_name</b><br>" .
                                        _MA_CM_PRINT_CDDATESTART . "
	                                    $date_start<br>" .
                                        _MA_CM_PRINT_CDDATEBACK . "
	                                    $date_end<br>" .
                                    '</td>
	                            </tr>
	                        </table>
	                    </td>
	                </tr>
	        ';

            // pour dire qu'il a bien fait apparaitre son bon de réservation

            $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[2]) . " SET isPrint=1 WHERE ID_RENT=$ID_RENT";

            $xoopsDB->queryF($sql);

            [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc_rent, $id_user_rent, $id_cd_rent, $isPrint, $res_group] = $xoopsDB->fetchRow($result);
        }

         echo "
	            <tr>
	                <td>
	                    <br><br>
	                    <form> <input name='B1' onclick='window.print();' type='button' value='";
                            echo _MA_CM_PRINT_PRINT;
                        echo "'></form>
	                </td>
	            </tr>
	        </table>
	     ";
    #} else {
    #	echo _MA_CM_PRINT_NOACCESS;
    #}
