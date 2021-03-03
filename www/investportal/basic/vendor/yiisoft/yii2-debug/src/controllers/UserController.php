<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\controllers;

use Yii;
use yii\debug\models\UserSwitch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * User controller
 *
 * @author Semen Dubina <yii2debug@sam002.net>
 * @since 2.0.10
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->session->hasSessionId) {
            throw new BadRequestHttpException('Need an active session');
        }
        return parent::beforeAction($action);
    }

    /**
     * Set new identity, switch user
     * @return \yii\web\User
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSetIdentity()
    {
        $user_id = Yii::$app->request->post('user_id');

        $userSwitch = new UserSwitch();
        $newIdentity = Yii::$app->user->identity->findIdentity($user_id);
        $userSwitch->setUserByIdentity($newIdentity);
        return Yii::$app->user;
    }

    /**
     * Reset identity, switch to main user
     * @return \yii\web\User
     * @throws \yii\base\InvalidConfigException
     */
    public function actionResetIdentity()
    {
        $userSwitch = new UserSwitch();
        $userSwitch->reset();
        return Yii::$app->user;
    }
}
