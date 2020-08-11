<?php

namespace App\Logging;

use App\Observers\TestObserver;
use Carbon\Carbon;

trait ModelLog
{
    // Регистрация обсервера
    public static function boot()
    {
        parent::boot();
        self::observe(new TestObserver());
    }

    // Получение логов для соответствующей модели
    /**
    * @return Log[]
    */
    public function getLogs()
    {
        $model_name = class_basename($this);
        $log_file = file(storage_path('/logs/models/'.$model_name.'.log'));
        $logs = [];

        foreach ($log_file as $line_num => $line) {
            list($date1, $date2, $field, $unformatted_values) = sscanf(
                $line,
                "%s %s - %s %[^\t\n]"
            );

            $values = explode('->', $unformatted_values);

            $logs[] =  new Log(
                $model_name,
                Carbon::parse($date1.' '.$date2),
                $field,
                trim($values[0]),
                trim($values[1])
            );
        }

        return $logs;
    }

    /**
     * @return Log
     */
    public function createLog($date, $column, $old_value, $new_value)
    {
        // class_basename либо get_class (в зависимости от того, что нужно на самом деле)
        $log = new Log(class_basename($this), $date, $column, $old_value, $new_value);
        $log->save();
        return $log;
    }
}
