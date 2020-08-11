<?php

namespace Tests\Feature;

use App\Models\Example;
use App\Models\NewExample;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogTest extends TestCase
{
    private $model;
    private $column;
    private $old_value;
    private $new_value;

    public function setUp() : void
    {
        parent::setUp();

        $this->column = 'field_two';
        $this->old_value = 'my old value';
        $this->new_value = 'новое значение';

        $this->model = new NewExample();
        $this->model->field_one = $this->old_value;
        $this->model->field_two = $this->old_value;
        $this->model->save();
    }

    /**
     * @return void
     */
    public function testOldValue()
    {
        $this->assertTrue($this->model->field_one == $this->old_value);
    }

    /**
     * Изменение модели и запись в лог
     *
     * @return void
     */
    public function testUpdate()
    {
        $column = $this->column;

        $this->model->$column = $this->new_value;
        $this->model->save();

        $this->assertTrue($this->model->$column == $this->new_value);
    }

    /**
     * Проверка парсинга логов
     *
     * @return void
     */
    public function testGetLogs()
    {
        $logs = $this->model->getLogs();
        $log = $logs[count($logs)-1];
        $model_name = class_basename($this->model);

        self::assertTrue($log->name == $model_name);
        self::assertTrue($log->column == $this->column);
        self::assertTrue($log->old_value == $this->old_value);
        self::assertTrue($log->new_value == $this->new_value);
    }
}
