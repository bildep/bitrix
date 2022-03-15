<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;

Loader::includeModule("iblock");
Loader::includeModule("highloadblock");




class UserAddressCompontent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;
        $arResult = [];
        $cacheTime = 3600;
        $cacheId = 'myUniqueCacheId';
        $cachePath = 'kcache/component/users.addredss';


        $cache = Cache::createInstance(); // Служба кеширования
        $taggedCache = Application::getInstance()->getTaggedCache();

        if ($USER->IsAuthorized()) {

            if ($cache->initCache($cacheTime, $cacheId, $cachePath)) {
                $res = $cache->getVars();
                $this->arResult = json_decode($res["arResult"], JSON_OBJECT_AS_ARRAY );

            } elseif ($cache->startDataCache($cacheTime, $cacheId, $cachePath)) {

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

                // Добавляем теги
                $taggedCache->registerTag('user_address');

                // Всё хорошо, записываем кеш
                $taggedCache->endTagCache();

                $cache->endDataCache( array(
                    "arResult" => json_encode($this->arResult)
                ) );

            }

            $this->IncludeComponentTemplate();
        }

    }

}?>