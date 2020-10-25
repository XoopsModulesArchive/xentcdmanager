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
    echo $oAdminButton->renderButtons('admingroup');

    function GRAdminGroup()
    {
        OpenTable();

        echo "<div class='adminHeader'>" . _AM_CM_GR_ADMIN_ADMINGROUP . '</div>';

        GRAddGroup(_AM_CM_GR_ADMIN_ADDGROUP);

        buildGrActionMenu();

        CloseTable();
    }

    function GRAddGroup($title)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $sform = new XoopsThemeForm($title, 'addgroup', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_CM_GR_ADD_GROUPTEXT, 'group', 50, 255));

        $sform->addElement(new XoopsFormTextArea(_AM_CM_GR_ADD_DESC, 'desc'));

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement(new XoopsFormButton('', 'add', _AM_CM_GR_ADD_ADD, 'submit'));

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', 'GRSaveGr'));

        $sform->display();
    }

    function GRSaveGr($name, $desc)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        if ('' != $name) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[3]) . "(name, description) VALUES ('$name', '$desc')";

            $result = $xoopsDB->query($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('admingroup.php?op=GrAdminGroup', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('admingroup.php?op=GRAdminGroup', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('admingroup.php?op=GRAdminGroup', 5, _AM_CM_GR_ADD_ERRORNONAME);
        }
    }

    function GRListGroup()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        OpenTable();

        echo "<table width='100%' class='outer' cellspacing='1'>
        	<tr>
            	<th>" . _AM_CM_GR_LIST_NAME . "</th>
                <th colspan='2'>" . _AM_CM_GR_LIST_DESC . '</th>
            </tr>';

        // va chercher tous les groupes

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[3]) . ' ORDER by name';

        $result = $xoopsDB->query($sql);

        [$ID_GROUP, $name, $desc] = $xoopsDB->fetchRow($result);

        while (!empty($ID_GROUP)) {
            echo "
                <tr valign='top' align='left'>
                    <td class='head'><a href='admingroup.php?op=GRDescGroup&id=$ID_GROUP'><img src='../images/display.gif'></a><a href='admingroup.php?op=GREditGroup&id=$ID_GROUP'><img src='../images/edit.gif'></a>&nbsp;&nbsp;&nbsp;" . $myts->displayTarea($name, 1, 0, 1, 0) . "</td>
                    <td class='even'>" . $myts->displayTarea($desc, 1, 0, 1, 0) . '</td>
                </tr>
            ';

            [$ID_GROUP, $name, $desc] = $xoopsDB->fetchRow($result);
        }

        echo '</table><br>';

        buildGrActionMenu();

        CloseTable();
    }

    function GRDescGroup()
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

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[3]) . " WHERE ID_GROUP=$id";

            $result = $xoopsDB->query($sql);

            $sql1 = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group=$id";

            $result1 = $xoopsDB->query($sql1);

            $sql2 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group=$id";

            $result2 = $xoopsDB->query($sql2);

            [$ID_GROUP, $name, $desc] = $xoopsDB->fetchRow($result);

            [$cd_count] = $xoopsDB->fetchRow($result1);

            echo "<table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'>" . _AM_CM_GR_DESC_TITLE . '</th></tr>';

            echo "
                <tr valign='top' align='left'>
                    <td class='head' width='20%'>" . _AM_CM_GR_DESC_NAME . "</td>
                    <td class='even'>" . $myts->displayTarea($name, 1, 0, 1, 0) . "</td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='20%'>" . _AM_CM_GR_DESC_DESC . "</td>
                    <td class='even'>" . $myts->displayTarea($desc, 1, 0, 1, 0) . "</td>
                </tr>
                 <tr valign='top' align='left'>
                    <td class='head' width='20%'>" . _AM_CM_GR_DESC_NUMBEROFCD . '(' . $cd_count . ") : </td>
                    <td class='even'>";

            while (false !== ($cd_in_group = $xoopsDB->fetchArray($result2))) {
                if (1 == $cd_in_group['copy']) {
                    $cd_in_group['copy'] = ' (' . _AM_CM_GR_DESC_COPYTEXT . ')';
                } else {
                    $cd_in_group['copy'] = '';
                }

                echo $cd_in_group['name'] . ' - CD ' . $cd_in_group['nogroup'] . $cd_in_group['copy'] . '<br>';
            }

            echo '</td>
                </tr>
            ';

            echo '</table><br>';

            buildGrActionMenu();

            CloseTable();
        } else {
            redirect_header('admingroup.php?op=GRListGroup', 5, _AM_CM_GR_LIST_ERRORIDNONCONFORM);
        }
    }

    function GREditGroup()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        if (!empty($_GET['id']) || !empty($_POST['id'])) {
            OpenTable();

            if (!empty($_GET['id'])) {
                $id = $_GET['id'];
            } elseif (!empty($_POST['id'])) {
                $id = $_POST['id'];
            }

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[3]) . " WHERE ID_GROUP=$id";

            $result = $xoopsDB->query($sql);

            [$ID_GROUP, $name, $desc] = $xoopsDB->fetchRow($result);

            echo "<form name='modgroup' id='modcd' action='admingroup.php' method='post' onsubmit='return xoopsFormValidate_modgroup();' enctype='multipart/form-data'><table width='100%' class='outer' cellspacing='1'><tr><th colspan='2'>" . _AM_CM_GR_MODIFY_TITLE . '</th></tr>';

            echo "
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_GR_MODIFY_NAME . "</td>
                    <td class='even'><input type='text' name='name' id='name' size='50' maxlength='255' value='$name'></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head' width='10%'>" . _AM_CM_GR_MODIFY_DESC . "</td>
                    <td class='even'><textarea name='desc' id='desc' rows='5' cols='50'>$desc</textarea></td>
                </tr>
                <tr valign='top' align='left'>
                    <td class='head'></td>
                    <td class='even'>
                        <input type='hidden' name='id' id='id' value='$ID_GROUP'>
                        <input type='hidden' name='op' id='op' value=''>
                        <input type='submit' class='formButton' name='mod'  id='mod' value='" . _AM_CM_GR_MODIFY_MODIFY . "'  onmouseover='document.modcd.op.value=\"GRSaveModifGroup\"'>
                        <input type='submit' class='formButton' name='del'  id='del' value='" . _AM_CM_GR_DEL_DEL . "'  onmouseover='document.modcd.op.value=\"GRAreYouSureToDelete\"'>
                    </td>
                </tr>
            ";

            echo '</table></form>';

            buildGrActionMenu();

            CloseTable();
        } else {
            redirect_header('admingroup.php?op=GRListGr', 5, _AM_CM_GR_LIST_ERRORIDNONCONFORM);
        }
    }

    function GRSaveModifGroup($id_group, $name, $desc)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[3]) . " SET name='$name', description='$desc' WHERE ID_GROUP=$id_group";

        $result = $xoopsDB->query($sql);

        if (0 == $xoopsDB->errno()) {
            redirect_header('admingroup.php?op=GRListGroup', 3, _AM_CM_DB_UPDATED);
        } else {
            redirect_header('admingroup.php?op=GRListGroup', 5, $xoopsDB->error());
        }
    }

    function GRAreYouSureToDelete($id, $name, $desc)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        echo "
        	<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'>
            	<tr class='bg4'>
                	<td valign='top'>
            	        <form name='delgroup' id='modcat' action='admingroup.php' method='post' onsubmit='return xoopsFormValidate_delgroup();' enctype='multipart/form-data'>
	                        <table width='100%' class='outer' cellspacing='1'>
	                            <tr>
	                                <th colspan='2'>" . _AM_CM_GR_DEL_CONFIRM . "</th>
	                            </tr>
                                <tr valign='top' align='left'>
                                	<td class='head' width='35%'>" . _AM_CM_GR_DEL_NAME . "</td>
                                    <td class='even'>$name</td>
                                </tr>
	                            <tr valign='top' align='left'>
	                                <td class='head'>" . _AM_CM_GR_DEL_AREYOUSURE . "</td>
	                                <td class='even'>
                                    	<input type='hidden' name='id' id='id' value='$id'>
                                        <input type='hidden' name='name' id='name' value='$name'>
                                        <input type='hidden' name='desc' id='desc' value='$desc'>

	                            		<input type='hidden' name='op' id='op' value=''>
                                    	<input type='submit' class='formButton' name='yes'  id='yes' value='" . _AM_CM_GR_DEL_YES . "' onmouseover='document.delgroup.op.value=\"GRDeleteGroup\"'>
                                        <input type='submit' class='formButton' name='no'  id='no' value='" . _AM_CM_GR_DEL_NO . "' onmouseover='document.delgroup.op.value=\"GREditGroup\"'>
                                    </td>
	                            </tr>
                            </form>
                        </table><br>";

        buildGrActionMenu();

        echo '</td>
                </tr>
            </table>
        ';
    }

    function GRDeleteGroup($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $sql = 'DELETE FROM ' . $xoopsDB->prefix($module_tables[3]) . " WHERE ID_GROUP=$id";

        $sql1 = 'SELECT ID_CD FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group=$id";

        $result1 = $xoopsDB->query($sql1);

        [$ID_CD] = $xoopsDB->fetchRow($result1);

        if (empty($ID_CD)) {
            $result = $xoopsDB->query($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('admingroup.php', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('admingroup.php', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('admingroup.php?op=GRListGroup', 5, _AM_CM_DR_DEL_CANNOTDEL);
        }
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

    $op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'GRAdminGroup':
            GRAdminGroup();
            break;
        case 'GRSaveGr':
            GRSaveGr($group, $desc);
            break;
        case 'GRListGroup':
            GRListGroup();
            break;
        case 'GRDescGroup':
            GRDescGroup();
            break;
        case 'GREditGroup':
            GREditGroup();
            break;
        case 'GRSaveModifGroup':
            GRSaveModifGroup($id, $name, $desc);
            break;
        case 'GRAreYouSureToDelete':
            GRAreYouSureToDelete($id, $name, $desc);
            break;
        case 'GRDeleteGroup':
            GRDeleteGroup($id);
            break;
        default:
            GRAdminGroup();
            break;
    }

    // *************************** Fin de NTS **********************************

     xoops_cp_footer();
