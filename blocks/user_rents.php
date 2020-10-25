<?php

    require_once XOOPS_ROOT_PATH . '/modules/xentcdmanager/include/functions.php';

    function user_rents_show()
    {
        global $xoopsDB, $xoopsUser, $module_tables, $xoopsTpl;

        $url = XOOPS_URL . '/modules/xentcdmanager/';

        $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" media="all" href=' . $url . 'include/xentcdmanager.css>');

        $block = [];

        $uid = $xoopsUser->uid();

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xent_cm_rent') . " WHERE id_user=$uid AND (STATUS=1 OR STATUS=2) ORDER BY date_back ASC";

        $result = $xoopsDB->query($sql);

        $hasRents = false;

        while (false !== ($user_rents = $xoopsDB->fetchArray($result))) {
            $hasRents = true;

            $display = [];

            $today_with_hour = date('Y') . date('m') . date('d') . date('H') . date('i');

            $date_rent = $user_rents['date_rent'];

            $date_back = $user_rents['date_back'];

            $user_rents['date_rent'] = mb_substr($user_rents['date_rent'], 0, 4) . '/' . mb_substr($user_rents['date_rent'], 4, 2) . '/' . mb_substr($user_rents['date_rent'], 6, 2) . ' ' . mb_substr($user_rents['date_rent'], 8, 2) . ':' . mb_substr($user_rents['date_rent'], 10, 2);

            $user_rents['date_back'] = mb_substr($user_rents['date_back'], 0, 4) . '/' . mb_substr($user_rents['date_back'], 4, 2) . '/' . mb_substr($user_rents['date_back'], 6, 2) . ' ' . mb_substr($user_rents['date_back'], 8, 2) . ':' . mb_substr($user_rents['date_back'], 10, 2);

            $display['cd_name'] = "<a href='res_desc.php?id=" . $user_rents['ID_RENT'] . "'>";

            $real_cd_name = reference('xent_cm_cd', 'name', 'ID_CD', $user_rents['id_cd']);

            if (mb_strlen($real_cd_name) > 13) {
                $real_cd_name = mb_substr($real_cd_name, 0, 13) . '...';
            }

            $user_rents['copy'] = reference($module_tables[1], 'copy', 'ID_CD', $user_rents['id_cd']);

            if (1 == $user_rents['copy']) {
                $user_rents['copy'] = _MB_CM_YOURRES_COPYTEXT;
            } else {
                $user_rents['copy'] = '';
            }

            if ($today_with_hour < $date_back) {
                switch ($user_rents['status']) {
                    case 1:
                        $display['cd_name'] .= "<div class='cdToComeNApproved'><b>" . $real_cd_name . _MB_CM_YOURRES_CDTEXT . reference('xent_cm_cd', 'nogroup', 'ID_CD', $user_rents['id_cd']) . $user_rents['copy'] . '</b><br> ' . $user_rents['date_rent'] . _MB_CM_YOURRES_TO . $user_rents['date_back'];
                        break;
                    case 2:
                        $display['cd_name'] .= "<div class='cdToComeApproved'><b>" . $real_cd_name . _MB_CM_YOURRES_CDTEXT . reference('xent_cm_cd', 'nogroup', 'ID_CD', $user_rents['id_cd']) . $user_rents['copy'] . '</b><br> ' . $user_rents['date_rent'] . _MB_CM_YOURRES_TO . $user_rents['date_back'];
                        break;
                }
            } else {
                //en retard

                switch ($user_rents['status']) {
                    case 1:
                        $display['cd_name'] .= "<div class='cdLateNApproved'><b>" . $real_cd_name . _MB_CM_YOURRES_CDTEXT . reference('xent_cm_cd', 'nogroup', 'ID_CD', $user_rents['id_cd']) . $user_rents['copy'] . '</b><br> ' . $user_rents['date_back'];
                        break;
                    case 2:
                        $display['cd_name'] .= "<div class='cdLateApproved'><b>" . $real_cd_name . _MB_CM_YOURRES_CDTEXT . reference('xent_cm_cd', 'nogroup', 'ID_CD', $user_rents['id_cd']) . $user_rents['copy'] . '</b><br> ' . $user_rents['date_back'];
                        break;
                    case 3:
                        $display['cd_name'] .= "<div class='cdReturned'><b>" . $real_cd_name . _MB_CM_YOURRES_CDTEXT . reference('xent_cm_cd', 'nogroup', 'ID_CD', $user_rents['id_cd']) . $user_rents['copy'] . '</b><br> ' . $user_rents['date_back'];
                        break;
                }
            }

            $display['cd_name'] .= '</a>';

            if (0 == $user_rents['isPrint']) {
                $display['cd_name'] .= "<a href='print.php?id=" . $user_rents['ID_RENT'] . "' target='_blank'><img src='images/print.gif'></img></a></div>";
            }

            $display['cd_name'] .= '<br><br>';

            $block['display'][] = $display;
        }

        if (false === $hasRents) {
            $block['hasRents'] = _MB_CM_YOURRES_NORES;
        }

        $block['cdReturned'] = "<div class='cdReturnedLegend'>" . _MB_CM_YOURRES_CDRETURNED . '</div>';

        $block['cdLateNApproved'] = "<div class='cdLateNApprovedLegend'>" . _MB_CM_YOURRES_CDLATENAPPROVED . '</div>';

        $block['cdLateApproved'] = "<div class='cdLateApprovedLegend'>" . _MB_CM_YOURRES_CDLATEAPPROVED . '</div>';

        $block['cdToComeNApproved'] = "<div class='cdToComeNApprovedLegend'>" . _MB_CM_YOURRES_CDTOCOMENAPPROVED . '</div>';

        $block['cdToComeApproved'] = "<div class='cdToComeApprovedLegend'>" . _MB_CM_YOURRES_CDTOCOMEAPPROVED . '</div>';

        $block['legend'] = _MB_CM_YOURRES_LEGEND;

        return $block;
    }
