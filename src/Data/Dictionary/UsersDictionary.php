<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Dictionary;

use CarloNicora\Minimalism\Services\Users\Data\Users\Databases\UsersTable;

enum UsersDictionary: string
{
    case User='user';

    /**
     * @return string
     */
    public function getIdKey(
    ): string
    {
        return match ($this) {
            self::User => UsersTable::userId->name,
        };
    }

    /**
     * @return string
     */
    public function getEndpoint(
    ): string
    {
        return match ($this) {
            self::User => 'users',
        };
    }

    /**
     * @return string
     */
    public function getPlural(
    ): string
    {
        return $this->getEndpoint();
    }

    /**
     * @return string
     */
    public function getTableName(
    ): string
    {
        return match ($this) {
            self::User => UsersTable::class,
        };
    }
}