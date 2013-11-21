<?php
/**
 * Snippetname:	Quill
 * Version:		1.0.5
 * Author:		Urique Dertlian (urique@unix.am)
 * Date:		20-11-2013
 */
if (!class_exists('Quill')) {

	class Quill {

		var $mode; // list | control | field
		var $tv; // TV name
		var $value; // TV value or range a-z or 1-100
		var $parentID;
		var $len;
		var $title;
		var $showPublishedOnly;
		var $depth;
		var $source; // documents | managers | chunks | range
		var $default;
		var $indent;
		var $sort;
		var $sortdir;
		var $utf;

		//_______________________________________________________
		function Quill($params) {
			global $modx;
			$this->utf = ($modx->config['modx_charset'] == 'UTF-8');
			$this->mode = $params['mode'];
			$this->parentID = !is_numeric($params['parent']) ? $this->getDocumentByAlias($params['parent']) : $params['parent'];
			$this->tv = $params['tv'];
			$this->value = $params['value'];
			$this->len = $params['len'];
			$this->indent = $params['indent'];
			$this->title = $params['title'];
			$this->showPublishedOnly = $params['showPublishedOnly'];
			$this->depth = $params['depth'];
			$this->fdepth = $this->depth;
			//if($modx->isFrontend() && isset($params['fdepth'])) $this->depth = $params['fdepth'];
			$this->source = $params['source'];
			$this->default = $params['default'];
			$this->sort = $params['sort'];
			$this->sortdir = $params['sortdir'];
			$this->filter = $params['filter'];
		}

		//_______________________________________________________
		function Run() {
			switch ($this->mode) {
				case 'list':
					return $this->getElements();
					break;
				case 'field':
				case 'control':
					return $this->getCC($this->tv, $this->value);
					break;
			}
		}

		//_______________________________________________________
		function getElements() {
			switch ($this->source) {
				case 'documents':
					$ddItems = $this->getDocuments($this->parentID);
					break;
				case 'managers':
					$ddItems = $this->getUsers('manager');
					break;
				case 'chunks':
					$ddItems = $this->getChunks();
					break;
				case 'range':
					$ddItems = $this->getRange($this->value);
					break;
			}
			if ($this->default !== null) {
				$ddItems = array_merge(array($this->genElement($this->default, '0')), $ddItems);
			}
			return implode('||', $ddItems);
		}

		//_______________________________________________________
		function getDocuments($p, $l = 0) {
			global $modx;
			($this->utf) ? $nbsp = chr(0xC2) . chr(0xA0) : $nbsp = chr(0xA0);

			$pbDocs = $modx->getDocumentChildren($p, 1, 0, '*', '', $this->sort, $this->sortdir);
			$unpbDocs = $modx->getDocumentChildren($p, 0, 0, '*', '', $this->sort, $this->sortdir);
			$c = ($this->showPublishedOnly == 0) ? array_merge($pbDocs, $unpbDocs) : $pbDocs;
			if ($this->filter) {
				$filter = explode(',', $this->filter);
				$tvs = array($this->title, $filter[0]);
			} else {
				$tvs = $this->title;
			}

			$out = array();
			foreach ($c as $k) {
				$tvOut = $modx->getTemplateVarOutput($tvs, $k['id'], $this->showPublishedOnly);
				if (!$this->filter || ($this->filter && trim($filter[1]) == trim($tvOut[$filter[0]]))) {
					$out[] = $this->genElement(str_repeat($nbsp, $l * $this->indent) . $tvOut[$this->title], $k['id'], $l);
				}
				if ($l < $this->depth) {
					$out = array_merge($out, $this->getDocuments($k['id'], $l + 1));
				}
			}
			return $out;
		}

		//_______________________________________________________
		function getUsers($kind) {
			global $modx;
			$uid = 1;

			$out = array();
			while (is_array($user = $modx->getUserInfo($uid))) {
				$title = $user['username'];
				if (!empty($user['fullname'])) {
					$title .= ' (' . $user['fullname'] . ')';
				}
				$out[] = $this->genElement($title, $uid);
				$uid++;
			}
			return $out;
		}

		//_______________________________________________________
		function getChunks() {
			global $modx;
			$sql = "SELECT id,name FROM " . $modx->getFullTableName('site_htmlsnippets');
			$res = $modx->db->query($sql);
			$out = array();
			while ($row = $modx->db->getRow($res)) {
				$out[] = $this->genElement($row['name'], $row['id']);
			}
			return $out;
		}

		//_______________________________________________________
		function getRange($range) {
			$ranges = explode('-', $range);
			if (count($ranges) < 2) {
				return $range;
			}
			if (isset($ranges[2]) && is_numeric($ranges[2]))
				return $this->genElements(range($ranges[0], $ranges[1], $ranges[2]), true);
			else
				return $this->genElements(range($ranges[0], $ranges[1]), true);
		}

		//_______________________________________________________
		function genElement($title, $key, $l = 0) {
			$maxLen = ($this->utf) ? $this->len * 2 + $l * $this->indent * 2 : $this->len + $l * $this->indent;
			$len = ($this->utf) ? strlen($title) / 2 - $l * $this->indent : strlen($title) - $l * $this->indent;
			if ($this->len > 0 && $len > $this->len) {
				$title = substr($title, 0, $maxLen) . "…";
			}
			return $title . '==' . $key;
		}

		//_______________________________________________________
		function genElements($items, $ikeys = false) {
			$elements = array();
			foreach ($items as $key => $value) {
				$elements[] = $value . '==' . (($ikeys) ? $value : $key);
			}
			return $elements;
		}

		//_______________________________________________________
		function getCC($tv_name, $tv_val) {
			global $modx;
			include_once(MODX_MANAGER_PATH . 'includes/tmplvars.format.inc.php');
			include_once(MODX_MANAGER_PATH . 'includes/tmplvars.commands.inc.php');
			include_once(MODX_MANAGER_PATH . 'includes/tmplvars.inc.php');

			$sql = "SELECT type,name,elements,default_text FROM " . $modx->getFullTableName('site_tmplvars') . " WHERE name='" . $tv_name . "'";
			$rs = mysql_query($sql);
			$row = mysql_fetch_assoc($rs);
			mysql_free_result($rs);

			if ($this->mode == 'control') {
				if (!$tv_val) {
					if (isset($_REQUEST[$tv_name])) {
						$tv_val = $_REQUEST[$tv_name];
						if (is_array($tv_val))
							$tv_val = implode('||', $tv_val);
						$tv_val = mysql_escape_string($tv_val);
					}
					else {
						$docid = $modx->getDocumentIdentifier;
						$tv = $modx->getTemplateVar($tv_name, 'id', $docid);
						$tv_val = $tv['value'];
					}
				}
				return $this->cleanFormElement(renderFormElement($row['type'], $row['name'], $row['default_text'], $row['elements'], $tv_val, ''), $tv_name);
			}

			$index_list = ParseIntputOptions(ProcessTVCommand($row['elements']));
			foreach ($index_list as $elem) {
				list($v, $k) = explode('==', $elem);
				$elements[$k] = $v;
			}

			if (!$tv_val) {
				$docid = $modx->getDocumentIdentifier;
				$tv = $modx->getTemplateVar($tv_name, '*', $docid);
				$value = $tv['value'];
				$values = explode('||', $value);
				if (is_array($values)) {
					foreach ($values as $k => $v) {
						$values[$k] = $elements[$v];
					}
					$value = implode('||', $values);
				}
				$tv_val = getTVDisplayFormat($tv_name, $value, $tv['display'], $tv['display_params'], $tv['type'], $docid);
			}

			return trim($tv_val, chr(0xC2) . chr(0xA0));
		}

		//_______________________________________________________
		function getDocumentByAlias($alias) {
			global $modx;
			$sql = "SELECT id FROM " . $modx->getFullTableName('site_content') . " WHERE alias='$alias'";
			if ($res = $modx->db->query($sql)) {
				if ($row = $modx->db->getRow($res))
					return $row['id'];
			}
			return 0;
		}

		//_______________________________________________________
		function cleanFormElement($code, $tvname = '') {
			$code = preg_replace('#(id|for)="tv#', '$1="' . $tvname, $code);
			$code = str_replace('name="tv', 'name="', $code);
			$code = str_replace(array(' onchange="documentDirty=true;"', ' size="1"', ' tvtype="number"'), '', $code);
			return $code;
		}

	}

}
?>