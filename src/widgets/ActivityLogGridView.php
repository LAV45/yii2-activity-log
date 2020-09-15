<?php

namespace lav45\activityLogger\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use lav45\activityLogger\modules\models\ActivityLogViewModel;

/**
 * Class LogGridView
 * @package lav45\activityLogger\widgets
 */
class ActivityLogGridView extends GridView
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (empty($this->columns)) {
            $this->columns = [
                [
                    'class' => SerialColumn::class,
                    'header' => '№',
                ],
                [
                    'label' => Yii::t('lav45/logger', 'Created'),
                    'format' => 'datetime',
                    'value' => 'created_at',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'style' => 'max-width: 136px;',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'label' => Yii::t('lav45/logger', 'Action'),
                    'value' => static function ($model) {
                        return Yii::t('lav45/logger', $model->action);
                    },
                    'headerOptions' => [
                        'class' => 'text-center w-1 d-none d-md-table-cell',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center d-none d-md-table-cell',
                    ],
                ],
                [
                    'label' => Yii::t('lav45/logger', 'User name'),
                    'format' => 'raw',
                    'value' => static function ($model) {
                        $url = Url::current(['userId' => $model->user_id, 'page' => null]);
                        return Html::a(Html::encode($model->user_name), $url);
                    },
                    'headerOptions' => [
                        'class' => 'text-center d-none d-sm-table-cell',
                        'style' => 'max-width: 170px;',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center d-none d-sm-table-cell',
                    ],
                ],
                [
                    'label' => Yii::t('lav45/logger', 'Data'),
                    'format' => 'raw',
                    'value' => static function ($log) {
                        /** @var ActivityLogViewModel $log */
                        $data = [];
                        foreach ($log->getData() as $attribute => $values) {
                            $data[] = $values->render();
                        }

                        return Html::ul($data, [
                            'class' => 'list-unstyled',
                            'encode' => false,
                        ]);
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                ],
            ];
        }

        parent::init();
    }
}