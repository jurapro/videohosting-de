<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'description:ntext',
            'amount_of_likes',
            'amount_of_dislikes',
            [
                'attribute' => 'date',
                'format' => ['date', 'dd-MM-Y HH:i']
            ],
            'category.name',

            [
                'attribute' => 'restrictions',
                'value' => function ($data) {
                    return $data->getRestrictionText();
                },

            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
