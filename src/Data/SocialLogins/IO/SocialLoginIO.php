<?php
namespace CarloNicora\Minimalism\Services\Users\Data\SocialLogins\IO;

use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Services\MySQL\Factories\SqlQueryFactory;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUserIO;
use CarloNicora\Minimalism\Services\Users\Data\SocialLogins\Databases\SocialLoginsTable;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;

class SocialLoginIO extends AbstractUserIO
{
    /**
     * @param User $user
     * @return bool
     * @throws MinimalismException
     */
    public function hasSocialLogins(
        User $user,
    ): bool
    {
        $socialLogins = $this->data->read(
            queryFactory: SqlQueryFactory::create(SocialLoginsTable::class)
                ->addParameter(SocialLoginsTable::userId, $user->getId()),
        );

        return $socialLogins !== [];
    }
}