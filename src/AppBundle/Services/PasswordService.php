<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services;


class PasswordService
{
    public function generate(int $length = 12) : string {
        return substr(hash('sha512',rand()),0,$length);
    }
}