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
    echo $oAdminButton->renderButtons('admincd');

    function CDAdminCd()
    {
        OpenTable();

        echo "<div class='adminHeader'>" . _AM_CM_CD_ADMIN_ADMINCD . '</div>';

        CDAddCd(_AM_CM_CD_ADMIN_ADDCD);

        buildCdActionMenu();

        CloseTable();
    }

    function CDAddCd($title)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        // S'il y a erreur ... le formulaire est réaffiché avec les infos préalablement rentré

        if (!empty($_GET['name'])) {
            $name = $_GET['name'];
        } else {
            $name = '';
        }

        if (!empty($_GET['desc'])) {
            $desc = $myts->displayTarea($_GET['desc']);
        } else {
            $desc = '';
        }

        if (!empty($_GET['no'])) {
            $no = $_GET['no'];
        } else {
            $no = $xoopsModuleConfig['cdno'];
        }

        if (!empty($_GET['nogroup'])) {
            $nogroup = $_GET['nogroup'];
        } else {
            $nogroup = '1';
        }

        if (!empty($_GET['lang'])) {
            $lang = $_GET['lang'];
        } else {
            $lang = '';
        }

        if (!empty($_GET['datep'])) {
            $datep = $_GET['datep'];
        } else {
            $datep = '';
        }

        if (!empty($_GET['cdkey'])) {
            $cdkey = $_GET['cdkey'];
        } else {
            $cdkey = '';
        }

        if (!empty($_GET['cop'])) {
            $copy = $_GET['cop'];
        } else {
            $copy = '';
        }

        if (!empty($_GET['status'])) {
            $status = $_GET['status'];
        } else {
            $status = 1;
        }

        if (!empty($_GET['id_cat'])) {
            $id_cat = $_GET['id_cat'];
        } else {
            $id_cat = '';
        }

        if (!empty($_GET['id_group'])) {
            $id_group = $_GET['id_group'];
        } else {
            $id_group = '';
        }

        echo "

        	<form name='addcd' id='addcdt' action='admincd.php' method='post' onsubmit='return xoopsFormValidate_addcd();' enctype='multipart/form-data'>
	            <table width='100%' class='outer' cellspacing='1'>
	                <tr>
	                    <th colspan='2'>" . _AM_CM_CD_ADMIN_ADDCD . "</th>
	                </tr>
	                <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_NAME . "</td>
	                    <td class='even'><input type='text' name='name' id='name' size='50' maxlength='600' value='$name'></td>
	                </tr>
	                <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_DESC . "</td>
	                    <td class='even'><textarea name='desc' id='desc' rows='5' cols='50'>" . $desc . "</textarea></td>
	                </tr>
	                <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_NO . "</td>
	                    <td class='even'><input type='text' name='no' id='no' size='10' maxlength='255' value='$no'></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_NOGROUP . "</td>
	                    <td class='even'><input type='text' name='nogroup' id='no' size='10' maxlength='255' value='$nogroup'></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_LANGUAGE . "</td>
	                    <td class='even'><input type='text' name='lang' id='lang' size='30' maxlength='255'/ value='$lang'></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_DATEP . "</td>
	                    <td class='even'><input type='text' name='date_p' id='date_p' size='30' maxlength='255'/ value='$datep'></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_CDKEY . "</td>
	                    <td class='even'><textarea name='cdkey' id='cdkey' rows='5' cols='50'>$cdkey</textarea></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_COPY . "</td>
	                    <td class='even'><input type='checkbox' name='copy'/ ";

        if (1 == $copy) {
            echo 'checked';
        }

        echo "></td>
	                </tr>
                    <tr valign='top' align='left'>
	                    <td class='head'>" . _AM_CM_CD_ADD_STATUS . "</td>
	                    <td class='even'><input type='checkbox' name='status' ";

        if (1 == $status) {
            echo 'checked';
        }

        echo "></td>
	                </tr>
	                <tr>
	                    <td class='head' width='15%'>" . _AM_CM_CD_ADD_CATTEXT . "</td>
	                    <td class='even'>";

        if (!empty($id_cat)) {
            CDDisplayCatTree(false, $id_cat);
        } else {
            CDDisplayCatTree();
        }

        echo "</td>
	                </tr>
	                <tr>
	                    <td class='head' width='15%'>" . _AM_CM_CD_ADD_GROUP . "</td>
	                    <td class='even'>";

        if (!empty($id_group)) {
            CDDisplayGroupSelect($id_group);
        } else {
            CDDisplayGroupSelect();
        }

        echo "</td>
	                </tr>
	                <tr valign='top' align='left'>
	                    <td class='head'></td>
	                    <td class='even'><input type='submit' class='formButton' name='add'  id='add' value='" . _AM_CM_CAT_ADD_ADD . "'></td>
	                </tr>
	                <input type='hidden' name='op' id='op' value='CDSaveAddCd'>
	            </table>
    		</form>
        ";
    }

    function CDDisplayCatTree($selected = false, $id_cat = -1)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        if ($selected) {
            $xt = new XoopsTopic($xoopsDB->prefix($module_tables[0]));

            $xt->makeTopicSelBox(0, -1, 'id_cat', '');
        } else {
            //$xt = new XoopsTopic( $xoopsDB -> prefix($module_tables[0]), $_POST['id_cat'] );

            $xt = new XoopsTopic($xoopsDB->prefix($module_tables[0]));

            // patch parce le topic_pid ne se set pas ......

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_CAT=$id_cat";

            $result = $xoopsDB->query($sql);

            [$ID_CAT, $name, $description, $prefix, $id_cat_parent] = $xoopsDB->fetchRow($result);

            // fin de la patch

            //normalement, a la place de $id_cat_parent, ca devraie etre $xt->topic_pid()

            $xt->makeTopicSelBox(1, $id_cat, 'id_cat', '');
        }
    }

    function CDDisplayGroupSelect($selected_id = -1)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[3]) . ' ORDER BY name';

        $result = $xoopsDB->query($sql);

        [$ID_GROUP, $name] = $xoopsDB->fetchRow($result);

        echo "<select name='id_group'>";

        if (-1 == $selected_id) {
            echo "<option value='0' selected>----</option>";

            while (!empty($ID_GROUP)) {
                echo "<option value='$ID_GROUP'>" . $myts->displayTarea($name, 1, 0, 1, 0) . '</option>';

                [$ID_GROUP, $name] = $xoopsDB->fetchRow($result);
            }
        } else {
            echo "<option value='0'>----</option>";

            while (!empty($ID_GROUP)) {
                if ($selected_id == $ID_GROUP) {
                    echo "<option value='$ID_GROUP' selected>" . $myts->displayTarea($name, 1, 0, 1, 0) . '</option>';
                } else {
                    echo "<option value='$ID_GROUP'>" . $myts->displayTarea($name, 1, 0, 1, 0) . '</option>';
                }

                [$ID_GROUP, $name] = $xoopsDB->fetchRow($result);
            }
        }

        echo '</select>';
    }

    function CDSaveAddCd($name, $number, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        if (0 != $id_cat) {
            if ('' == $copy || 0 == $copy) {
                $copy = 0;
            } else {
                $copy = 1;
            }

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE number=$number";

            $result = $xoopsDB->query($sql);

            $cd_idDuplex = $xoopsDB->fetchRow($result);

            if (empty($cd_idDuplex[0])) {
                $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[1]) . "(name, number, description, nogroup, copy, status, id_cat, id_group, language, date_parution, cdkey) VALUES ('$name', '$number', '$desc', $nogroup, $copy, $status, $id_cat, $id_group, '$lang', '$date_p', '$cdkey')";

                $result = $xoopsDB->queryF($sql);

                if ($number == (int)$xoopsModuleConfig['cdno']) {
                    $no = (int)($xoopsModuleConfig['cdno'] + 1);

                    $modid = $xoopsModule->getVar('mid');

                    $sql = 'UPDATE ' . $xoopsDB->prefix('config') . " SET conf_value='$no' WHERE conf_modid=$modid AND conf_name='cdno'";

                    $result = $xoopsDB->queryF($sql);
                }

                if (0 == $xoopsDB->errno()) {
                    redirect_header("admincd.php?op=CDListCd&idc=$id_cat&start=0#$id_cat", 1, _AM_CM_DB_UPDATED);
                } else {
                    redirect_header('admincd.php?op=CDListCd', 5, $xoopsDB->error());
                }
            } else {
                $desc = htmlspecialchars($desc, ENT_QUOTES | ENT_HTML5);

                redirect_header("admincd.php?op=CDAdminCd&name=$name&desc=$desc&no=$number&nogroup=$nogroup&lang=$lang&datep=$date_p&cdkey=$cdkey&cop=$copy&status=$status&id_cat=$id_cat&id_group=$id_group", 3, _AM_CM_CD_ADD_NOEXISTS);
            }
        } else {
            redirect_header('admincd.php?op=CDAdminCd', 5, _AM_CM_CD_ADD_ERRORNOCAT);
        }
    }

    function CDListCd()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        if (!empty($_GET['start'])) {
            $start = $_GET['start'];
        } else {
            $start = 1;
        }

        if (!empty($_GET['idc'])) {
            $idc = $_GET['idc'];
        } else {
            $idc = 0;
        }

        if (!empty($_GET['tri'])) {
            $tri = $_GET['tri'];
        } else {
            $tri = 0;
        }

        $rec_display = $xoopsModuleConfig['records_display'];

        OpenTable();

        // va chercher toute les catégories

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]);

        $result = $xoopsDB->query($sql);

        [$ID_CAT, $name_cat, $desc_cat, $prefix_cat, $id_cat_parent] = $xoopsDB->fetchRow($result);

        while (!empty($ID_CAT)) {
            // va chercher tous les cd pour une catégorie donnée

            $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_cat=$ID_CAT";

            switch ($tri) {
                // par numéro
                case 0:
                    $sql1 .= ' ORDER BY number, copy, nogroup ASC ';

                    break;
                // par nom
                case 1:
                    $sql1 .= ' ORDER BY name, copy, nogroup ASC ';

                    break;
            }

            $result1 = $xoopsDB->query($sql1);

            $number_records = $xoopsDB->getRowsNum($result1);

            if ($ID_CAT == $idc || empty($idc)) {
                $start--;

                $sql1 .= " LIMIT $start, $rec_display";

                $start++;
            } else {
                $sql1 .= " LIMIT 0, $rec_display";
            }

            CDListShowCD($ID_CAT, $name_cat, $sql1, $number_records, $start, $tri, $idc);

            echo '<br><br><br>';

            [$ID_CAT, $name_cat, $desc_cat, $prefix_cat, $id_cat_parent] = $xoopsDB->fetchRow($result);
        }

        CloseTable();
    }

    function CDListShowCD($id_cat, $name_cat, $sql, $number_records, $start, $tri, $currentidc)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $result = $xoopsDB->query($sql);

        $rec_display = $xoopsModuleConfig['records_display'];

        $counttri = 0;

        echo "<table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'><a name='$id_cat'>" . $myts->displayTarea($name_cat, 1, 0, 1, 0) . ' (' . $number_records . ')</a></th></tr>';

        echo "<tr><td class='head' width='15%'><b><a href='admincd.php?op=CDListCd&counttri=$tri#$id_cat'>Numéro</a></b></td><td class='even'><b>";

        $counttri++;

        echo "<a href='admincd.php?op=CDListCd&tri=$counttri#$id_cat'>Nom</a></b></td></tr>";

        [$ID_CD, $name_cd, $number_cd, $desc_cd, $nogroup, $copy, $status, $id_cat_cd, $id_group_cd] = $xoopsDB->fetchRow($result);

        while (!empty($ID_CD)) {
            if (0 == $copy) {
                $copy = '';
            } elseif (1 == $copy) {
                $copy = 'Copie';
            }

            echo "
                <tr valign='top' align='left'>
                    <td class='head' width='15%'>" . $myts->displayTarea($prefix_cat, 1, 0, 1, 0) . $number_cd . '</td>';

            if (0 != $id_group_cd) {
                $num_cd_in_group = countCdInGroup($id_group_cd);

                echo "<td class='even'>$name_cd $copy&nbsp;(CD $nogroup de $num_cd_in_group)<br><br>";
            } else {
                echo "<td class='even'>$name_cd $copy&nbsp;(CD $nogroup)<br><br>";
            }

            echo "<a href='admincd.php?op=CDDescCd&id=$ID_CD'><img src='../images/display.gif'></a><a href='admincd.php?op=CDEditCd&id=$ID_CD&start=$start'><img src='../images/edit.gif'></a><a href='admincd.php?op=CDEditCd&id=$ID_CD&clone=1&start=$start'><img src='../images/clone.gif'></a></td>
                </tr>
            ";

            [$ID_CD, $name_cd, $number_cd, $desc_cd, $nogroup, $copy, $status, $id_cat_cd, $id_group_cd] = $xoopsDB->fetchRow($result);
        }

        echo "<tr><td colspan='2'>";

        buildCdActionMenu();

        echo '</td></tr></table><br>';

        // nombre de pages

        for ($x = 1; $x <= (ceil($number_records / $rec_display)); $x++) {
            if (1 == $x) {
                echo "<a href=admincd.php?op=CDListCd&idc=$id_cat&tri=$tri&start=1#$id_cat>";
            } else {
                $start_t = (($x * $rec_display) - $rec_display) + 1;

                echo "<a href=admincd.php?op=CDListCd&idc=$id_cat&start=" . $start_t . "&tri=$tri#$id_cat>";
            }

            if ((int)($start / $rec_display) + 1 == $x && $id_cat == $currentidc) {
                echo "<font color='#c65f2a'>" . _AM_CM_RENT_ADMIN_PAGE . " $x</font></a>&nbsp;&nbsp;";
            } else {
                echo _AM_CM_RENT_ADMIN_PAGE . " $x</a>&nbsp;&nbsp;";
            }
        }
    }

    function CDDescCd()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        if (!empty($_GET['id']) || !empty($_POST['id'])) {
            OpenTable();

            if (!empty($_GET['id'])) {
                $id = $_GET['id'];
            } elseif (!empty($_POST['id'])) {
                $id = $_POST['id'];
            }

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id";

            $result = $xoopsDB->query($sql);

            [$ID_CD, $name, $number, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey] = $xoopsDB->fetchRow($result);

            echo "<table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'>" . _AM_CM_CD_DESC_TITLE . '</th></tr>';

            echo "
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_NO . "</td>
                    <td class='even'>" . $myts->displayTarea(reference($module_tables[0], 'prefix', 'ID_CAT', $id_cat), 1, 0, 1, 0) . "$number</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_NAME . "</td>
                    <td class='even'>$name</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_DESC . "</td>
                    <td class='even'>" . $myts->displayTarea($desc, 1, 0, 1, 0) . "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_DESC_NOGROUP . "</td>
                    <td class='even'>CD $nogroup</td>
                </tr>
                 <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_LANGUAGE . "</td>
                    <td class='even'>$lang</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_DATEP . "</td>
                    <td class='even'>$date_p</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_CDKEY . "</td>
                    <td class='even'>$cdkey</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_DESC_COPY . "</td>
                    <td class='even'>";

            if (1 == $copy) {
                echo _AM_CM_CD_DESC_YESTEXT;
            } else {
                echo _AM_CM_CD_DESC_NOTEXT;
            }

            echo "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_DESC_STATUS . "</td>
                    <td class='even'>";

            if (1 == $status) {
                echo _AM_CM_CD_DESC_STATUSGOOD;
            } else {
                echo _AM_CM_CD_DESC_STATUSNGOOD;
            }

            echo "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='30%'>" . _AM_CM_CD_DESC_MEMBERGROUP . "</td>
                    <td class='even'>";

            echo reference('xent_cm_cd_group', 'name', 'ID_GROUP', $id_group);

            echo "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='30%'>" . _AM_CM_CD_DESC_MEMBERCAT . "</td>
                    <td class='even'>";

            echo $myts->displayTarea(reference('xent_cm_cat', 'name', 'ID_CAT', $id_cat), 1, 0, 1, 0);

            echo '</td>
                </tr>
            ';

            echo '</table><br>';

            buildCdActionMenu();

            CloseTable();
        } else {
            redirect_header('admincd.php?op=CDListCd', 5, _AM_CM_CD_LIST_ERRORIDNONCONFORM);
        }
    }

    function CDEditCd()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables, $xoopsModuleConfig;

        if (!empty($_GET['id']) || !empty($_POST['id'])) {
            OpenTable();

            if (!empty($_GET['id'])) {
                $id = $_GET['id'];
            } elseif (!empty($_POST['id'])) {
                $id = $_POST['id'];
            }

            if (!empty($_GET['start'])) {
                $start = $_GET['start'];
            } elseif (!empty($_POST['start'])) {
                $start = 0;
            }

            if (!empty($_GET['clone'])) {
                $clone = $_GET['clone'];
            } elseif (!empty($_POST['clone'])) {
                $clone = 0;
            }

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id";

            $result = $xoopsDB->query($sql);

            [$ID_CD, $name, $number, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey] = $xoopsDB->fetchRow($result);

            if (1 == $clone) {
                $number = (int)$xoopsModuleConfig['cdno'];
            }

            echo "<form name='modcd' id='modcd' action='admincd.php' method='post' onsubmit='return xoopsFormValidate_modcat();' enctype='multipart/form-data'><table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'>" . _AM_CM_CD_DESC_TITLE . '</th></tr>';

            echo "
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_NO . "</td>
                    <td class='even'><input type='text' name='no' id='no' size='50' maxlength='255' value='$number'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_NAME . "</td>
                    <td class='even'><input type='text' name='name' id='name' size='50' maxlength='600' value='$name'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_DESC . "</td>
                    <td class='even'><textarea name='desc' id='desc' rows='5' cols='50'>$desc</textarea></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_NOGROUP . "</td>
                    <td class='even'><input type='text' name='nogroup' id='no' size='10' maxlength='255' value='$nogroup'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_LANGUAGE . "</td>
                    <td class='even'><input type='text' name='lang' id='lang' size='30' maxlength='255' value='$lang'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_DATEP . "</td>
                    <td class='even'><input type='text' name='date_p' id='date_p' size='30' maxlength='255' value='$date_p'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_CDKEY . "</td>
                    <td class='even'><textarea name='cdkey' id='cdkey' rows='5' cols='50'>$cdkey</textarea></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_COPY . "</td>
                    <td class='even'><input type='checkbox' name='copy'";

            if (1 == $copy) {
                $copy = 1;

                echo 'checked ';
            } else {
                $copy = 0;
            }

            echo "></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'>" . _AM_CM_CD_ADD_STATUS . "</td>
                    <td class='even'><input type='checkbox' name='status' ";

            if (1 == $status) {
                echo 'checked';
            }

            echo "></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_MEMBERCAT . "</td>
                    <td class='even'>";

            CDDisplayCatTree(false, $id_cat);

            echo "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_CD_DESC_MEMBERGROUP . "</td>
                    <td class='even'>";

            CDDisplayGroupSelect($id_group);

            echo "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'></td>
                    <td class='even'>
                        <input type='hidden' name='id' id='id' value='$ID_CD'>
                        <input type='hidden' name='op' id='op' value=''>
                        <input type='hidden' name='start' id='start' value='$start'>
                        <input type='hidden' name='clone' id='clone' value='$clone'>";

            if (0 == $clone) {
                echo "<input type='submit' class='formButton' name='mod'  id='mod' value='" . _AM_CM_CD_MODIFY_MODIFY . "'  onmouseover='document.modcd.op.value=\"CDSaveModifCd\"'>";

                echo "<input type='submit' class='formButton' name='del'  id='del' value='" . _AM_CM_CD_DEL_DEL . "'  onmouseover='document.modcd.op.value=\"CDAreYouSureToDelete\"'>";
            } else {
                echo "<input type='submit' class='formButton' name='mod'  id='mod' value='" . _AM_CM_GR_CLONE_CLONE . "'  onmouseover='document.modcd.op.value=\"CDSaveModifCd\"'>";
            }

            echo '</td>
                </tr>
            ';

            echo '</table></form>';

            buildCdActionMenu();

            CloseTable();
        } else {
            redirect_header('admincd.php?op=CDListCd', 5, _AM_CM_CD_LIST_ERRORIDNONCONFORM);
        }
    }

    function CDSaveModifCd($id_cd, $name, $number, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey, $start, $clone)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables, $xoopsModuleConfig;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        if (0 != $id_cat) {
            if (1 != $clone) {
                $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[1]) . " SET name='$name', number='$number', description='$desc', nogroup=$nogroup, copy=$copy, status=$status, id_cat=$id_cat, id_group=$id_group, language='$lang', date_parution='$date_p', cdkey='$cdkey' WHERE ID_CD=$id_cd";

                $result = $xoopsDB->query($sql);
            } else {
                $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE number=$number";

                $result1 = $xoopsDB->query($sql1);

                $cd_idDuplex = $xoopsDB->fetchRow($result1);

                if (empty($cd_idDuplex[0])) {
                    $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[1]) . "(name, number, description, nogroup, copy, status, id_cat, id_group, language, date_parution, cdkey) VALUES ('$name', '$number', '$desc', $nogroup, $copy, $status, $id_cat, $id_group, '$lang', '$date_p', '$cdkey')";

                    $result = $xoopsDB->queryF($sql);

                    if ($number == (int)$xoopsModuleConfig['cdno']) {
                        $no = (int)($xoopsModuleConfig['cdno'] + 1);

                        $modid = $xoopsModule->getVar('mid');

                        $sql = 'UPDATE ' . $xoopsDB->prefix('config') . " SET conf_value='$no' WHERE conf_modid=$modid AND conf_name='cdno'";

                        $result = $xoopsDB->queryF($sql);
                    }

                    if (0 == $xoopsDB->errno()) {
                        redirect_header("admincd.php?op=CDListCd&idc=$id_cat&start=$start#$id_cat", 1, _AM_CM_DB_UPDATED);
                    } else {
                        redirect_header('admincd.php?op=CDListCd', 5, $xoopsDB->error());
                    }
                } else {
                    redirect_header('admincd.php?op=CDAdminCd', 3, _AM_CM_CD_ADD_NOEXISTS);
                }
            }

            if (0 == $xoopsDB->errno()) {
                redirect_header("admincd.php?op=CDListCd&idc=$id_cat&start=$start#$id_cat", 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('admincd.php?op=CDListCd', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('admincd.php?op=CDAdminCd', 5, _AM_CM_CD_ADD_ERRORNOCAT);
        }
    }

    function CDAreYouSureToDelete($id, $name, $number, $desc, $nogroup, $copy)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        if (1 == $copy) {
            $scopy = 'Copie';
        } else {
            $scopy = '';
        }

        echo "
        	<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'>
            	<tr class='bg4'>
                	<td valign='top'>
            	        <form name='delcd' id='modcat' action='admincd.php' method='post' onsubmit='return xoopsFormValidate_delcd();' enctype='multipart/form-data'>
	                        <table width='100%' class='outer' cellspacing='1'>
	                            <tr>
	                                <th colspan='2'>" . _AM_CM_CD_DEL_CONFIRM . "</th>
	                            </tr>
                                <tr valign='top' align='left'>
                                	<td class='head' width='35%'>" . _AM_CM_CAT_ADD_CATTEXT . "</td>
                                    <td class='even'>$name $scopy (CD $nogroup)</td>
                                </tr>
	                            <tr valign='top' align='left'>
	                                <td class='head'>" . _AM_CM_CD_DEL_AREYOUSURE . "</td>
	                                <td class='even'>
                                    	<input type='hidden' name='id' id='id' value='$id'>


	                            		<input type='hidden' name='op' id='op' value=''>
                                    	<input type='submit' class='formButton' name='yes'  id='yes' value='" . _AM_CM_CD_DEL_YES . "' onmouseover='document.delcd.op.value=\"CDDeleteCd\"'>
                                        <input type='submit' class='formButton' name='no'  id='no' value='" . _AM_CM_CD_DEL_NO . "' onmouseover='document.delcd.op.value=\"CDEditCd\"'>
                                    </td>
	                            </tr>
                            </form>
                        </table><br>";

        buildCdActionMenu();

        echo '</td>
                </tr>



            </table>
        ';
    }

    function CDDeleteCd($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $sql = 'DELETE FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE ID_CD=$id";

        $xoopsDB->query($sql);

        if (0 == $xoopsDB->errno()) {
            redirect_header('admincd.php', 3, _AM_CM_DB_UPDATED);
        } else {
            redirect_header('admincd.php', 5, $xoopsDB->error());
        }
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

    $op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'CDAdminCd':
            CDAdminCd();
            break;
        case 'CDSaveAddCd':
            if (empty($copy)) {
                $copy = 0;
            } else {
                $copy = 1;
            }

            if (empty($status)) {
                $status = 0;
            } else {
                $status = 1;
            }

            if (empty($status)) {
                $status = 0;
            }
            CDSaveAddCd($name, $no, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey);
            break;
        case 'CDListCd':
            CDListCd();
            break;
        case 'CDEditCd':
            CDEditCd();
            break;
        case 'CDDescCd':
            CDDescCd();
            break;
        case 'CDSaveModifCd':
            if (empty($copy)) {
                $copy = 0;
            } else {
                $copy = 1;
            }
            if (empty($status)) {
                $status = 0;
            } else {
                $status = 1;
            }

            CDSaveModifCd($id, $name, $no, $desc, $nogroup, $copy, $status, $id_cat, $id_group, $lang, $date_p, $cdkey, $start, $clone);
            break;
        case 'CDAreYouSureToDelete':
            if (empty($copy)) {
                $copy = 0;
            }
            CDAreYouSureToDelete($id, $name, $no, $desc, $nogroup, $copy);
            break;
        case 'CDDeleteCd':
            CDDeleteCd($id);
            break;
        case 'CDCloneCD':
            CDCloneCD();
            break;
        default:
            CDAdminCd();
            break;
    }

    // *************************** Fin de NTS **********************************

     xoops_cp_footer();
