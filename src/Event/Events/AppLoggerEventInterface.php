<?php

namespace App\Event\Events;

/**
 * Basic methods for all loger abtsract classes to follow
 */
interface AppLoggerEventInterface {

    //returns Entity type : product, category , order etc
    public function getEntityType(): string;

    //returns action Type : create , update , verify etc
    public function getAction(): string;

}