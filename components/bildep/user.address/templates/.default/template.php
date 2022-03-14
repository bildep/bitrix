<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?php
$list = [];

foreach ($arResult['ITEMS'] as $k=>$item) {
    $list[$k]['data']['ID'] = $item['ID'];
    $list[$k]['data']['ADDRESS'] = $item['UF_ADDRESS'];
}

$columns[0] = ['id' => 'ID', 'name' => 'ID'];
$columns[1] = ['id' => 'ADDRESS', 'name' => 'Адрес'];

$APPLICATION->IncludeComponent("bitrix:main.ui.grid", "template1", Array(
	"GRID_ID" => "report_list",
    'COLUMNS' => $columns,
    "ROWS" => $list,
    "SHOW_ROW_ACTIONS_MENU" => false,
    "SHOW_GRID_SETTINGS_MENU" => false,
    "ALLOW_SORT" => false,
    'SHOW_ROW_CHECKBOXES' => false,
    'SHOW_SELECTED_COUNTER' => false,
    'SHOW_TOTAL_COUNTER' => false

),
	false
);
?>