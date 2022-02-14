<?php
namespace CarloNicora\Minimalism\Services\Users;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Factories\ServiceFactory;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Interfaces\Security\Interfaces\SecurityInterface;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserRoleInterface;
use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserServiceInterface;
use CarloNicora\Minimalism\Services\Path;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use Exception;

class Users extends AbstractService implements UserServiceInterface, UserRoleInterface
{
    /** @var User|null  */
    private ?User $user=null;

    /**
     * @param Path $path
     * @param EncrypterInterface $encrypter
     * @param SecurityInterface $authorisation
     */
    public function __construct(
        private Path $path,
        private EncrypterInterface $encrypter,
        private SecurityInterface $authorisation,
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
        $this->user = $this->objectFactory->create(UserIO::class)->readById(
            userId: $this->authorisation->getUserId(),
        );
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
        foreach ($users as $user){
            $response .= $separator . 'userIds[]=' . $this->encrypter->encryptId($user->getId());
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
    public function load(
    ): void
    {
        if ($this->authorisation->isUser()) {
            $this->user = $this->objectFactory->create(UserIO::class)->readById(
                userId: $this->authorisation->getUserId(),
            );
        }
    }

    /**
     * @return int
     */
    public function getId(
    ): int
    {
        return $this->user->getId();
    }

    /**
     * @return string|null
     */
    public function getEmail(
    ): ?string
    {
        if (!$this->authorisation->isUser()) {
            return null;
        }

        return $this->user->getEmail();
    }

    /**
     * @param string $attributeName
     * @return mixed
     */
    public function getAttribute(
        string $attributeName,
    ): mixed
    {
        if (!$this->authorisation->isUser()) {
            return null;
        }

        if (strtolower($attributeName) === 'username') {
            return $this->user->getUsername();
        }

        return $this->user->getSingleMeta($attributeName);
    }

    /**
     * @return bool
     */
    public function isVisitor(
    ): bool
    {
        return !$this->authorisation->isUser();
    }
}