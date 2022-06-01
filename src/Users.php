<?php

namespace CarloNicora\Minimalism\Services\Users;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Factories\ServiceFactory;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Interfaces\Security\Interfaces\SecurityInterface;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserInterface;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserLoaderInterface;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserServiceInterface;
use CarloNicora\Minimalism\Services\Path;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\MinimalismUser;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use Exception;

class Users extends AbstractService implements UserServiceInterface, UserLoaderInterface
{
    /** @var User|null */
    private User|null $currentUser = null;

    /**
     * @param Path $path
     * @param EncrypterInterface $encrypter
     * @param SecurityInterface $authorisation
     */
    public function __construct(
        private readonly Path               $path,
        private readonly EncrypterInterface $encrypter,
        private readonly SecurityInterface  $authorisation,
    )
    {
    }

    /**
     * @param ServiceFactory $services
     * @return void
     * @throws MinimalismException
     * @throws Exception
     */
    public function postIntialise(
        ServiceFactory $services,
    ): void
    {
        $this->load();
    }

    /**
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function getUserUrlById(
        int $userId,
    ): string
    {
        return $this->path->getUrl()
            . UsersDictionary::User->getEndpoint()
            . '/' . $this->encrypter->encryptId($userId);
    }

    /**
     * @param User[] $users
     * @return string
     * @throws Exception
     */
    public function getUserUrlByIds(
        array $users,
    ): string
    {
        $response = $this->path->getUrl()
            . UsersDictionary::User->getEndpoint()
            . '/';

        $separator = '?';
        foreach ($users as $user) {
            $response  .= $separator . 'userIds[]=' . $this->encrypter->encryptId($user->getId());
            $separator = '&';
        }

        return $response;
    }

    /**
     * @param User $user
     * @return string
     */
    public function getUserUrl(
        User $user,
    ): string
    {
        return $this->path->getUrl()
            . UsersDictionary::User->getEndpoint()
            . '/@' . $user->getUsername();
    }

    /**
     * @return void
     * @throws MinimalismException
     * @throws Exception
     */
    public function load(): void
    {
        if ($this->authorisation->isUser()) {
            $this->currentUser = $this->objectFactory->create(UserIO::class)->readById(
                userId: $this->authorisation->getUserId(),
            );
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->currentUser->getId();
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        if (! $this->authorisation->isUser()) {
            return null;
        }

        return $this->currentUser->getEmail();
    }

    /**
     * @param string $attributeName
     * @return mixed
     */
    public function getAttribute(
        string $attributeName,
    ): mixed
    {
        if (! $this->authorisation->isUser()) {
            return null;
        }

        if (strtolower($attributeName) === 'username') {
            return $this->currentUser->getUsername();
        }

        return $this->currentUser->getSingleMeta($attributeName);
    }

    /**
     * @return bool
     */
    public function isVisitor(): bool
    {
        return ! $this->authorisation->isUser();
    }

    /**
     * @param int $id
     * @return UserInterface|null
     * @throws MinimalismException
     * @throws Exception
     */
    public function byId(int $id): ?UserInterface
    {
        $userIO = $this->objectFactory->create(className: UserIO::class);
        return $this->buildMinimalismUser($userIO->readById($id));
    }

    /**
     * @param string $email
     * @return UserInterface|null
     * @throws MinimalismException
     * @throws Exception
     */
    public function byEmail(string $email): ?UserInterface
    {
        $userIO = $this->objectFactory->create(className: UserIO::class);
        return $this->buildMinimalismUser($userIO->readByEmail($email));
    }

    /**
     * @param string $userName
     * @return UserInterface|null
     * @throws MinimalismException
     * @throws Exception
     */
    public function byUserName(string $userName): ?UserInterface
    {
        $userIO = $this->objectFactory->create(className: UserIO::class);
        return $this->buildMinimalismUser($userIO->readByUsername($userName));
    }

    /**
     * @param User $userDataObject
     * @return MinimalismUser
     */
    private function buildMinimalismUser(
        User $userDataObject
    ): MinimalismUser
    {
        return new MinimalismUser(
            id: $userDataObject->getId(),
            userName: $userDataObject->getUsername(),
            email: $userDataObject->getEmail(),
            attributes: [
                'avatar' => $userDataObject->getAvatar(),
                'createdAt' => $userDataObject->getCreatedAt(),
                'updatedAt' => $userDataObject->getUpdatedAt(),
            ]
        );
    }
}