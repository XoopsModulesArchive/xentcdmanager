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

    $GLOBALS['xoopsOption']['template_main'] = 'xentcdmanager_search.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $searchtext = $_GET['search_box'];
    $searchcat = $_GET['search_cat'];
    $tmpSearchText = $searchtext;

    $arr = [];

    $count = 0;

    // on sépare la string dans un array
    while ('' != $tmpSearchText) {
        $pos = mb_strpos($tmpSearchText, ' ');

        $arr[$count] = mb_substr($tmpSearchText, 0, $pos);

        if (empty($pos)) {
            $arr[$count] = $tmpSearchText;

            break;
        }

        $tmpSearchText = mb_substr($tmpSearchText, mb_strpos($tmpSearchText, ' ') + 1);

        $count++;
    }

    if ('' == $tmpSearchText) {
        $arr[0] = '';
    }

    $field = '';
    $table = '';
    // ici on prend tous les enregistrements qui contiennent le premier mot tappé

    switch ($searchcat) {
        // par nom
        case 0:
            $field = 'name';
            $table = $module_tables[1];
            break;
        // par numéro
        case 1:
            $field = 'number';
            $table = $module_tables[1];
            break;
        // par catégorie
        case 2:
            $field = 'name';
            $table = $module_tables[0];
            break;
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($table) . ' WHERE ';

    for ($x = 0; $x <= $count; $x++) {
        if ($x != $count) {
            $sql .= " INSTR($field, '$arr[$x]') AND ";
        } else {
            $sql .= " INSTR($field, '$arr[$x]') ";
        }
    }

    switch ($searchcat) {
        // par nom
        case 0:
            $sql .= 'ORDER BY name, nogroup ASC';
            break;
        // par numéro
        case 1:
            $sql .= 'ORDER BY CAST(number AS SIGNED), name, nogroup ASC';
            break;
        // par catégorie
        case 2:
            $sql .= 'ORDER BY name ASC';
            break;
    }

    if (!empty($_GET['start'])) {
        $start = $_GET['start'];
    } else {
        $start = 1;
    }

    $result = $xoopsDB->query($sql);
    #$number_records_total = $xoopsDB->getRowsNum($result);
    $number_records_total = 0;

    $rec_display = $xoopsModuleConfig['records_display'];

    #$start--;
    #$sql .= " LIMIT $start, $rec_display";
    #$start++;

    #$result = $xoopsDB->query($sql);
    #$number_records = $xoopsDB->getRowsNum($result);

    while (false !== ($cd_search = $xoopsDB->fetchArray($result))) {
        $bool = false;

        if ($permObject->checkRight('category_read', $cd_search['id_cat'], $xoopsUser->getGroups(), $xoopsModule->mid())) {
            $number_records_total++;

            if (2 == $searchcat) {
                $cd_search['ID'] = $cd_search['ID_CAT'];

                if (1 == $xoopsModuleConfig['isPrefix']) {
                    $cd_search['number'] = $cd_search['prefix'];
                }

                $cd_search['page'] = 'scat_list';

                $cd_search['copy'] = '';

                $cd_search['nogroup'] = '';

                $cd_search['cdtext'] = '';
            } else {
                $cd_search['ID'] = $cd_search['ID_CD'];

                if (1 == $xoopsModuleConfig['isPrefix']) {
                    $cd_search['number'] = reference($module_tables[0], 'prefix', 'ID_CAT', $cd_search['id_cat']) . $cd_search['number'];
                } else {
                    $cd_search['number'] = $cd_search['number'];
                }

                $cd_search['page'] = 'cd_desc';

                if (1 == $cd_search['copy']) {
                    $cd_search['copy'] = _MA_CM_SEARCH_COPYTEXT;
                } else {
                    $cd_search['copy'] = '';
                }

                $cd_search['cdtext'] = _MA_CM_SEARCH_CDTEXT;
            }

            $cd_search['name'] = highlightSearchWords($cd_search['name'], $arr);

            $xoopsTpl->append('cd_search', $cd_search);
        }
    }

    // nombre de pages
    /*$page = array();
    for ($x=1; $x<=(ceil($number_records_total/$rec_display)); $x++){
        $tmpSearchText = str_replace(" ","+",$searchtext);

        if ($x == 1){
            $page['p'] = "<a href=search.php?search_box=$tmpSearchText&search_cat=$searchcat>";
        } else {
            $start_t = ($x*$rec_display)-$rec_display + 1;
            $page['p'] =  "<a href=search.php?search_box=$tmpSearchText&search_cat=$searchcat&start=$start_t>";
        }

        if (intval($start/$rec_display)+1 == $x){
            $page['p'] .= "<font color='#c65f2a'>Page $x</font></a>&nbsp;&nbsp;";
        } else {
            $page['p'] .= "Page $x</a>&nbsp;&nbsp;";
        }

        $xoopsTpl->append('page', $page);
    }

    $temp = intval(($start/$rec_display)+1);
    $number_records_dis = $rec_display * $temp;

    if ($number_records_dis > $number_records_total){
        $number_records_dis = $number_records_total;
    }*/

    #$xoopsTpl->assign('resultstext', _MA_CM_SEARCH_RESULTS." (".$start." - ".$number_records_dis." de ".$number_records_total.")");
    $xoopsTpl->assign('resultstext', _MA_CM_SEARCH_RESULTS . ' (' . $number_records_total . ')');

    $xoopsTpl->assign('searchtitle', _MA_CM_SEARCH_TITLE);
    $xoopsTpl->assign('searchtext', _MA_CM_SEARCH_SEARCHTEXT);
    $xoopsTpl->assign('searchword', $searchtext);
    include 'footer.php';
