<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentcdmanager/class/xoopstopic.php';

    global $xoopsDB, $xoopsConfig, $xoopsModule, $module_tables;

    $myts = MyTextSanitizer::getInstance();

    if (!empty($_GET['tri'])) {
        $tri = $_GET['tri'];
    } else {
        $tri = 1;
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE cdkey <> ''";

    switch ($tri) {
        case 1:
            $sql .= ' ORDER BY CAST(number AS SIGNED)';
            break;
        case 2:
            $sql .= ' ORDER BY name';
            break;
        default:
            $sql .= ' ORDER BY CAST(number AS SIGNED)';
            break;
    }

    $result = $xoopsDB->query($sql);

    echo "<table width='100%' border='1' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' cellpadding='2'><th align='left'><a href='admincd_printcdkey.php?tri=1'>Numéro</a></th><th align='left'><a href='admincd_printcdkey.php?tri=2'>Nom</a></th><th>Cd-Key</th>";
    while (false !== ($cd = $xoopsDB->fetchArray($result))) {
        if (1 == $cd['copy']) {
            _AM_CM_CD_ADD_COPY == $cd['copy'];
        } else {
            '' == $cd['copy'];
        }

        echo "<tr><td width='10%'>" . $cd['number'] . '</td>';

        echo '<td>' . $cd['name'] . '&nbsp;' . $cd['copy'] . '&nbsp;CD&nbsp;' . $cd['nogroup'] . '</td>';

        echo '<td>' . ($cd['cdkey']) . '</td>';
    }
    echo '</tr></table>';
