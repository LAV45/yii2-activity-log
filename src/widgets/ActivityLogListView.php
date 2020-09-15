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
     * @var string
     */
    public $template = '<h4>[ {entity_name}{entity_id} ] {user_name} {action} <span>{created_at}</span>{env}</h4><ul class="details">{data}</ul>';
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
     */
    public function renderView($model, $key, $index, $widget)
    {
        $entity_id = $env = '';
        $entity_name = Html::a(Html::encode($model->entity_name), Url::current([
            'entityName' => $model->entity_name,
            'entityId' => null,
            'page' => null
        ]));
        if ($model->entity_id) {
            $entity_id = ' : ' . Html::a(Html::encode($model->entity_id), Url::current([
                    'entityName' => $model->entity_name,
                    'entityId' => $model->entity_id,
                    'page' => null
                ]));
        }
        $url = Url::current(['userId' => $model->user_id, 'page' => null]);
        $user_name = Html::a(Html::encode($model->user_name), $url);
        $action = Yii::t('lav45/logger', $model->action);
        if ($model->env) {
            $env .= '<small style="float: right;">';
            $url = Url::current(['env' => $model->env, 'page' => null]);
            $env .= Html::a(Html::encode($model->env), $url);
            $env .= '</small>';
        }
        $data = '';
        foreach ($model->getData() as $attribute => $values) {
            $data .= $values->render();
        }
        $params = [
            '{entity_name}' => $entity_name,
            '{entity_id}' => $entity_id,
            '{user_name}' => $user_name,
            '{action}' => $action,
            '{env}' => $env,
            '{data}' => $data,
        ];

        return strtr($this->template, $params);
    }
}