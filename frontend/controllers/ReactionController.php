<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use frontend\models\Reaction;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use frontend\models\ReactionSearch;
use common\models\constants\ReactionTypeConstant;

/**
 * ReactionController implements the CRUD actions for Reaction model.
 */
class ReactionController extends Controller
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
                        ],
                    ],
                ],
            ]
        );
    }
}
