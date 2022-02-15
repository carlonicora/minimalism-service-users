<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Cache;

use CarloNicora\Minimalism\Services\Cacher\Builders\CacheBuilder;
use CarloNicora\Minimalism\Services\Cacher\Factories\CacheBuilderFactory;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;

class UsersCacheFactory extends CacheBuilderFactory
{
    /**
     * @param int $userId
     * @return CacheBuilder
     */
    public static function user(
        int $userId,
    ): CacheBuilder
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $userId,
        );
    }

    /**
     * @param int $userId
     * @return CacheBuilder
     */
    public static function privateUser(
        int $userId,
    ): CacheBuilder
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $userId,
        )->addContext('private');
    }

    /**
     * @param string $username
     * @return CacheBuilder
     */
    public static function username(
        string $username,
    ): CacheBuilder
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $username,
        );
    }

    /**
     * @param string $username
     * @return CacheBuilder
     */
    public static function privateUsername(
        string $username,
    ): CacheBuilder
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $username,
        )->addContext('private');
    }

    /**
     * @param string $email
     * @return CacheBuilder
     */
    public static function email(
        string $email,
    ): CacheBuilder
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $email,
        );
    }
}