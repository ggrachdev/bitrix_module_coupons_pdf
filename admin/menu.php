<?
IncludeModuleLangFile(__FILE__);
/** @global CUser $USER */
if ($USER->IsAdmin())
{
	$menu = array(
		"parent_menu" => "global_menu_settings",
		"section" => "empty",
		"sort" => 1,
		"text" => GetMessage("GGRACHDEV_MENU_ITEM"),
		"icon" => "learning_icon_attempts",
		"page_icon" => "learning_icon_attempts",
		"items_id" => "menu_empty",
		"items" => array(),
	);
	if (!IsModuleInstalled('intranet'))
	$menu["items"][] = array(
		"text" => GetMessage("GGRACHDEV_EDIT_LIST"),
		"url" => "ggrachdev_edit_empty.php?lang=".LANGUAGE_ID,
	);
	return $menu;
}
else
{
	return false;
}
?>