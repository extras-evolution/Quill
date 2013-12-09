<?php
/**
 * Quill
 *
 * Increases the usefulness of template variables
 *
 * @category    snippet
 * @version     1.0.6
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author		Dertlian
 * @internal    @properties
 * @internal    @modx_category Content
 * @internal    @installset base, sample
 */

require_once(MODX_BASE_PATH . 'assets/snippets/quill/quill.class.inc.php');

$params = array(
	'mode' => isset($mode) ? $mode : 'list',
	'parent' => isset($parent) ? $parent : 0,
	'len' => isset($len) ? $len : 50,
	'depth' => isset($depth) ? $depth : 100,
	'indent' => isset($indent) ? $indent : 3,
	'default' => isset($default) ? $default : null,
	'showPublishedOnly' => isset($showPublishedOnly) ? $showPublishedOnly : 1,
	'source' => isset($source) ? $source : 'documents',
	'title' => isset($title) ? $title : 'pagetitle',
	'sort' => isset($sort) ? $sort : 'menuindex',
	'sortdir' => isset($sortdir) ? $sortdir : 'ASC',
	'tv' => isset($tv) ? $tv : (isset($name) ? $name : ''), // support for old param name [name]
	'value' => isset($value) ? $value : null,
	'filter' => isset($filter) ? $filter : null,
	'type' => isset($type) ? $type : null,
	'elements' => isset($elements) ? $elements : null,
);

$quill = new Quill($params);
return $quill->Run();
?>