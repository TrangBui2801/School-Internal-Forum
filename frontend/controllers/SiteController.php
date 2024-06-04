<?php

namespace frontend\controllers;

use backend\models\FirebaseToken;
use Codeception\Util\HttpCode;
use common\models\constants\StatusConstant;
use common\models\constants\UserRoleConstant;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\Auth;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\User;
use yii\authclient\AuthAction;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'auth', 'register-firebase', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $auth = Auth::find()->where(['source' => $client->getId(), 'sourceId' => $attributes['id'],])->one();
        if (Yii::$app->user->isGuest) {
            $isUserExist = true;
            if ($auth) { // login
                $user = $auth->user;
                if ($user) {
                    Yii::$app->user->login($user);
                } else {
                    $isUserExist = false;
                }
            } else { // signup
                if (User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->session->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $isUserExist = false;
                }
            }
            if (!$isUserExist) {
                $password = Yii::$app->security->generateRandomString(6);
                $user = new User();
                $user->_username = strtolower($attributes['given_name']) . strtolower($attributes['family_name']) .  '_' . strtotime(date('d-m-Y'));
                $user->_password = Yii::$app->security->generatePasswordHash($password);
                $user->full_name = $attributes['name'];
                $user->email = $attributes['email'];
                $user->role = UserRoleConstant::USER;
                $user->avatar = Url::to('@backend') . '/web/uploads/users_avatar/user_no_avatar/User-avatar.png';
                $user->status = StatusConstant::ACTIVE;
                if ($user->save()) {
                    $auth = new Auth(['userId' => $user->id, 'source' => $client->getId(), 'sourceId' => (string)$attributes['id'],]);
                    if ($auth->save()) {
                        Yii::$app->user->login($user);
                    } else {
                        Yii::$app->session->setFlash('error', $auth->getErrors());
                    }
                } else {
                    Yii::$app->session->setFlash('error', $auth->getErrors());
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionRegisterFirebase()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $data = $this->request->post();
            $firebaseToken = $data['token'];
            $deviceType = $data['deviceType'];
            $token = FirebaseToken::find()->where(['=', 'deviceToken', $firebaseToken])->andWhere(['=', 'userId', Yii::$app->user->identity->id])->one();
            if (!$token) {
                $token = new FirebaseToken();
                $token->userId =  Yii::$app->user->identity->id;
                $token->deviceToken = $firebaseToken;
                $token->deviceType = $deviceType;
                $token->deviceId = Yii::$app->security->generateRandomString(16);
                $token->status = StatusConstant::ACTIVE;
                $token->save();
            }
        }
        return [
            'code' => HttpCode::OK
        ];
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
