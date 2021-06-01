<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Video */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="video-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:ntext',
            'amount_of_likes',
            'amount_of_dislikes',
            [
                'attribute' => 'date',
                'format' => ['date', 'dd-MM-Y HH:i']
            ],
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'date',
                'format' => ['date', 'dd-MM-Y HH:i']
            ],
            'user.username',
            'text:ntext',
        ],
    ]); ?>

    <?php $form = ActiveForm::begin(['action' => '/comment/create']);
    $comment = new \app\models\Comment();
    ?>

    <?= $form->field($comment, 'id_video')
        ->hiddenInput(['value' => $model->id])->label(false) ?>

    <?= $form->field($comment, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Комментировать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
