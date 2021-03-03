<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * UserSwitch is a model used to temporary logging in another user
 *
 * @author Semen Dubina <yii2debug@sam002.net>
 * @since 2.0.10
 */
class UserSwitch extends Model
{
    /**
     * @var User user which we are currently switched to
     */
    private $_user;
    /**
     * @var User the main user who was originally logged in before switching.
     */
    private $_mainUser;


    /**
     * @var string|User ID of the user component or a user object
     * @since 2.0.13
     */
    public $userComponent = 'user';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'mainUser'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user' => 'Current User',
            'mainUser' => 'Main User',
        ];
    }

    /**
     * Get current user
     * @return null|User
     * @throws \yii\base\InvalidConfigException
     */
    public function getUser()
    {
        if ($this->_user === null) {
            /* @var $user User */
            $this->_user = is_string($this->userComponent) ? Yii::$app->get($this->userComponent,
                false) : $this->userComponent;
        }
        return $this->_user;
    }

    /**
     * Get main user
     * @return User
     * @throws \yii\base\InvalidConfigException
     */
    public function getMainUser()
    {
        $currentUser = $this->getUser();

        if ($this->_mainUser === null && $currentUser->getIsGuest() === false) {
            $session = Yii::$app->getSession();
            if ($session->has('main_user')) {
                $mainUserId = $session->get('main_user');
                $mainIdentity = call_user_func([$currentUser->identityClass, 'findIdentity'], $mainUserId);
            } else {
                $mainIdentity = $currentUser->identity;
            }

            $mainUser = clone $currentUser;
            $mainUser->setIdentity($mainIdentity);
            $this->_mainUser = $mainUser;
        }

        return $this->_mainUser;
    }

    /**
     * Switch user
     * @param User $user
     * @throws \yii\base\InvalidConfigException
     */
    public function setUser(User $user)
    {
        // Check if user is currently active one
        $isCurrent = ($user->getId() === $this->getMainUser()->getId());
        // Switch identity
        $this->getUser()->switchIdentity($user->identity);
        if (!$isCurrent) {
            Yii::$app->getSession()->set('main_user', $this->getMainUser()->getId());
        } else {
            Yii::$app->getSession()->remove('main_user');
        }
    }

    /**
     * Switch to user by identity
     * @param IdentityInterface $identity
     * @throws \yii\base\InvalidConfigException
     */
    public function setUserByIdentity(IdentityInterface $identity)
    {
        $user = clone $this->getUser();
        $user->setIdentity($identity);
        $this->setUser($user);
    }

    /**
     * Reset to main user
     */
    public function reset()
    {
        $this->setUser($this->getMainUser());
    }

    /**
     * Checks if current user is main or not.
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isMainUser()
    {
        $user = $this->getUser();
        if ($user->getIsGuest()) {
            return true;
        }
        return ($user->getId() === $this->getMainUser()->getId());
    }
}
