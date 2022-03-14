<?

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

Loader::includeModule("iblock");
Loader::includeModule("highloadblock");

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class UserAddressCompontent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            if ($this->startResultCache(false)) {

                $this->prepareResult();
                $this->IncludeComponentTemplate();
            }
        } catch (Exception $e) {
            $this->AbortResultCache();
            $this->arResult['ERROR'] = $e->getMessage();
        }

    }

    protected function prepareResult()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $USER_ID = $USER->GetID();

            $hlblock = HL\HighloadBlockTable::getById(4)->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            if ($this->arParams['SHOW_ALL'] == 'Y') {
                $filter = array('UF_USER_ID' => $USER_ID);
            } else {
                $filter = array('UF_ACTIVE' => '1', 'UF_USER_ID' => $USER_ID);
            }

            $rsData = $entity_data_class::getList(array(
                'select' => array('*'),
                'order' => array('ID' => 'ASC'),
                'filter' => $filter
            ));
            while ($arData = $rsData->Fetch()) {
                $this->arResult['ITEMS'][] = $arData;
            }

            if (!$this->arResult['ITEMS']) {
                $this->AbortResultCache();
            }

        }
    }
}