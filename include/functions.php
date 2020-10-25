<?php

function highlightSearchWords($haystack, $needle)
{
    for ($x = 0, $xMax = count($needle); $x < $xMax; $x++) {
        $result = '';

        while (instr($haystack, $needle[$x], 1)) {
            $i = mb_strpos(mb_strtolower($haystack), mb_strtolower($needle[$x]));

            $result .= mb_substr($haystack, 0, $i) . "<font color='#FF0000'>" . mb_substr($haystack, $i, mb_strlen($needle[$x])) . '</font>';

            $haystack = mb_substr($haystack, $i + mb_strlen($needle[$x]));
        }

        $result .= $haystack;

        $haystack = $result;
    }

    return $result;
}

function instr($haystack, $needle, $insensitive = 0)
{
    if ($insensitive) {
        return (false !== mb_stristr($haystack, $needle)) ? true : false;
    }
  

    return (false !== mb_strpos($haystack, $needle)) ? true : false;
}

// retourne tous les enregistrements de la table fc1 pour
// les champs fct2 et fct3
function getTopic($fct1, $fct2, $fct3)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $sql = 'SELECT ' . $fct3 . ', ' . $fct2 . ' FROM ' . $xoopsDB->prefix($fct1) . ' ';

    $result = $xoopsDB->query($sql);

    $thearray = [];

    while ($topic = $xoopsDB->fetchArray($result)) {
        $theid = htmlspecialchars($topic[$fct3], ENT_QUOTES | ENT_HTML5);

        $thename = htmlspecialchars($topic[$fct2], ENT_QUOTES | ENT_HTML5);

        $thearray[$theid] = $thename;
    }

    return $thearray;
}

// retourne le fct2 pour un id = fct3 donné dans la table fct1
function reference($fct1, $fct2, $fct3, $id)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $sql = 'SELECT ' . $fct3 . ', ' . $fct2 . ' FROM ' . $xoopsDB->prefix($fct1) . ' WHERE ' . $fct3 . "=$id";

    $result = $xoopsDB->query($sql);

    [$id, $champs] = $xoopsDB->fetchRow($result);

    $titres = $myts->displayTarea($champs);

    return $titres;
}

//retourne le nombre de cd pour une cat ET SES SOUS-CAT
function getNumberOfCdInCat($id, $cd_c, $bool)
{
    global $xoopsDB, $module_tables;

    $id_cat = $id;

    // compte le nombre de cd dans la cat original ($id)

    $sql = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix($module_tables[1] . " WHERE id_cat = $id_cat");

    $result = $xoopsDB->query($sql);

    $view = $xoopsDB->fetchRow($result);

    static $cd_count = 0;

    if (true === $bool) {
        $cd_count = 0;
    }

    $test = $view[0];

    $cd_count += $test;

    // va voir si la cat originale ($id) est parent d'autres cat

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0] . " WHERE id_cat_parent = $id_cat");

    $result = $xoopsDB->query($sql);

    [$ID_CAT] = $xoopsDB->fetchRow($result);

    while (!empty($ID_CAT)) {
        getNumberOfCdInCat($ID_CAT, $cd_count, false);

        [$ID_CAT] = $xoopsDB->fetchRow($result);
    }

    return $cd_count;
}

// retourne l'arbre de cat/scat avec des liens pour accéder plus rapidement
function getCatTree($id)
{
    global $xoopsDB, $module_tables;

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_CAT=$id";

    $result = $xoopsDB->query($sql);

    [$ID_CAT, $name, $desc, $prefix, $id_cat_parent] = $xoopsDB->fetchRow($result);

    $count = 0;

    $arr = [];

    $arr[$count] = "<a href='scat_list.php?id=$ID_CAT'>$name</a>";

    while (0 != $id_cat_parent) {
        $count++;

        $sql1 = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_CAT=$id_cat_parent";

        $result1 = $xoopsDB->query($sql1);

        [$ID_CAT, $name, $desc, $prefix, $id_cat_parent] = $xoopsDB->fetchRow($result1);

        $arr[$count] = "<a href='scat_list.php?id=$ID_CAT'>" . reference($module_tables[0], 'name', 'ID_CAT', $ID_CAT) . '</a>';
    }

    return $arr;
}

function makeDaySelect($name, $caption = '', $selected = 1)
{
    if (1 == $selected) {
        $selected = (int)date('d');
    }

    $day_select = new XoopsFormSelect($caption, $name, $selected, 1, '');

    for ($x = 1; $x <= 31; $x++) {
        $day_select->addOption($x);
    }

    return $day_select;
}

