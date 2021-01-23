<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Apple tree';
?>
<div class="site-index">
    <?php $form = ActiveForm::begin(); ?>
    <div>
        <?= Html::a(
            'Сгенерировать дерево яблок',
            '/site/generate',
            [
                'class' => 'btn btn-primary'
            ]
        ) ?>
    </div>
    <div>
        <?php

        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'summary' => '',
                'columns' => [
                    'id',
                    'color',
                    'created_at',
                    'fallen_at',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return \common\models\AppleModel::$statusName[$model->status];
                        },
                    ],
                    'eaten',
//                        [
//                            'class' => ActionColumn::className(),
//                            // you may configure additional properties here
//                        ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ' {update} {view} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return '<input id="eatenInput' . $key . '" type="text" size="20">' . Html::a(
                                        '<span class="glyphicon glyphicon-cutlery"></span>',
                                        'eaten?id=' . $model->id,
                                        [
                                            'title' => 'Скушать',
                                            'id' => 'id="eatenA' . $key . '"',
                                            'class' => 'eaten-button',
                                            'subId' => $key
                                        ]
                                    );
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-download"></span>',
                                    'fall?id=' . $model->id,
                                    [
                                        'title' => 'Fall'
                                    ]
                                );
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    'delete?id=' . $model->id,
                                    [
                                        'title' => 'Удалить'
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]
        )
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

$js = <<<JS
        (function(){

            $(document).ready(function() {
    
                $(".eaten-button").on("click", function (e) {
                // e.preventDefault();
                var id = $(this).attr("subId"),
                input = $("#eatenInput"+id);
                $(this).attr("href", $(this).attr("href")+"&val="+input.val())
                
                });
            });
        })(jQuery);
JS;

$this->registerJs($js, View::POS_END);
?>
