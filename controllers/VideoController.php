<?php

namespace app\controllers;

use app\models\Comment;
use Yii;
use app\models\Video;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'admin',
                    'clearRestriction', 'setViolation',
                    'setShadowBan', 'setBan'],
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['admin',
                            'clearRestriction', 'setViolation',
                            'setShadowBan', 'setBan'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionClearRestriction($id)
    {
        $this->findModel($id)->clearRestriction();
        return $this->redirect(['admin']);
    }

    public function actionSetViolation($id)
    {
        $this->findModel($id)->setViolation();
        return $this->redirect(['admin']);
    }

    public function actionSetShadowBan($id)
    {
        $this->findModel($id)->setShadowBan();
        return $this->redirect(['admin']);
    }

    public function actionSetBan($id)
    {
        $this->findModel($id)->setBan();
        return $this->redirect(['admin']);
    }

    /**
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()
                ->where(['id_user' => Yii::$app->user->getId(), 'restrictions' => [0, 1, 2]])
                ->orderBy("`amount_of_likes`+`amount_of_dislikes` DESC"),
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()
                ->orderBy('date DESC'),
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $video = $this->findModel($id);
        if (in_array($video->restrictions, [1, 3])) {
            throw new NotFoundHttpException;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()
                ->where(['id_video' => $id])
                ->orderBy('date DESC'),
        ]);

        return $this->render('view', [
            'model' => $video,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
            if ($model->validate() && $model->upload()) {
                $model->save(false);
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
