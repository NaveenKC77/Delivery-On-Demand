<?php

namespace App\Services;

interface UserTypeServicesInterface extends ServicesInterface
{
    /**
     * Summary of getAllVerifiedQueryBuilder
     * @return void
     *              returns all verified type users , in querybuilder type
     */
    public function getAllVerifiedQueryBuilder();
}
