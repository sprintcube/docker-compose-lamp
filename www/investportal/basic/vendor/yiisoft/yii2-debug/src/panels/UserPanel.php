<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\debug\controllers\UserController;
use yii\debug\models\search\UserSearchInterface;
use yii\debug\models\UserSwitch;
use yii\debug\Panel;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Debugger panel that collects and displays user data.
 *
 * @property-read DataProviderInterface $userDataProvider This property is read-only.
 * @property-read Model|UserSearchInterface $usersFilterModel This property is read-only.
 *
 * @author Daniel Gomez Pan <pana_1990@hotmail.com>
 * @since 2.0.8
 */
class UserPanel extends Panel
{
    /**
     * @var array the rule which defines who allowed to switch user identity.
     * Access Control Filter single rule. Ignore: actions, controllers, verbs.
     * Settable: allow, roles, ips, matchCallback, denyCallback.
     * By default deny for everyone. Recommendation: can allow for administrator
     * or developer (if implement) role: ['allow' => true, 'roles' => ['admin']]
     * @see http://www.yiiframework.com/doc-2.0/guide-security-authorization.html
     * @since 2.0.10
     */
    public $ruleUserSwitch = [
        'allow' => false,
    ];
    /**
     * @var UserSwitch object of switching users
     * @since 2.0.10
     */
    public $userSwitch;
    /**
     * @var Model|UserSearchInterface Implements of User model with search method.
     * @since 2.0.10
     */
    public $filterModel;
    /**
     * @var array allowed columns for GridView.
     * @see http://www.yiiframework.com/doc-2.0/yii-grid-gridview.html#$columns-detail
     * @since 2.0.10
     */
    public $filterColumns = [];
    /**
     * @var string|User ID of the user component or a user object
     * @since 2.0.13
     */
    public $userComponent = 'user';
    /**
     * @var string Display Name of the debug panel.
     * @since 2.1.4
     */
    public $displayName = 'User';


    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->isEnabled() || $this->getUser()->isGuest) {
            return;
        }

        $this->userSwitch = new UserSwitch(['userComponent' => $this->userComponent]);
        $this->addAccessRules();

        if (!is_object($this->filterModel)
            && class_exists($this->filterModel)
            && in_array('yii\debug\models\search\UserSearchInterface', class_implements($this->filterModel), true)
        ) {
            $this->filterModel = new $this->filterModel;
        } elseif ($this->getUser() && $this->getUser()->identityClass) {
            if (is_subclass_of($this->getUser()->identityClass, 'yii\db\ActiveRecord')) {
                $this->filterModel = new \yii\debug\models\search\User();
            }
        }
    }

    /**
     * @return User|null
     * @since 2.0.13
     * @throws InvalidConfigException
     */
    public function getUser()
    {
        /* @var $user User */
        return is_string($this->userComponent) ? Yii::$app->get($this->userComponent, false) : $this->userComponent;
    }

    /**
     * Add ACF rule. AccessControl attach to debug module.
     * Access rule for main user.
     * @throws InvalidConfigException
     */
    private function addAccessRules()
    {
        $this->ruleUserSwitch['controllers'] = [$this->module->id . '/user'];

        $this->module->attachBehavior(
            'access_debug',
            [
                'class' => 'yii\filters\AccessControl',
                'only' => [$this->module->id . '/user', $this->module->id . '/default'],
                'user' => $this->userSwitch->getMainUser(),
                'rules' => [
                    $this->ruleUserSwitch,
                ],
            ]
        );
    }

    /**
     * Get model for GridView -> FilterModel
     * @return Model|UserSearchInterface
     */
    public function getUsersFilterModel()
    {
        return $this->filterModel;
    }

    /**
     * Get model for GridView -> DataProvider
     * @return DataProviderInterface
     */
    public function getUserDataProvider()
    {
        return $this->getUsersFilterModel()->search(Yii::$app->request->queryParams);
    }

    /**
     * Check is available search of users
     * @return bool
     */
    public function canSearchUsers()
    {
        return (isset($this->filterModel) &&
            $this->filterModel instanceof Model &&
            $this->filterModel->hasMethod('search')
        );
    }

    /**
     * Check can main user switch identity.
     * @return bool
     * @throws InvalidConfigException
     */
    public function canSwitchUser()
    {
        if ($this->getUser()->isGuest) {
            return false;
        }

        $allowSwitchUser = false;

        $rule = new AccessRule($this->ruleUserSwitch);

        /** @var Controller $userController */
        $userController = null;
        $controller = $this->module->createController('user');
        if (isset($controller[0]) && $controller[0] instanceof UserController) {
            $userController = $controller[0];
        }

        //check by rule
        if ($userController) {
            $action = $userController->createAction('set-identity');
            $user = $this->userSwitch->getMainUser();
            $request = Yii::$app->request;

            $allowSwitchUser = $rule->allows($action, $user, $request) ?: false;
        }

        return $allowSwitchUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->displayName;
    }

    /**
     * {@inheritdoc}
     */
    public function getSummary()
    {
        return Yii::$app->view->render('panels/user/summary', ['panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        return Yii::$app->view->render('panels/user/detail', ['panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $identity = Yii::$app->{$this->userComponent}->identity;

        if (!isset($identity)) {
            return null;
        }

        $rolesProvider = null;
        $permissionsProvider = null;

        try {
            $authManager = Yii::$app->getAuthManager();

            if ($authManager instanceof \yii\rbac\ManagerInterface) {
                $roles = ArrayHelper::toArray($authManager->getRolesByUser($this->getUser()->id));
                foreach ($roles as &$role) {
                    $role['data'] = $this->dataToString($role['data']);
                }
                unset($role);
                $rolesProvider = new ArrayDataProvider([
                    'allModels' => $roles,
                ]);

                $permissions = ArrayHelper::toArray($authManager->getPermissionsByUser($this->getUser()->id));
                foreach ($permissions as &$permission) {
                    $permission['data'] = $this->dataToString($permission['data']);
                }
                unset($permission);

                $permissionsProvider = new ArrayDataProvider([
                    'allModels' => $permissions,
                ]);
            }
        } catch (\Exception $e) {
            // ignore auth manager misconfiguration
        }

        $identityData = $this->identityData($identity);
        foreach ($identityData as $key => $value) {
            $identityData[$key] = VarDumper::dumpAsString($value);
        }

        // If the identity is a model, let it specify the attribute labels
        if ($identity instanceof Model) {
            $attributes = [];

            foreach (array_keys($identityData) as $attribute) {
                $attributes[] = [
                    'attribute' => $attribute,
                    'label' => $identity->getAttributeLabel($attribute),
                ];
            }
        } else {
            // Let the DetailView widget figure the labels out
            $attributes = null;
        }

        return [
            'id' => $identity->getId(),
            'identity' => $identityData,
            'attributes' => $attributes,
            'rolesProvider' => $rolesProvider,
            'permissionsProvider' => $permissionsProvider,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        try {
            $this->getUser();
        } catch (InvalidConfigException $exception) {
            return false;
        }
        return true;
    }

    /**
     * Converts mixed data to string
     *
     * @param mixed $data
     * @return string
     */
    protected function dataToString($data)
    {
        if (is_string($data)) {
            return $data;
        }

        return VarDumper::export($data);
    }

    /**
     * Returns the array that should be set on [[\yii\widgets\DetailView::model]]
     *
     * @param IdentityInterface $identity
     * @return array
     */
    protected function identityData($identity)
    {
        if ($identity instanceof Model) {
            return $identity->getAttributes();
        }

        return get_object_vars($identity);
    }
}
