<?php
namespace HtUserSocialAuthModule\Manager;

use Hrevert\OauthClient\Manager\UserManagerInterface;
use ZfcUser\Mapper\UserInterface as UserMapper;

class UserManager implements UserManagerInterface
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
     * {@inheritdoc}
     */
    public function findById($id)
    {
        return $this->userMapper->findById($id);
    }
}
