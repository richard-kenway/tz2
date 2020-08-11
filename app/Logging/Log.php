<?php

namespace App\Logging;

class Log
{
    // Добавлено $name для имени модели
    public $name;
    public $date;
    public $column;
    public $old_value;
    public $new_value;

    public function __construct($name, $date, $column, $old_value, $new_value)
    {
        $this->name = $name;
        $this->date = $date;
        $this->column = $column;
        $this->old_value = $old_value;
        $this->new_value = $new_value;
    }

    public function save()
    {
        config(['logging.channels.tz2.path' => storage_path('logs/models/'.$this->name.'.log')]);

        $string = vsprintf("%s - %s %s -> %s", [
            $this->date,
            $this->column,
            $this->old_value,
            $this->new_value
        ]);
        \Illuminate\Support\Facades\Log::channel('tz2')
            ->info($string);
    }
}
