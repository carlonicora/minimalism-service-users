<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheBuilderInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlQueryFactoryInterface;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlQueryFactory;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUserIO;
use CarloNicora\Minimalism\Services\Users\Data\Cache\UsersCacheFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\Databases\UsersTable;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;

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
     * @param SqlDataObjectInterface|SqlQueryFactoryInterface|array $dataObject
     * @param CacheBuilderInterface|null $cache
     */
    public function update(
        SqlDataObjectInterface|SqlQueryFactoryInterface|array $dataObject,
        ?CacheBuilderInterface                                $cache = null
    ): void
    {
        parent::update(
            dataObject: $dataObject,
            cache: UsersCacheFactory::user($dataObject->getId()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::privateUser($dataObject->getId()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::email($dataObject->getEmail()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::username($dataObject->getUsername()),
        );

        $this->cache?->invalidate(
            builder: UsersCacheFactory::privateUsername($dataObject->getUsername()),
        );
    }

    /**
     * @param User|SqlDataObjectInterface|SqlQueryFactoryInterface|array $dataObjectOrQueryFactory
     * @param CacheBuilderInterface|null $cache
     */
    public function delete(
        User|SqlDataObjectInterface|SqlQueryFactoryInterface|array $dataObjectOrQueryFactory,
        ?CacheBuilderInterface                                     $cache = null
    ): void
    {
        parent::delete(
            dataObjectOrQueryFactory: $dataObjectOrQueryFactory,
            cache: UsersCacheFactory::user($dataObjectOrQueryFactory->getId()),
        );

        $this->cache?->invalidate(UsersCacheFactory::privateUser($dataObjectOrQueryFactory->getId()));
        $this->cache?->invalidate(UsersCacheFactory::email($dataObjectOrQueryFactory->getEmail()));
        $this->cache?->invalidate(UsersCacheFactory::username($dataObjectOrQueryFactory->getUsername()));
        $this->cache?->invalidate(UsersCacheFactory::privateUsername($dataObjectOrQueryFactory->getUsername()));
    }
}