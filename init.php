<?php
use Bitrix\Main;
use Bitrix\Main\Entity;

function d($data){
    echo "<pre>".print_r($data,true)."</pre>";
}

$eventManager = Main\EventManager::getInstance();
$eventManager->addEventHandler("", "AddressesOnBeforeUpdate", "UsersAddressesBeforeUpdate");

function UsersAddressesBeforeUpdate(Entity\Event $event)
{

$cache = Bitrix\Main\Data\Cache::createInstance();
$cache->cleanDir('kcache/component/users.addredss');
}