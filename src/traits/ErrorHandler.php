<?php

namespace luya\traits;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use Curl\Curl;

trait ErrorHandler
{
    public $api = 'http://luya.io/errorapi';

    public $transferException = false;

    public function renderException($exception)
    {
        if ($exception instanceof NotFoundHttpException || !$this->transferException) {
            return parent::renderException($exception);
        }
        
        $curl = new Curl();
        $response = $curl->post(rtrim($this->api, '/').'/create', [
            'error_json' => Json::encode($this->getExceptionArray($exception)),
        ]);
        
        return parent::renderException($exception);
    }

    /**
     * @todo: catch getPrevious() exception
     *
     * @param object $exception Exception
     *
     * @return multitype:multitype:Ambigous <NULL, unknown>
     */
    public function getExceptionArray($exception)
    {
        $_trace = [];
        foreach ($exception->getTrace() as $key => $item) {
            $_trace[$key] = [
                'file' => isset($item['file']) ? $item['file'] : null,
                'line' => isset($item['line']) ? $item['line'] : null,
                'function' => isset($item['function']) ? $item['function'] : null,
                'class' => isset($item['class']) ? $item['class'] : null,
            ];
        }

        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'requestUri' => (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : null,
            'serverName' => (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : null,
            'date' => date('d.m.Y H:i'),
            'trace' => $_trace,
            'ip' => (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null,
            'get' => Yii::$app->request->get(),
            'post' => Yii::$app->request->post(),
        ];
    }
}
