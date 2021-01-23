<?php

namespace backend\controllers;

use backend\models\LoginFormAdmin;
use common\models\AppleModel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'generate', 'delete', 'fall', 'eaten'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        AppleModel::updateRotten();
        AppleModel::removeEaten();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => AppleModel::find(),
            ]
        );
        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Generate homepage.
     *
     * @return string
     */
    public function actionGenerate()
    {
        $random = mt_rand(5, 10);
        for ($i = 0; $i < $random; $i++) {
            $newApple = new AppleModel();
            $newApple->color = AppleModel::$colors[mt_rand(0, 3)];
            $newApple->status = mt_rand(0, 2);
            $newApple->created_at = time();
            $newApple->eaten = mt_rand(0, 99);
            $newApple->save();
        }

        $this->redirect('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginFormAdmin();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionDelete($id)
    {
        if ($model = AppleModel::find()->andWhere(['id' => $id])->one()) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionFall($id)
    {
        if ($model = AppleModel::find()->andWhere(['id' => $id])->one()) {
            $model->fallen_at = time();
            $model->update(false, ['fallen_at']);
        }

        return $this->redirect(['index']);
    }

    public function actionEaten($id, $val)
    {
        if ($val > 0 && $model = AppleModel::find()->andWhere(['id' => $id])->one()) {
            $model->eaten += (int)$val;
            $model->eaten > 100 ? $model->eaten = 100 : null;
            $model->update(false, ['eaten']);
        }

        return $this->redirect(['index']);
    }

}
