<?php

namespace App\Traits;

trait CreateMany
{
    // Makes use of fillable so ambiguous fields don't get saved in DB (Similar To Eloquent Create)
    public static function createMany(array $data = [])
    {
        $model = (new static);

        $now = current_mysql_date_time();

        foreach ($data as $k => $row) {
            foreach ($row as $key => $value) {
                if (!in_array($key, $model->fillable, true)) {
                    unset($data[$k][$key]);
                }

                $data[$k]['created_at'] = $now;
                $data[$k]['updated_at'] = $now;
            }
        }

        $model::insert($data);
    }

}
