<?php
/**
 * This file is part of 
 * Kimai - Open Source Time Tracking // http://www.kimai.org
 * (c) 2006-2009 Kimai-Development-Team
 * 
 * Kimai is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; Version 3, 29 June 2007
 * 
 * Kimai is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Kimai; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

// ================
// = EX PROCESSOR =
// ================

// insert KSPI
$isCoreProcessor = 0;
$dir_templates = "templates/";
require("../../includes/kspi.php");

require("private_db_layer.php");

// ==================
// = handle request =
// ==================
switch ($axAction) {
    // =========================================
    // = Erase timesheet entry via quickdelete =
    // =========================================
    /*case 'quickdelete':
        zef_delete_record($id);
        echo 1;
    break;*/

    // ===========================
    // = Load data and return it =
    // ===========================
    case 'reload':
        $timeformat       = isset($_REQUEST['timeformat']) ? strip_tags($_REQUEST['timeformat']) : null;
        $dateformat       = isset($_REQUEST['dateformat']) ? strip_tags($_REQUEST['dateformat']) : null;
        $default_location = isset($_REQUEST['dateformat']) ? strip_tags($_REQUEST['default_location']) : '';

        // write format in smarty notation
        $timeformat = preg_replace('/([A-Za-z])/','%$1',$timeformat);
        $dateformat = preg_replace('/([A-Za-z])/','%$1',$dateformat);

        $filters = explode('|',$axValue);
        if ($filters[0] == "")
          $filterUsr = array();
        else
          $filterUsr = explode(':',$filters[0]);

        if ($filters[1] == "")
          $filterKnd = array();
        else
          $filterKnd = explode(':',$filters[1]);

        if ($filters[2] == "")
          $filterPct = array();
        else
          $filterPct = explode(':',$filters[2]);

        // if no userfilter is set, set it to current user
        if (isset($kga['usr']) && count($filterUsr) == 0)
          array_push($filterUsr,$kga['usr']['usr_ID']);
          
        if (isset($kga['customer']))
          $filterKnd = array($kga['customer']['knd_ID']);

        $arr_data = xp_get_arr($in,$out,$filterUsr,$filterKnd,$filterPct,1,$default_location);


        if (count($arr_data)>0) {
            $tpl->assign('arr_data', $arr_data);
        } else {
            $tpl->assign('arr_data', 0);
        }
        $tpl->assign('total', intervallApos(get_zef_time($in,$out,$filterUsr,$filterKnd,$filterPct)));

        $ann = xp_get_arr_usr($in,$out,$filterUsr,$filterKnd,$filterPct);
        $ann_new = intervallApos($ann);
        $tpl->assign('usr_ann',$ann_new);
        
        $ann = xp_get_arr_knd($in,$out,$filterUsr,$filterKnd,$filterPct);
        $ann_new = intervallApos($ann);
        $tpl->assign('knd_ann',$ann_new);

        $ann = xp_get_arr_pct($in,$out,$filterUsr,$filterKnd,$filterPct);
        $ann_new = intervallApos($ann);
        $tpl->assign('pct_ann',$ann_new);

        $ann = xp_get_arr_evt($in,$out,$filterUsr,$filterKnd,$filterPct);
        $ann_new = intervallApos($ann);
        $tpl->assign('evt_ann',$ann_new);

        $tpl->assign('custom_timeformat',$timeformat);
        $tpl->assign('custom_dateformat',$dateformat);
        $tpl->display("table.tpl");
    break;

}

?>