Quill
================================================================================

Increases the usefulness of template variables
for the MODX Evolution content management framework

Features:
--------------------------------------------------------------------------------
With this snippet the option values of a dropdown/listbox/check box/radio options template variable could be prefilled in the MODX backend. In the frontend a filter form control based on the option values of a template variable and the option text(s) corresponding to the current value(s) of a template variable could be shown.

Installation:
--------------------------------------------------------------------------------
1. Upload the folder *assets/snippets/quill* in the corresponding folder in your installation
2. Create a snippet called Quill and fill the snippet code with the content of the file *install/assets/snippets/quill.tpl*

Parameters:
--------------------------------------------------------------------------------

The following snippet parameter could be used

Name | Description | Default
---- | ----------- | -------
parent | Numeric ID or alias of parent document. Use with **list** mode | 0
len | Maximum length of list items title. Use with **list** mode | 50
depth | Depth of children to process. Use with **list** mode | 100
indent | Indent step for child items. Use with **list** mode | 3
default | Name of additional default item with value '0'. Use with **list** mode | -
showPublishedOnly | Enables only published documents processing | 1
source | Switches source of items for list (documents | managers | chunks | range). Use with **list** mode | documents
title | Fieldname to use as title in documents list. Use with **list** mode | page title
tv | Name of template variable to use in [control] or [field] modes. (old param name is 'name') |
value | Value of template variable to use in 'control' or 'field' modes, or range description in range mode (use 1-150 or a-z format). If not defined, returns existent template variable value | -
sort | Attribute to sort list like 'pagetitle', 'menuindex', 'id'…. Use with **list** mode | menuindex
sortdir | Directon of sort (asc / desc). Use with **list** mode and 'sort' parameter | ASC
mode | Switches mode: **list** returns hierarchical tree of child document, 'control' returns manager control for template variable, 'field' returns the template variable option text instead of the template variable value | list

Examples:
--------------------------------------------------------------------------------

### Fill option values of a listbox/check box/radio options template variable

With this example the selectable values of a template variable could be prefilled in the backend:

1. Create a resource with the pagetitle `Currencies` and the alias `currencies`
2. Create some child resources in `Currencies` resource and set their pagetitles to `US dollar`, `Euro`, `Yen` etc. and set their aliases  to `use`, `eur`, `jpy`
4. Create a template variable with the name `currency` and the type `DropDown List Menu` and input option value `@EVAL return $modx->runSnippet("Quill",array('parent'=>'currencies'));`
5. Bind this template variable with a template
6. Ready. Edit/create a resource with this template

### Show the template variable input in the frontend

With this example a filter form control could be shown in the fronted:

1. Create the resources and the template variable as in the first example
2. Create a new resource and insert somewhere in the resource content the following snippet call ``[[Quill? &mode=`control` &tv=`currency`]]``

### Show the option text(s) of the current template variable value(s)

With this example the option text(s) corresponding to the current value(s) of a template variable could be shown in the fronted:

1. Create the resources and the template variable as in the first example
2. Create a new resource and insert somewhere in the resource content the following snippet call ``[[Quill? &mode=`field` &tv=`currency`]]``
