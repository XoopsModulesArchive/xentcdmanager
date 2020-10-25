<?php
// $Id: xoopstopic.php,v 1.4 2005/04/07 15:10:23 m4d3l Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, https://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

class XoopsTopic
{
    public $table;

    public $topic_id;

    public $topic_pid;

    public $topic_title;

    public $topic_imgurl;

    public $prefix; // only used in topic tree

    public $use_permission = false;

    public $mid; // module id used for setting permission

    public $temp;

    public function __construct($table, $topicid = 0, $temp = 0)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->table = $table;

        if (is_array($topicid)) {
            $this->makeTopic($topicid);
        } elseif (0 != $topicid) {
            $this->getTopic((int)$topicid);
        } else {
            $this->topic_id = $topicid;
        }
    }

    public function setTopicTitle($value)
    {
        $this->topic_title = $value;
    }

    public function setTopicImgurl($value)
    {
        $this->topic_imgurl = $value;
    }

    public function setTopicPid($value)
    {
        $this->topic_pid = $value;
    }

    public function getTopic($topicid)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ID_CAT=' . $topicid . '';

        $array = $this->db->fetchArray($this->db->query($sql));

        $this->makeTopic($array);
    }

    public function makeTopic($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    public function usePermission($mid)
    {
        $this->mid = $mid;

        $this->use_permission = true;
    }

    public function store()
    {
        $myts = MyTextSanitizer::getInstance();

        $title = '';

        $imgurl = '';

        if (isset($this->topic_title) && '' != $this->topic_title) {
            $title = $myts->addSlashes($this->topic_title);
        }

        if (isset($this->topic_imgurl) && '' != $this->topic_imgurl) {
            $imgurl = $myts->addSlashes($this->topic_imgurl);
        }

        if (!isset($this->topic_pid) || !is_numeric($this->topic_pid)) {
            $this->topic_pid = 0;
        }

        if (empty($this->topic_id)) {
            $this->topic_id = $this->db->genId($this->table . '_topic_id_seq');

            $sql = sprintf("INSERT INTO %s (topic_id, topic_pid, topic_imgurl, topic_title) VALUES (%u, %u, '%s', '%s')", $this->table, $this->topic_id, $this->topic_pid, $imgurl, $title);
        } else {
            $sql = sprintf("UPDATE %s SET topic_pid = %u, topic_imgurl = '%s', topic_title = '%s' WHERE topic_id = %u", $this->table, $this->topic_pid, $imgurl, $title, $this->topic_id);
        }

        if (!$result = $this->db->query($sql)) {
            ErrorHandler::show('0022');
        }

        if (true === $this->use_permission) {
            if (empty($this->topic_id)) {
                $this->topic_id = $this->db->getInsertId();
            }

            $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

            $parent_topics = $xt->getAllParentId($this->topic_id);

            if (!empty($this->m_groups) && is_array($this->m_groups)) {
                foreach ($this->m_groups as $m_g) {
                    $moderate_topics = XoopsPerms::getPermitted($this->mid, 'ModInTopic', $m_g);

                    $add = true;

                    // only grant this permission when the group has this permission in all parent topics of the created topic

                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $moderate_topics, true)) {
                            $add = false;

                            continue;
                        }
                    }

                    if (true === $add) {
                        $xp = new XoopsPerms();

                        $xp->setModuleId($this->mid);

                        $xp->setName('ModInTopic');

                        $xp->setItemId($this->topic_id);

                        $xp->store();

                        $xp->addGroup($m_g);
                    }
                }
            }

            if (!empty($this->s_groups) && is_array($this->s_groups)) {
                foreach ($s_groups as $s_g) {
                    $submit_topics = XoopsPerms::getPermitted($this->mid, 'SubmitInTopic', $s_g);

                    $add = true;

                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $submit_topics, true)) {
                            $add = false;

                            continue;
                        }
                    }

                    if (true === $add) {
                        $xp = new XoopsPerms();

                        $xp->setModuleId($this->mid);

                        $xp->setName('SubmitInTopic');

                        $xp->setItemId($this->topic_id);

                        $xp->store();

                        $xp->addGroup($s_g);
                    }
                }
            }

            if (!empty($this->r_groups) && is_array($this->r_groups)) {
                foreach ($r_groups as $r_g) {
                    $read_topics = XoopsPerms::getPermitted($this->mid, 'ReadInTopic', $r_g);

                    $add = true;

                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $read_topics, true)) {
                            $add = false;

                            continue;
                        }
                    }

                    if (true === $add) {
                        $xp = new XoopsPerms();

                        $xp->setModuleId($this->mid);

                        $xp->setName('ReadInTopic');

                        $xp->setItemId($this->topic_id);

                        $xp->store();

                        $xp->addGroup($r_g);
                    }
                }
            }
        }

        return true;
    }

    public function delete()
    {
        $sql = sprintf('DELETE FROM %s WHERE topic_id = %u', $this->table, $this->topic_id);

        $this->db->query($sql);
    }

    public function topic_id()
    {
        return $this->topic_id;
    }

    public function topic_pid()
    {
        return $this->topic_pid;
    }

    public function topic_title($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();

        switch ($format) {
            case 'S':
                $title = htmlspecialchars($this->topic_title, ENT_QUOTES | ENT_HTML5);
                break;
            case 'E':
                $title = htmlspecialchars($this->topic_title, ENT_QUOTES | ENT_HTML5);
                break;
            case 'P':
                $title = htmlspecialchars($this->topic_title, ENT_QUOTES | ENT_HTML5);
                break;
            case 'F':
                $title = htmlspecialchars($this->topic_title, ENT_QUOTES | ENT_HTML5);
                break;
        }

        return $title;
    }

    public function topic_imgurl($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();

        switch ($format) {
            case 'S':
                $imgurl = htmlspecialchars($this->topic_imgurl, ENT_QUOTES | ENT_HTML5);
                break;
            case 'E':
                $imgurl = htmlspecialchars($this->topic_imgurl, ENT_QUOTES | ENT_HTML5);
                break;
            case 'P':
                $imgurl = htmlspecialchars($this->topic_imgurl, ENT_QUOTES | ENT_HTML5);
                break;
            case 'F':
                $imgurl = htmlspecialchars($this->topic_imgurl, ENT_QUOTES | ENT_HTML5);
                break;
        }

        return $imgurl;
    }

    public function prefix()
    {
        return $this->prefix ?? null;
    }

    public function getFirstChildTopics()
    {
        $ret = [];

        $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

        $topic_arr = $xt->getFirstChild($this->topic_id, 'topic_title');

        if (is_array($topic_arr) && count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    public function getAllChildTopics()
    {
        $ret = [];

        $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

        $topic_arr = $xt->getAllChild($this->topic_id, 'topic_title');

        if (is_array($topic_arr) && count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    public function getChildTopicsTreeArray()
    {
        $ret = [];

        $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

        $topic_arr = $xt->getChildTreeArray($this->topic_id, 'topic_title');

        if (is_array($topic_arr) && count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    public function makeTopicSelBox($none = 0, $seltopic = -1, $selname = '', $onchange = '')
    {
        $xt = new XoopsTree($this->table, 'ID_CAT', 'id_cat_parent');

        if (-1 != $seltopic) {
            $xt->makeMySelBox('name', 'name', $seltopic, $none, $selname, $onchange);
        } elseif (!empty($this->topic_id)) {
            $xt->makeMySelBox('name', 'name', $this->topic_id, $none, $selname, $onchange);
        } else {
            $xt->makeMySelBox('name', 'name', 0, $none, $selname, $onchange);
        }
    }

    //generates nicely formatted linked path from the root id to a given id

    public function getNiceTopicPathFromId($funcURL)
    {
        $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

        $ret = $xt->getNicePathFromId($this->topic_id, 'toppic_title', $funcURL);

        return $ret;
    }

    public function getAllChildTopicsId()
    {
        $xt = new XoopsTree($this->table, 'topic_id', 'topic_pid');

        $ret = $xt->getAllChildId($this->topic_id, 'toppic_title');

        return $ret;
    }

    public function &getTopicsList()
    {
        $result = $this->db->query('SELECT topic_id, topic_pid, topic_title FROM ' . $this->table);

        $ret = [];

        $myts = MyTextSanitizer::getInstance();

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[$myrow['topic_id']] = ['title' => htmlspecialchars($myrow['topic_title'], ENT_QUOTES | ENT_HTML5), 'pid' => $myrow['topic_pid']];
        }

        return $ret;
    }

    public function topicExists($pid, $title)
    {
        $sql = 'SELECT COUNT(*) from ' . $this->table . ' WHERE topic_pid = ' . (int)$pid . " AND topic_title = '" . trim($title) . "'";

        $rs = $this->db->query($sql);

        [$count] = $this->db->fetchRow($rs);

        if ($count > 0) {
            return true;
        }
  

        return false;
    }
}
