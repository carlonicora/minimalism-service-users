<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Cache;

use CarloNicora\Minimalism\Interfaces\Cache\Abstracts\AbstractCacheBuilderFactory;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderInterface;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;

class UsersCacheFactory extends AbstractCacheBuilderFactory
{
    /**
     * @param int $userId
     * @return CacheBuilderInterface
     */
    public static function user(
        int $userId,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $userId,
        );
    }

    /**
     * @param int $userId
     * @return CacheBuilderInterface
     */
    public static function privateUser(
        int $userId,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $userId,
        )->addContext('private');
    }

    /**
     * @param string $username
     * @return CacheBuilderInterface
     */
    public static function username(
        string $username,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $username,
        );
    }

    /**
     * @param string $username
     * @return CacheBuilderInterface
     */
    public static function privateUsername(
        string $username,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $username,
        )->addContext('private');
    }

    /**
     * @param string $email
     * @return CacheBuilderInterface
     */
    public static function email(
        string $email,
    ): CacheBuilderInterface
    {
        return self::create(
            cacheName: UsersDictionary::User->getIdKey(),
            identifier: $email,
        );
    }
}