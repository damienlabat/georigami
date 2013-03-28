<?php

class savedview extends BaseModel
{
    public static $timestamps = true;

    public function bloc()
    {
        return $this->belongs_to('Bloc');
    }

    public function getDirectoryNum()
    {
        return floor($this->id/100);
    }

    public function getDirectory()
    {
        $dir= path('public').'./svg/view/'.$this->getDirectoryNum() .'/';
        if (!is_dir($dir)) mkdir($dir, 0777);
        return $dir;
    }

}
