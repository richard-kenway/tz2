<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TestObserver
{
    public function updating(Model $model)
    {
        $table = $model->getTable();
        $columns = $model
            ->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($table);

        foreach ($columns as $column) {
            if ($model->isDirty($column)) {
                $old_value = $model->getOriginal($column);
                $new_value = $model->$column;
                $model->createLog(Carbon::now(), $column, $old_value, $new_value);
            }
        }
    }
}
