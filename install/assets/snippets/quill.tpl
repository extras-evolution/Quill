//<?php
/**
 * Quill
 * 
 * Increase documents and TV usage
 * @category 	snippet
 * @version 	1.0.4
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties 
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 */
require_once($modx->config['base_path'].'assets/snippets/quill/quill.class.inc.php');
$params = array(
'mode' => isset($mode) ? $mode : 'list',
'parent'=> isset($parent) ? $parent : 0,
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
);

$quill = new Quill($params);
return $quill->Run();
