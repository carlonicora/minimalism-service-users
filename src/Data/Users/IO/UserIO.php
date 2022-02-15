<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlQueryFactory;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUserIO;
use CarloNicora\Minimalism\Services\Users\Data\Cache\UsersCacheFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\Databases\UsersTable;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use Exception;

class UserIO extends AbstractUserIO
{
    /**
     * @param int $userId
     * @return User
     * @throws MinimalismException
     */
    public function readById(
        int $userId,
    ): User
    {
        return $this->data->read(
            queryFactory: SqlQueryFactory::create(UsersTable::class)
                ->selectAll()
                ->addParameter(UsersTable::userId, $userId),
            cacheBuilder: UsersCacheFactory::user($userId),
            responseType: User::class,
        );
    }

    /**
     * @param string $username
     * @return User
     * @throws MinimalismException
     */
    public function readByUsername(
        string $username,
    ): User
    {
        return $this->data->read(
            queryFactory: SqlQueryFactory::create(UsersTable::class)
                ->selectAll()
                ->addParameter(UsersTable::username, $username),
            cacheBuilder: UsersCacheFactory::username($username),
            responseType: User::class,
        );
    }

    /**
     * @param string $email
     * @return User
     * @throws MinimalismException
     */
    public function readByEmail(
        string $email,
    ): User
    {
        return $this->data->read(
            queryFactory: SqlQueryFactory::create(UsersTable::class)
                ->selectAll()
                ->addParameter(UsersTable::email, $email),
            cacheBuilder: UsersCacheFactory::email($email),
            responseType: User::class,
        );
    }

    /**
     * @param User $user
     * @return User
     */
    public function create(
        User $user
    ): User
    {
        return $this->data->create(
            queryFactory: $user,
        );
    }

    /**
     * @param User $user
     */
    public function update(
        User $user
    ): void
    {
        $this->data->update(
            queryFactory: $user,
            cacheBuilder: UsersCacheFactory::user($user->getId()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::privateUser($user->getId()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::email($user->getEmail()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::username($user->getUsername()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::privateUsername($user->getUsername()),
        );
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function delete(
        User $user,
    ): void
    {
        $this->data->delete(
            queryFactory: $user,
            cacheBuilder: UsersCacheFactory::user($user->getId()),
        );

        $this->cache?->invalidate(UsersCacheFactory::privateUser($user->getId()));
        $this->cache?->invalidate(UsersCacheFactory::email($user->getEmail()));
        $this->cache?->invalidate(UsersCacheFactory::username($user->getUsername()));
        $this->cache?->invalidate(UsersCacheFactory::privateUsername($user->getUsername()));
    }
}