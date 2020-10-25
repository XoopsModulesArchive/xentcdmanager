<?php
// ------------------------------------------------------------------------- //
//                  Module xentCdManager pour Xoops 2.0.7                    //
//                              Version:  1.0                                //
// ------------------------------------------------------------------------- //
// Author: Milhouse                                        				     //
// Purpose: CD reservation system                          				     //
// email: hotkart@hotmail.com                                                //
// URLs:                      												 //
//---------------------------------------------------------------------------//
global $xoopsModuleConfig;
$modversion['name'] = _MI_CM_NAME;
$modversion['version'] = '1.0';
$modversion['description'] = _MI_CM_DESC;
$modversion['credits'] = 'Alexandre Parent (hotkart@hotmail.com)';
$modversion['author'] = 'Ecrit pour Xoops2<br>par Alexandre Parent (Milhouse)';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['image'] = 'images/xent_cm_slogo.png';
$modversion['help'] = '';
$modversion['dirname'] = 'xentcdmanager';

// MYSQL FILE
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file
//If you hack this modules, dont change the order of the table.
//All
$modversion['tables'][0] = 'xent_cm_cat';
$modversion['tables'][1] = 'xent_cm_cd';
$modversion['tables'][2] = 'xent_cm_rent';
$modversion['tables'][3] = 'xent_cm_cd_group';
$modversion['tables'][4] = 'xent_cm_searchcat';

//$modversion['onInstall'] = 'include/functions.php';
$modversion['templates'][1]['file'] = 'xentcdmanager_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'xentcdmanager_header.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'xentcdmanager_footer.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'xentcdmanager_scat_list.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'xentcdmanager_cd_desc.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'xentcdmanager_cd_reserve.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'xentcdmanager_group_desc.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'xentcdmanager_cd_done.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'xentcdmanager_view_res.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'xentcdmanager_res_desc.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'xentcdmanager_search.html';
$modversion['templates'][11]['description'] = '';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;
/*$modversion['sub'][1]['name'] = _MI_EV_SMNAME1;
$modversion['sub'][1]['url'] = "emplois.php";
$modversion['sub'][2]['name'] = _MI_EV_SMNAME2;
$modversion['sub'][2]['url'] = "temoignage.php"; */

// Blocks
$modversion['blocks'][1]['file'] = 'user_rents.php';
$modversion['blocks'][1]['name'] = _MI_CM_BLOCKS_USERRES_TITLE;
$modversion['blocks'][1]['description'] = _MI_CM_BLOCKS_USERRES_DESC;
$modversion['blocks'][1]['show_func'] = 'user_rents_show';
$modversion['blocks'][1]['template'] = 'xentcdmanager_user_rents.html';

//CONFIG
$modversion['config'][1]['name'] = 'cie';
$modversion['config'][1]['title'] = '_MI_CM_CONFIG_CIE_TITLE';
$modversion['config'][1]['description'] = '_MI_CM_CONFIG_CIE_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = '';

$modversion['config'][2]['name'] = 'module_title';
$modversion['config'][2]['title'] = '_MI_CM_CONFIG_TITLE_TITLE';
$modversion['config'][2]['description'] = '_MI_CM_CONFIG_TITLE_DESC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = 'Système de réservation de CD';

$modversion['config'][3]['name'] = 'cdno';
$modversion['config'][3]['title'] = '_MI_CM_CONFIG_CDNO_TITLE';
$modversion['config'][3]['description'] = '_MI_CM_CONFIG_CDNO_DESC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 100000;

$modversion['config'][4]['name'] = 'version';
$modversion['config'][4]['title'] = '_MI_CM_CONFIG_VERSION_TITLE';
$modversion['config'][4]['description'] = '_MI_CM_CONFIG_VERSION_DESC';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = 'xentCdManager v1.0';

$modversion['config'][5]['name'] = 'records_display';
$modversion['config'][5]['title'] = '_MI_CM_CONFIG_RECDISP_TITLE';
$modversion['config'][5]['description'] = '_MI_CM_CONFIG_RECDISP_DESC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = '10';

$modversion['config'][6]['name'] = 'resview_display';
$modversion['config'][6]['title'] = '_MI_CM_CONFIG_RESDISP_TITLE';
$modversion['config'][6]['description'] = '_MI_CM_CONFIG_RESDISP_DESC';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = '3';

$modversion['config'][7]['name'] = 'adminemail';
$modversion['config'][7]['title'] = '_MI_CM_CONFIG_ADMMAIL_TITLE';
$modversion['config'][7]['description'] = '_MI_CM_CONFIG_ADMMAIL_DESC';
$modversion['config'][7]['formtype'] = 'textbox';
$modversion['config'][7]['valuetype'] = 'text';
$modversion['config'][7]['default'] = '';

$modversion['config'][8]['name'] = 'isPrefix';
$modversion['config'][8]['title'] = '_MI_CM_CONFIG_isP_TITLE';
$modversion['config'][8]['description'] = '_MI_CM_CONFIG_isP_DESC';
$modversion['config'][8]['formtype'] = 'yesno';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = '0';
