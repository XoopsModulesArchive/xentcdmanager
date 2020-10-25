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
    echo $oAdminButton->renderButtons('admincat');

    function CMAdminCat()
    {
        OpenTable();

        echo "<div class='adminHeader'>" . _AM_CM_CAT_ADMIN_ADMINCAT . '</div>';

        CMAddCat(_AM_CM_CAT_ADMIN_ADDCATPRINC);

        CMAddSubCat(_AM_CM_CAT_ADMIN_ADDCATSUB);

        CMModifCat(_AM_CM_CAT_ADMIN_MODIFCAT);

        buildCatActionMenu();

        CloseTable();
    }

    //$princ = 1 ---> ajout d'une cat princ
    function CMAddCat($title)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $sform = new XoopsThemeForm($title, 'addcat', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_CM_CAT_ADD_CATTEXT, 'cat', 50, 255));

        $sform->addElement(new XoopsFormTextArea(_AM_CM_CAT_ADD_DESC, 'desc'));

        $sform->addElement(new XoopsFormText(_AM_CM_CAT_ADD_PREFIX, 'prefix', 10, 255));

        $sform->addElement(new XoopsFormHidden('id_cat', '0'));

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement(new XoopsFormButton('', 'add', _AM_CM_CAT_ADD_ADD, 'submit'));

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', 'CMSaveCat'));

        $sform->display();
    }

    function CMAddSubCat($title)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        echo "

            <form name='modcat' id='modcat' action='admincat.php' method='post' onsubmit='return xoopsFormValidate_modcat();' enctype='multipart/form-data'>
                <table width='100%' class='outer' cellspacing='1'>
                    <tr>
                        <th colspan='2'>" . _AM_CM_CAT_ADMIN_ADDCATSUB . "</th>
                    </tr>
                    <tr valign='top' align='left'>
                        <td class='head'>" . _AM_CM_CAT_ADD_CATTEXT . "</td>
                        <td class='even'><input type='text' name='cat' id='cat' size='50' maxlength='255' value=''></td>
                    </tr>
                    <tr valign='top' align='left'>
                        <td class='head'>" . _AM_CM_CAT_ADD_DESC . "</td>
                        <td class='even'><textarea name='desc' id='desc' rows='5' cols='50'></textarea></td>
                    </tr>
                    <tr valign='top' align='left'>
                        <td class='head'>" . _AM_CM_CAT_ADD_PREFIX . "</td>
                        <td class='even'><input type='text' name='prefix' id='prefix' size='10' maxlength='255' value=''></td>
                    </tr>
                    <tr>
                        <td class='head' width='15%'>" . _AM_CM_CAT_ADD_CATTEXT . "</td>
                        <td class='even'>";

        CMDisplayCatTree();

        echo "</td>
                    </tr>
                    <tr valign='top' align='left'>
                        <td class='head'></td>
                        <td class='even'><input type='submit' class='formButton' name='add'  id='add' value='" . _AM_CM_CAT_ADD_ADD . "'></td>
                    </tr>
                    <input type='hidden' name='op' id='op' value='CMSaveCat'>
                </table>
            </form>
        ";
    }

    function CMModifCat($title)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        echo "
            <form name='modcat' id='modcat' action='admincat.php' method='post' onsubmit='return xoopsFormValidate_modcat();' enctype='multipart/form-data'>
                <table width='100%' class='outer' cellspacing='1'>
                    <tr>
                        <th colspan='2'>" . _AM_CM_CAT_ADMIN_MODIFCAT . "</th>
                    </tr>
                    <tr>
                        <td class='head' width='15%'>" . _AM_CM_CAT_ADD_CATTEXT . "</td>
                        <td class='even'>";

        CMDisplayCatTree(true);

        echo "</td>
                    </tr>
                    <tr valign='top' align='left'>
                        <td class='head'></td>
                        <td class='even'>
                            <input type='submit' class='formButton' name='add'  id='add' value='" . _AM_CM_CAT_MODIFY_MODIFY . "'>
                        </td>
                    </tr>
                    <input type='hidden' name='op' id='op' value='CMEditCat'>
                </table>
            </form>
        ";
    }

    function CMSaveCat($cat, $desc, $id_cat_parent, $prefix)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        if (!empty($cat)) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[0]) . "(name, description, id_cat_parent, prefix) VALUES ('$cat', '$desc', $id_cat_parent, '$prefix')";

            $xoopsDB->query($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('admincat.php?op=CMAdminCat', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('admincat.php?op=CMAdminCat', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('admincat.php?op=CMAdminCat', 3, _AM_CM_DB_NOCATNAME);
        }
    }

    function CMSaveModifCat($id, $cat, $desc, $id_cat_parent, $prefix)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $desc = $myts->addSlashes($desc);

        if (!empty($cat)) {
            $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[0]) . " SET name='$cat', description = '$desc', id_cat_parent = $id_cat_parent, prefix='$prefix' WHERE ID_CAT=$id";

            if ($id != $id_cat_parent) {
                $xoopsDB->query($sql);

                if (0 == $xoopsDB->errno()) {
                    redirect_header('admincat.php', 3, _AM_CM_DB_UPDATED);
                } else {
                    redirect_header("admincat.php?op=CMAddcat&cat=$cat&desc=$desc&id_cat_parent=$id_cat_parent&prefix=$prefix", 5, $xoopsDB->error());
                }
            } else {
                redirect_header("admincat.php?op=CMAddcat&cat=$cat&desc=$desc&id_cat_parent=$id_cat_parent&prefix=$prefix", 5, _AM_CM_CAT_MODIFY_ERRORCATPARENT);
            }
        } else {
            redirect_header('admincat.php?op=CMAdminCat', 3, _AM_CM_DB_NOCATNAME);
        }
    }

    function CMDeleteCat($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $sql = 'SELECT ID_CAT FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE id_cat_parent = $id";

        $result = $xoopsDB->query($sql);

        [$ID_CAT] = $xoopsDB->fetchRow($result);

        $sql = 'SELECT ID_CD FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_cat = $id";

        $result = $xoopsDB->query($sql);

        [$ID_CD] = $xoopsDB->fetchRow($result);

        if (empty($ID_CAT) && empty($ID_CD)) {
            $sql = 'DELETE FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_CAT=$id";

            $xoopsDB->query($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('admincat.php', 3, _AM_CM_DB_UPDATED);
            } else {
                redirect_header('admincat.php', 5, $xoopsDB->error());
            }
        } else {
            redirect_header('admincat.php?op=CMAdminCat', 5, _AM_CM_CAT_DEL_CANNOTDEL);
        }
    }

    function CMEditCat()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . ' WHERE ID_CAT= ' . $_POST['id_cat'];

        $result = $xoopsDB->query($sql);

        [$ID_CAT, $name, $description, $prefix, $id_cat_parent] = $xoopsDB->fetchRow($result);

        echo "
        	<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'>
            	<tr class='bg4'>
                	<td valign='top'>
            	        <form name='modcat' id='modcat' action='admincat.php' method='post' onsubmit='return xoopsFormValidate_modcat();' enctype='multipart/form-data'>
	                        <table width='100%' class='outer' cellspacing='1'>
	                            <tr>
	                                <th colspan='2'>" . _AM_CM_CAT_ADMIN_MODIFCAT . "</th>
	                            </tr>
                                <tr valign='top' align='left'>
                                	<td class='head'>" . _AM_CM_CAT_MODIFY_CATTEXT . "</td>
                                    <td class='even'><input type='text' name='cat' id='cat' size='50' maxlength='255' value='";

        echo $name;

        echo "'></td>
                                </tr>
                                <tr valign='top' align='left'>
                                	<td class='head'>" . _AM_CM_CAT_MODIFY_DESC . "</td>
                                    <td class='even'><textarea name='desc' id='desc' rows='5' cols='50'>$description</textarea></td>
                                </tr>
                                <tr valign='top' align='left'>
                                	<td class='head'>" . _AM_CM_CAT_MODIFY_PREFIX . "</td>
                                    <td class='even'><input type='text' name='prefix' id='cat' size='20' maxlength='255' value='";

        echo $prefix;

        echo "'></td>
                                </tr>
	                            <tr>
	                                <td class='head' width='15%'>" . _AM_CM_CAT_MODIFY_CATPARENT . "</td>
	                                <td class='even'>";

        CMDisplayCatTree();

        echo "</td>
	                            </tr>
	                            <tr valign='top' align='left'>
	                                <td class='head'></td>
	                                <td class='even'>
                                    	<input type='hidden' name='id' id='id' value='$ID_CAT'>
                                		<input type='hidden' name='op' id='op' value=''>
                                    	<input type='submit' class='formButton' name='add'  id='add' value='" . _AM_CM_CAT_MODIFY_MODIFY . "'  onmouseover='document.modcat.op.value=\"CMSaveModifCat\"'>
                                        <input type='submit' class='formButton' name='add'  id='add' value='" . _AM_CM_CAT_DEL_DEL . "'  onmouseover='document.modcat.op.value=\"CMAreYouSureToDelete\"'>
                                    </td>
	                            </tr>
                            </form>
                        </table><br>";

        buildCatActionMenu();

        echo '</td>
                </tr>
            </table>
        ';
    }

    function CMDisplayCatTree($selected = false)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

        if (!empty($_POST['id_cat'])) {
            $id_cat = $_POST['id_cat'];
        } else {
            $id_cat = '';
        }

        if ($selected) {
            $xt = new XoopsTopic($xoopsDB->prefix($module_tables[0]));

            $xt->makeTopicSelBox(0, -1, 'id_cat', '');
        } else {
            $xt = new XoopsTopic($xoopsDB->prefix($module_tables[0]), $id_cat);

            // patch parce le topic_pid ne se set pas ......

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . ' WHERE ID_CAT=' . $id_cat;

            $result = $xoopsDB->query($sql);

            [$ID_CAT, $name, $description, $prefix, $id_cat_parent] = $xoopsDB->fetchRow($result);

            // fin de la patch

            //normalement, a la place de $id_cat_parent, ca devraie etre $xt->topic_pid()

            $xt->makeTopicSelBox(1, $id_cat_parent, 'id_cat', '');
        }
    }

    function CMAreYouSureToDelete($id, $name)
    {
        echo "
        	<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'>
            	<tr class='bg4'>
                	<td valign='top'>
            	        <form name='modcat' id='modcat' action='admincat.php' method='post' onsubmit='return xoopsFormValidate_modcat();' enctype='multipart/form-data'>
	                        <table width='100%' class='outer' cellspacing='1'>
	                            <tr>
	                                <th colspan='2'>" . _AM_CM_CAT_DEL_CONFIRM . "</th>
	                            </tr>
                                <tr valign='top' align='left'>
                                	<td class='head' width='35%'>" . _AM_CM_CAT_ADD_CATTEXT . "</td>
                                    <td class='even'>$name</td>
                                </tr>
	                            <tr valign='top' align='left'>
	                                <td class='head'>" . _AM_CM_CAT_DEL_AREYOUSURE . "</td>
	                                <td class='even'>
                                    	<input type='hidden' name='id' id='id' value='$id'>
	                            		<input type='hidden' name='op' id='op' value=''>
                                    	<input type='submit' class='formButton' name='yes'  id='yes' value='" . _AM_CM_CAT_DEL_YES . "' onmouseover='document.modcat.op.value=\"CMDeleteCat\"'>
                                        <input type='submit' class='formButton' name='no'  id='no' value='" . _AM_CM_CAT_DEL_NO . "' onmouseover='document.modcat.op.value=\"CMAdminCat\"'>
                                    </td>
	                            </tr>
                            </form>
                        </table><br>";

        buildCatActionMenu();

        echo '</td>
                </tr>
            </table>
        ';
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

    $op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
        case 'CMAdminCat':
            CMAdminCat();
            break;
        case 'CMAddCat':
            CMAddCat();
            break;
        case 'CMSaveCat':
        //	$id_cat c'est id_cat_parent
            CMSaveCat($cat, $desc, $id_cat, $prefix);
            break;
        case 'CMSaveModifCat':
            CMSaveModifCat($id, $cat, $desc, $id_cat, $prefix);
            break;
        case 'CMEditCat':
            CMEditCat();
            break;
        case 'CMDeleteCat':
            CMDeleteCat($id);
            break;
        case 'CMAreYouSureToDelete':
            CMAreYouSureToDelete($id, $cat);
            break;
        default:
            CMAdminCat();
            break;
    }

    // *************************** Fin de NTS **********************************

     xoops_cp_footer();