function makeMonthSelect($name, $caption = '', $selected = 1)
{
    if (1 == $selected) {
        $selected = (int)date('m');
    }

    $month_select = new XoopsFormSelect($caption, $name, $selected, 1, '');

    $month_select->addOption(1, 'Jan');

    $month_select->addOption(2, 'Fev');

    $month_select->addOption(3, 'Mar');

    $month_select->addOption(4, 'Avr');

    $month_select->addOption(5, 'Mai');

    $month_select->addOption(6, 'Juin');

    $month_select->addOption(7, 'Jui');

    $month_select->addOption(8, 'Août');

    $month_select->addOption(9, 'Sep');

    $month_select->addOption(10, 'Oct');

    $month_select->addOption(11, 'Nov');

    $month_select->addOption(12, 'Dec');

    return $month_select;
}

// if 1 passes into this function it will come out as 01
function formatOneDigitIntoTwoDigit($number)
{
    if ('0' != mb_substr($number, 0, 1)) {
        if ($number >= 1 && $number <= 9) {
            $number = '0' . $number;
        }

        if (0 == $number || '0' == $number) {
            $number = '00';
        }
    }

    return $number;
}

function makeYearSelect($name, $caption = '', $selected = '', $endYear = 2004)
{
    $year_select = new XoopsFormSelect($caption, $name, $selected, 1, '');

    for ($x = (int)date('Y'); $x <= $endYear; $x++) {
        $year_select->addOption($x, $x);
    }

    return $year_select;
}

# Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber)
{
    return strftime('%A', mktime(0, 0, 0, 1, 2 + $daynumber, 2000));
}

function buildResMonthCalendar($month, $year, $id, $resdisplayed)
{
    global $xoopsDB, $module_tables, $xoopsModuleConfig;

    $tab = '';

    $weekstarts = 0;

    $month_start = mktime(0, 0, 0, $month, 1, $year);

    $weekday_start = (date('w', $month_start) - $weekstarts + 7) % 7;

    $days_in_month = date('t', $month_start);

    $month_end = mktime(23, 59, 59, $month, $days_in_month, $year);

    $tab .= '<table>';

    # Le header : Sunday, Monday, Tuesday, etc.

    for ($weekcol = 0; $weekcol < 7; $weekcol++) {
        $tab .= '<th width="14%">' . day_name(($weekcol + $weekstarts) % 7) . '</th>';
    }

    $tab .= '<tr>';

    # Mets des zones grises jusqu'au premier jour du mois

    for ($weekcol = 0; $weekcol < $weekday_start; $weekcol++) {
        $tab .= "<td bgcolor=\"#cccccc\" height=100>&nbsp;</td>\n";
    }

    $today_with_hour = date('Y') . date('m') . date('d') . date('H') . date('i');

    # Dessine les jours du mois

    for ($cday = 1; $cday <= $days_in_month; $cday++) {
        $today = $year . formatOneDigitIntoTwoDigit($month) . '00';

        $today = (int)$today + $cday;

        if (0 == $weekcol) {
            $tab .= "</tr><tr>\n";
        }

        if (date('d') == $cday && $month == date('m') && $year == date('Y')) {
            $tab .= "<td valign=top height=100 bgcolor=\"#FFF8DC\"><font color=\"#394f76\">$cday</font><br><br>";
        } else {
            $tab .= "<td valign=top height=100 bgcolor=\"#ededed\"><font color=\"#c65f2a\">$cday</font><br><br>";
        }

        # cette query retourne n'importe quel réservation qui entre en conflit

        # avec le laps de temps désiré

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[2]) . " WHERE id_cd=$id AND (LEFT(date_rent,8)<=$today AND LEFT(date_back,8)>=$today) ORDER BY date_rent ASC";

        #echo $sql;

        $result = $xoopsDB->queryF($sql);

        [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc, $id_user, $id_cd] = $xoopsDB->fetchRow($result);

        $count = 1;

        while (!empty($ID_RENT)) {
            if (-1 == $resdisplayed || $count <= $resdisplayed) {
                $d_r = (int)mb_substr($date_rent, 0, 8);

                $d_b = (int)mb_substr($date_back, 0, 8);

                $tab .= "<a href='res_desc.php?id=$ID_RENT'>";

                // pour savoir si une réservation est sur plusieurs jours

                if (0 == $d_b - $d_r) {
                    // sur une journée

                    $h_r = mb_substr($date_rent, 8, 2) . ':' . mb_substr($date_rent, 10, 2);

                    $h_b = mb_substr($date_back, 8, 2) . ':' . mb_substr($date_back, 10, 2);

                    $str_res = $h_r . _MA_CM_GENERAL_TO . $h_b;
                } else {
                    // sur plusieurs jours

                    $h_r = mb_substr($date_rent, 8, 2) . ':' . mb_substr($date_rent, 10, 2);

                    $h_b = mb_substr($date_back, 8, 2) . ':' . mb_substr($date_back, 10, 2);

                    if (mb_substr($date_rent, 0, 8) == $today) {
                        $str_res = $h_r . '---->';
                    } elseif (mb_substr($date_back, 0, 8) == $today) {
                        $str_res = '<----' . $h_b;
                    } else {
                        $str_res = '<---->';
                    }
                }

                if ($today_with_hour < $date_back) {
                    switch ($status) {
                         case 1:
                             $tab .= "<div class='cdToComeNApproved'>" . $str_res . '</div>';

                             break;
                         case 2:
                             $tab .= "<div class='cdToComeApproved'>" . $str_res . '</div>';
                             break;
                     }
                } else {
                    // ici le cd est retard

                    switch ($status) {
                         // cd loué et pas pris
                         case 1:
                             $tab .= "<div class='cdLateNApproved'>" . $str_res . '</div>';
                             break;
                         // loué et pris
                         case 2:
                             $tab .= "<div class='cdLateApproved'>" . $str_res . '</div>';
                             break;
                         // loué et remis
                         case 3:
                             $tab .= "<div class='cdReturned'>" . $str_res . '</div>';
                             break;
                     }
                }

                $tab .= '</a>';
            } else {
                break;
            }

            [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc, $id_user, $id_cd] = $xoopsDB->fetchRow($result);

            $count++;
        }

        [$ID_RENT, $date_rent, $date_back, $date_returned, $status, $desc, $id_user, $id_cd] = $xoopsDB->fetchRow($result);

        if (!empty($ID_RENT)) {
            $tab .= "<a href='view_res.php?id=$id&rd=-1'>>></a>";
        } elseif ($count > $xoopsModuleConfig['resview_display']) {
            $tab .= "<a href='view_res.php?id=$id'><<</a>";
        }

        $tab .= "</td>\n";

        if (7 == ++$weekcol) {
            $weekcol = 0;
        }
    }

    # Skip from end of month to end of week:

    if ($weekcol > 0) {
        for (; $weekcol < 7; $weekcol++) {
            $tab .= "<td bgcolor=\"#cccccc\" height=100>&nbsp;</td>\n";
        }
    }

    $tab .= '</tr></table>';

    return $tab;
}

