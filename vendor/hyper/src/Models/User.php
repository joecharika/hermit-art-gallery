<?php
/**
 * Hyper v0.7.2-beta.2 (https://hyper.starlight.co.zw)
 * Copyright (c) 2020. Joseph Charika
 * Licensed under MIT (https://github.com/joecharika/hyper/master/LICENSE)
 */

namespace Hyper\Models;


use Hyper\Annotations\file;
use Hyper\Application\Authorization;
use Hyper\Database\DatabaseContext;
use Hyper\Functions\Obj;
use Hyper\Http\Cookie;
use Hyper\SQL\SQLAttributes;
use function is_null;

/**
 * Class User
 * @package hyper\Models
 */
class User
{

    /**
     * @var string $id
     * @SQLType varchar(128)
     * @SQLAttributes primary key unique not null
     */
    public $id,

        /**
         * @var string $username
         * @SQLType varchar(128)
         * @SQLAttributes unique not null
         */
        $username,

        $name, $lastName, $otherNames,
        $phone, $email, $notes,
        $role, $key, $salt, $lockedOut = false,
        $isEmailVerified, $isPhoneVerified;

    /**
     * @var string
     * @file
     * @required
     */
    public $image;


    /**
     * User constructor.
     * @param null $username
     */
    public function __construct($username = null)
    {
        if (isset($username))
            $this->username = $username;
    }

    /**
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        $token = (new Cookie)->getCookie('__user');

        return empty($token)
            ? false
            : !is_null( (new DatabaseContext(Claim::class))->first('token', $token));
    }

    /**
     * @param $role
     * @return bool
     */
    public static function isInRole($role)
    {
        return strpos($role, Obj::property((new Authorization)->getSession()->user, 'role')) !== false;
    }

    /**
     * @return mixed
     */
    public static function getName()
    {
        return Obj::property((new Authorization)->getSession()->user, 'name');
    }

    /**
     * @return mixed
     */
    public static function getRole()
    {
        return Obj::property((new Authorization)->getSession()->user, 'role', 'visitor');
    }

    /**
     * @return mixed
     */
    public static function getId()
    {
        return Obj::property((new Authorization)->getSession()->user, 'id');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->name $this->otherNames $this->lastName";
    }
}