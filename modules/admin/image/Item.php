<?php

namespace admin\image;

use Yii;

class Item extends \yii\base\Object
{
    use \admin\storage\ItemTrait;
    
    public function getId()
    {
        return $this->itemArray['id'];
    }
    
    public function getFileId()
    {
        return $this->itemArray['file_id'];
    }
    
    public function getFilterId()
    {
        return $this->itemArray['filter_id'];
    }
    
    public function getSource()
    {
        return ($this->getFile()) ? Yii::$app->storage->httpPath . '/' . $this->getFilterId() . '_' . $this->getFile()->getSystemFileName() : false;
    }
    
    public function getServerSource()
    {
        return ($this->getFile()) ? Yii::$app->storage->serverPath . '/' . $this->getFilterId() . '_' . $this->getFile()->getSystemFileName() : false;
    }
    
    public function getResolutionWidth()
    {
        return $this->itemArray['resolution_width'];
    }
    
    public function getResolutionHeight()
    {
        return $this->itemArray['resolution_height'];
    }
    
    public function getFile()
    {
        return Yii::$app->storage->getFile($this->getFileId());
    }
    
    public function applyFilter($filterName)
    {
        return ($filterItem = Yii::$app->storage->getFiltersArrayItem($filterName)) ? Yii::$app->storage->addImage($this->getFileId(), $filterItem['id']) : false;
    }
    
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'fileId' => $this->getFileId(),
            'filterId' => $this->getFilterId(),
            'source' => $this->getSource(),
            'serverSource' => $this->getServerSource(),
            'resolutionWidth' => $this->getResolutionWidth(),
            'resolutionHeight' => $this->getResolutionHeight(),
        ];
    }
}
