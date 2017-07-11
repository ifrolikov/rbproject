<?php

namespace commands;

use models\Sensor as SensorModel;

class Sensor
{
    public function actionSet($id, $value)
    {
        if (!$model = SensorModel::findOne(['id' => $id])) {
            $model = new SensorModel();
            $model->id = $id;
            $model->name = 'sn '.$id;
        }
        $model->value = $value;
        $model->save();
        return $model;
    }
}