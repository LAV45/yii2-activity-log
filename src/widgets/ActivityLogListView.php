<?php

namespace lav45\activityLogger\widgets;

use Yii;
use yii\di\Instance;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\widgets\ListView;
use lav45\activityLogger\modules\models\ActivityLog;

/**
 * Class LogListView
 * @package lav45\activityLogger\widgets
 */
class ActivityLogListView extends ListView
{
    /**
     * @var string|array|Formatter
     */
    private $formatter = 'formatter';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->formatter = Instance::ensure($this->formatter, Formatter::class);
        $this->itemView = [$this, 'renderView'];
    }

    /**
     * @param ActivityLog $model
     * @param mixed $key
     * @param int $index
     * @param self $widget
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function renderView($model, $key, $index, $widget)
    {
        $str = '<h4>';
        $str .= '[ ';
        $str .= Html::a(Html::encode($model->entity_name), Url::current([
            'entityName' => $model->entity_name,
            'entityId' => null,
            'page' => null
        ]));
        if ($model->entity_id) {
            $str .= ' : ' . Html::a(Html::encode($model->entity_id), Url::current([
                    'entityName' => $model->entity_name,
                    'entityId' => $model->entity_id,
                    'page' => null
                ]));
        }
        $str .= ' ]';

        $url = Url::current(['userId' => $model->user_id, 'page' => null]);
        $action = Yii::t('lav45/logger', $model->action);
        $str .= Html::a(Html::encode($model->user_name), $url) . ' ' . $action;

        $str .= '<span>' . $this->formatter->asDatetime($model->created_at) . '</span>';

        if ($model->env) {
            $str .= '<small style="float: right;">';
            $url = Url::current(['env' => $model->env, 'page' => null]);
            $str .= Html::a(Html::encode($model->env), $url);
            $str .= '</small>';
        }
        $str .= '</h4>';
        $str .= '<ul class="details">';
        foreach ($model->getData() as $attribute => $values) {
            $str .= $values->render();
        }
        $str .= '</ul>';

        return $str;
    }
}