<?php

class savedview extends BaseModel
{
    /**
     * enable timestamps
     */
    public static $timestamps = true;

    /**
     * get saved view bloc
     * @return Bloc
     */
    public function bloc()
    {
        return $this->belongs_to('Bloc');
    }

    /**
     * return save directory name
     * @return interger
     */
    public function getDirectoryNum()
    {
        return floor($this->id/100);
    }


    /**
     * return directory
     * @return string       directory name
     */
    public function getDirectory($type='svg')
    {
        $dir= path('public').'./'.$type.'/view/'.$this->getDirectoryNum() .'/';
        if (!is_dir($dir)) mkdir($dir, 0777);
        return $dir;
    }

    /**
     * return saved view url (autoloaded with presenter)
     * @return string
     */
    public function get_url()
    {
            return URL::to_route('saved_show', array( Str::slug($this->bloc->location->name),  $this->id ));
    }

    /**
     * return localized created date (autoloaded with presenter)
     * @return string
     */
    public function get_created_at_localized()
    {
        return  date( Lang::line('date.complete')->get() ,strtotime($this->created_at) );
    }

}
