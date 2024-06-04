<?php

namespace backend\controllers;

use backend\models\Department;
use backend\models\User;
use backend\models\UserSearch;
use common\models\constants\StatusConstant;
use common\models\constants\UserRoleConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return User::isUserAdmin(Yii::$app->user->identity->_username);
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->_password = Yii::$app->security->generatePasswordHash($model->_password);
                $folder_name = "user_" . $model->email;
                $files = UploadedFile::getInstances($model, 'avatar');
                $url = "";
                if (count($files) > 0) {
                    FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/users_avatar/' . $folder_name, $mode = 0775, $recursive = true);
                    foreach ($files as $file) {
                        $url = Url::to('@backend') . '/web/uploads/users_avatar/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $file->saveAs($url);
                        
                    }
                }
                else
                {
                    $url =  Url::to('@backend') . '/web/uploads/users_avatar/user_no_avatar/User-avatar.png';
                }
                $model->avatar = $url;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $departments = ArrayHelper::map(Department::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $genders = array('Male' => 'Male', 'Female' => 'Female');
        return $this->render('create', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'departments' => $departments,
            'genders' => $genders
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();
        $folder_url = "";

        if ($model->avatar) {
            $folder_url = substr($model->avatar, 0, strripos($model->avatar, '/'));
            $all_files[] = Url::base(TRUE) . "/" . $model->avatar;
            $obj = (object) array('caption' => $model->id . " avatar", 'url' => "", 'key' => $model->id, 'type' => "image");
            $files_type[] = "image";
            $all_files_preview[] = $obj;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->_password = Yii::$app->security->generatePasswordHash($model->_password);
            $files = UploadedFile::getInstances($model, 'avatar');
            if ($files) {
                foreach ($files as $file) {
                    if ($folder_url != '' && file_exists($folder_url)) {
                        $url = $folder_url . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    } else {
                        $folder_name = "thread_" . $model->id;
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/' . $folder_name, $mode = 0775, $recursive = true);
                        $url = Url::to('@backend') . '/web/uploads/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $model->avatar = $url;
                    }
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $departments = ArrayHelper::map(Department::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $genders = array('Male' => 'Male', 'Female' => 'Female');

        return $this->render('update', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'departments' => $departments,
            'genders' => $genders
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
