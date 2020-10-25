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

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_scat_list.html';

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = -1;
    }

    if (-1 != $id) {
        if ($permObject->checkRight('category_read', $id, $xoopsUser->getGroups(), $xoopsModule->mid())) {
            if (!empty($_GET['tri'])) {
                $tri = $_GET['tri'];
            } else {
                $tri = 0;
            }

            // sql pour aller chercher les cd de la cat

            $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_cat=$id AND status=1 ";

            // sql pour aller cherche les scat de la cat courante

            $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE id_cat_parent=$id ";

            switch ($tri) {
                // par id/numéro
                case 1:
                    $sql1 .= 'ORDER BY ID_CAT';
                    $sql .= 'ORDER BY number';
                    break;
                // par nom
                case 2:
                    $sql1 .= 'ORDER BY name';
                    $sql .= 'ORDER BY name';
                    break;
                //par préfix/groupe
                case 3:
                    $sql1 .= 'ORDER BY prefix';
                    $sql .= 'ORDER BY language';
                    break;
                default:
                    $sql1 .= 'ORDER BY ID_CAT';
                    $sql .= 'ORDER BY number';
            }

            $result = $xoopsDB->query($sql);

            while (false !== ($cd = $xoopsDB->fetchArray($result))) {
                if (!empty($cd['ID_CD'])) {
                    // pour aller chercher si le cd est loué ou non

                    $icd = $cd['ID_CD'];

                    $sql2 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE id_cd=$icd AND status=1 ORDER BY date_rent ASC";

                    $result2 = $xoopsDB->query($sql2);

                    if ($permObject->checkRight('category_read', $cd['id_cat'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
                        if (0 == $cd['id_group']) {
                            $cd['group'] = '';
                        } else {
                            $cd['group'] = reference($module_tables[3], 'name', 'ID_GROUP', $cd['id_group']);
                        }

                        if (0 == $cd['copy']) {
                            $cd['copy'] = '';
                        } else {
                            $cd['copy'] = _MA_CM_SCATLIST_COPYTEXT;
                        }

                        $cd['prefix'] = reference($module_tables[0], 'prefix', 'ID_CAT', $cd['id_cat']);

                        $xoopsTpl->append('cd', $cd);

                        $xoopsTpl->assign('hasCd', 1);
                    }
                } else {
                    $xoopsTpl->assign('hasCd', 0);
                }
            }

            $result1 = $xoopsDB->query($sql1);

            while (false !== ($cat_data = $xoopsDB->fetchArray($result1))) {
                if (!empty($cat_data['ID_CAT'])) {
                    if ($permObject->checkRight('category_read', $cat_data['ID_CAT'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
                        // trouver le nombre de cd dans la cat

                        $id_cat = $cat_data['ID_CAT'];

                        $countcd = getNumberOfCdInCat($id_cat, 0, true);

                        $cat_data['name'] .= ' (' . $countcd . ' cd)';

                        $cat_data['name'] = $myts->displayTarea($cat_data['name'], 1, 0, 1, 0);

                        $cat_data['prefix'] = $myts->displayTarea($cat_data['prefix'], 1, 0, 1, 0);

                        $xoopsTpl->append('cat_data_list', $cat_data);

                        $xoopsTpl->assign('hasSubCat', 1);
                    }
                } else {
                    $xoopsTpl->assign('hasSubCat', 0);
                }
            }

            $catname = reference($module_tables[0], 'name', 'ID_CAT', $id);

            // Pour afficher le chemin en haut du tableau ex (INDEX | ODESIA | PROGRAMMES|)

            $a = [];

            $a = getCatTree($id);

            $path = "| <a href='index.php'>" . _MA_CM_INDEX_CATTREEROOT . '</a> | ';

            for ($x = count($a) - 1; $x >= 0; $x--) {
                $path .= $myts->displayTarea($a[$x], 1, 0, 1, 0) . ' | ';
            }

            // FIN

            $cd_title = _MA_CM_SCATLIST_CDLIST;

            $xoopsTpl->assign('viewRes', _MA_CM_SCATLIST_VIEWRES);

            $xoopsTpl->assign('editCd', _MA_CM_SCATLIST_EDITCD);

            $xoopsTpl->assign('isAdmin', $xoopsUser->isAdmin());

            $xoopsTpl->assign('path', $path);

            $xoopsTpl->assign('cd_title', $cd_title);

            $xoopsTpl->assign('month', date('m'));

            $xoopsTpl->assign('year', date('Y'));

            $xoopsTpl->assign('idcatparent', $id);

            $xoopsTpl->assign('isPrefix', $xoopsModuleConfig['isPrefix']);

            $xoopsTpl->assign('textid', _MA_CM_SCATLIST_IDTEXT);

            $xoopsTpl->assign('textname', _MA_CM_SCATLIST_NAMETEXT);

            $xoopsTpl->assign('textdesc', _MA_CM_SCATLIST_DESCTEXT);

            $xoopsTpl->assign('textprefix', _MA_CM_SCATLIST_PREFIXTEXT);

            $xoopsTpl->assign('textlang', _MA_CM_SCATLIST_LANGTEXT);

            $xoopsTpl->assign('textrent', _MA_CM_SCATLIST_RENTTEXT);

            $xoopsTpl->assign('optionrent', _MA_CM_SCATLIST_OPTIONSTEXT);

            $xoopsTpl->assign('textno', _MA_CM_SCATLIST_NOTEXT);

            $xoopsTpl->assign('hasNoSubCatText', _MA_CM_SCATLIST_HASNOSUBCATTEXT);

            $xoopsTpl->assign('hasNoCdText', _MA_CM_SCATLIST_HASNOCD);

            include 'footer.php';
        } else {
            redirect_header('index.php', 5, _MA_CM_NORIGHTS);
        }
    } else {
        redirect_header('index.php', 5, _MA_CM_NORIGHTS);
    }
