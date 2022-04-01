<?php

namespace samuelelonghin\btn;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

class Btn extends Widget
{
    /**
     * @var $model BtnInterface
     */
    public $model;
    public $pluginOptions;

    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_REMOVE = 'remove';
    const TYPE_DELETE = 'delete';
    const TYPE_VIEW = 'view';
    const TYPE_INFO = 'info';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_INDEX = 'index';
    const TYPE_CUSTOM = 'custom';
    const TYPE_ACCEPT = 'accept';
    const TYPE_REFUSE = 'refuse';
    const TYPE_JOIN = 'join';

    private $classes = [
        'create' => 'btn-success',
        'update' => 'btn-primary',
        'delete' => 'btn-danger',
        'remove' => 'btn-warning',
        'view' => 'btn-primary',
        'info' => 'btn-secondary',
        'download' => 'btn-secondary',
        'index' => 'btn-pil',
        'expand' => 'btn-pil',
        'add' => 'btn-outline-success',
        self::TYPE_REFUSE => 'btn-outline-danger',
        self::TYPE_ACCEPT => 'btn-outline-primary',
        self::TYPE_JOIN => 'btn-outline-success',
        'custom' => 'btn-secondary',
    ];
    private $icons = [
        'create' => 'plus',
        'update' => 'edit',
        'remove' => 'trash',
        'delete' => 'trash',
        'view' => 'eye',
        'info' => 'info',
        'download' => 'download',
        'index' => 'expand',
        'expand' => 'expand',
        self::TYPE_REFUSE => 'cross',
        self::TYPE_ACCEPT => 'tick',
        self::TYPE_JOIN => 'join',
        'custom' => '',
    ];
    private $messages = [
        'create' => 'Create',
        'update' => 'Update',
        'remove' => 'Remove',
        'delete' => 'Delete',
        'view' => 'View',
        'info' => 'Info',
        'download' => 'Scarica',
        self::TYPE_REFUSE => 'Rifiuta',
        self::TYPE_ACCEPT => 'Accetta',
        self::TYPE_JOIN => 'Iscrivimi',
        'custom' => 'Btn',
    ];
    public $preClass = 'btn';
    public $addClass = '';
    public $btn_class = false;
    public $visible = true;
    public $type = 'custom';
    public $controller = false;
    public $text = true;
    public $icon = false;
    public $content;
    public $options;
    public $pk = 'id';
    public $url = false;
    public $resize = true;
    public $params = false;
    public $action = false;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->visible) {
            switch ($this->type) {
                case Btn::TYPE_DELETE :
                case Btn::TYPE_REMOVE :
                {
                    return Html::a($this->getContent(), $this->getUrl(), [
                        'class' => $this->getClass(),
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]);
                }
                default:
                {
                    return Html::a($this->getContent(), $this->getUrl(), ['class' => $this->getClass()]);
                }
            }
        } else {
            return " ";
        }
    }

    /**
     * @return string[]
     */
    public function getUrl()
	{
		if (!$this->url) {
			if (!$this->action) {
				$this->action = $this->type;
			}

			if (!$this->controller) {
				if ($this->model)
					$this->url = '/' . $this->model::getController() . '/';
			} else
				$this->url = '/' . $this->controller . '/';

			if ($this->action) {
//                $this->url .= '/';
				$this->url .= $this->action;
			}
		}
		if (is_array($this->url)) $this->url = Url::to($this->url);

		$array = [$this->url];
		if ($this->model && $this->model->hasProperty($this->pk))
			$array[$this->pk] = $this->model[$this->pk];
		if ($this->params) {
			$array = array_merge($array, $this->params);
		}
		return $array;
	}
    /**
     * @return string
     */
    public function getClass()
    {
        if (!$this->btn_class) {
            $this->btn_class = $this->classes[$this->type];
        }
        return $this->preClass . ' ' . $this->btn_class . ' ' . $this->addClass;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        if (!$this->content) {
            if ($this->text === true && array_key_exists($this->type, $this->messages)) {
                $this->text = $this->messages[$this->type];
            }
            if ($this->icon === true && array_key_exists($this->type, $this->icons)) {
                $this->icon = $this->icons[$this->type];
            }
            if ($this->text) {
                $this->content .= $this->getText();
            }
            if ($this->icon) {
                $this->content .= $this->getIcon();
            }
        }
        return $this->content;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $class = $this->text ? 'd-lg-none' : '';
        return Html::tag('span', '', ['class' => $class . ' fa fa-' . $this->icon]);
    }

    /**
     * @return string
     */
    public function getText()
    {
        $class = $this->icon ? 'd-none d-lg-block' : '';
        return Html::tag('span', Yii::t('app', $this->text), ['class' => $class]);
    }

}
