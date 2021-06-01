<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\grid\GridView; ?>

<div class="site-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => function ($data) {
                    return \yii\helpers\Html::a($data->title, '/video/view/?id=' . $data->id);
                },
            ],
            [
                'attribute' => 'date',
                'format' => ['date', 'dd-MM-Y HH:i']
            ],
            'title',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<video src='$data->file' controls></video>";
                },

            ],
        ],
    ]); ?>
</div>
