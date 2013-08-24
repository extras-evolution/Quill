Quill 1.0.4
-----------

Installation
------------
1. Create folder assets/snippets/quill and copy there files quill.class.inc.php and index.html
2. Create snippet with name "Quill" and description "<strong>1.0.4</strong> Increase documents and TV usage"
3. Copy paste snippet code from quill.snippet.txt and save snippet.
	
Parameters
----------
parent			Numeric ID or alias of parent document. Use with 'list' mode
len				Maximum length of list items title. Use with 'list' mode
depth			Depth of children to process. Use with 'list' mode
indent			Indent step for child items. Use with 'list' mode
default			Name of additional default item with value '0'. Use with 'list' mode
showPublishedOnly	Enables only published documents processing
source			Switches source of items for list (documents | managers | chunks | range). Use with 'list' mode
title			Fieldname to use as title in documents list. Use with 'list' mode
tv				Name of TV to use in [control] or [field] modes. (old param name is 'name')
value			Value of TV to use in 'control' or 'field' modes, or range description in range mode (use 1-150 or a-z format). If not defined, returns existent TV value
sort			Attribute to sort list like 'pagetitle', 'menuindex', 'id'…. Use with 'list' mode
sortdir			Directon of sort (asc / desc). Use with 'list' mode and 'sort' parameter
mode			Switches mode: 'list' returns hierarchical tree of child document, 'control' returns manager control for TV, 'field' returns rendered TV value

Examples
--------
"CURRENCIES" — bind group of documents with TV.
1. Create document with pagetitle "My currencies" and alias "currencies"
2. Create three children documents in "My Currency" and set their pagetitles to "US dollar", "Euro", "Yen" and aliases (or longtitles or another TV) to "USD","EUR","JPY"
3. Create template with name "Good"
4. Create TV with name "price_currency" type "DropDown List Menu" and elements "@EVAL return $modx->runSnippet("Quill",array('parent'=>'currencies'));" or "@EVAL return $modx->runSnippet("Quill", array('parent'=>'currencies', 'title'=>'longtitle'));"
5. Bind this TV with "Good" template
6. Ready. At once just create document with template "Good"
	
"FILTER FORM" — easy creation of form controls for filter form.
1. To paste "price_currency" TV control (DropDown List Menu) just call in template/chunk Quill snippet: [[Quill? &mode=`control` &name=`price_currency`]]);

"SHOW CURRENCY OF GOOD"
1. Create document with template "Good", set "price_currency" and save.
2. Just add string in "Good" template: Currency is: [[Quill? &mode=`field` &name=`price_currency`]]);