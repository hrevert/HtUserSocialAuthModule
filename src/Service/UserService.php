<?php
namespace HtUserSocialAuthModule\Service;

use ZfcUser\Mapper\UserInterface as UserMapper;
use HtLeagueOauthClientModule\Model\Oauth2User;
use ZfcUser\Entity\UserInterface;

class UserService
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * Constructor
     *
     * @param UserMapper $userMapper
     */
    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @param Oauth2User $user
     * @return UserInterface
     */
    public function create(Oauth2User $user)
    {

    }
}