function buildResMonthMenu($year, $id)
{
    $s = '| ';

    for ($x = 1; $x <= 12; $x++) {
        $month_s = mktime(0, 0, 0, $x, 1, $year);

        $s .= "<a href='view_res.php?id=$id&month=$x&year=$year'><font color=\"#394f76\">" . date('F', $month_s) . " $year</font></a> | ";
    }

    $s .= "<form name='moveyear' id='moveyear' method='post' onsubmit='return xoopsFormValidate_addcd();' enctype='multipart/form-data'>";

    $s .= "Changer l'année : <input type='text' name='year_box' size='6' maxlength='4' value='$year'>";

    $s .= '</form>';

    return $s;
}

function buildRentActionMenu()
{
    echo "<div class='adminActionMenu'><a href=adminrent.php?rescat=3>" . _AM_CM_RENT_ADMIN_CDRETURNED . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

    echo '<a href=adminrent.php?rescat=1>' . _AM_CM_RENT_ADMIN_CDLATENAPPROVED . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

    echo '<a href=adminrent.php?rescat=2>' . _AM_CM_RENT_ADMIN_CDLATEAPPROVED . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

    echo '<a href=adminrent.php?rescat=5>' . _AM_CM_RENT_ADMIN_CDTOCOMENAPPROVED . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

    echo '<a href=adminrent.php?rescat=4>' . _AM_CM_RENT_ADMIN_CDTOCOMEAPPROVED . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

    echo '<a href=adminrent.php?op=RentCleanDB>' . _AM_CM_RENT_ADMIN_CLEAN . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
}

function buildGrActionMenu()
{
    echo "<div class='adminActionMenu'><a href=admingroup.php>" . _AM_CM_GR_ADMIN_ADMINGROUP . '&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</a>';

    echo '<a href=admingroup.php?op=GRListGroup>' . _AM_CM_GR_ADMIN_GRLIST . '</a></div>';
}

function buildCdActionMenu()
{
    echo "<div class='adminActionMenu'><a href=admincd.php>" . _AM_CM_CD_ADMIN_ADMINCD . '</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';

    echo '<a href=admincd.php?op=CDListCd>' . _AM_CM_CD_ADMIN_CDLIST . '</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';

    echo '<a href=admincd_printcd.php?tri=1 target=_blank>' . _AM_CM_CD_ADMIN_PRINTCDLIST . '</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';

    echo '<a href=admincd_printcdkey.php?tri=1 target=_blank>' . _AM_CM_CD_ADMIN_PRINTCDKEYLIST . '</a></div>';
}

function buildCatActionMenu()
{
    echo "<div class='adminActionMenu'><a href=admincat.php class='adminActionMenu'>" . _AM_CM_CAT_ADMIN_ADMINCAT . '</a></div>';
}

function countCdInGroup($id)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

    $sql_count = 'SELECT COUNT(*) FROM ' . $xoopsDB->prefix($module_tables[1]) . " WHERE id_group = $id";

    $result_count = $xoopsDB->query($sql_count);

    [$num_cd_in_group] = $xoopsDB->fetchRow($result_count);

    return $num_cd_in_group;
}
