<?php

namespace commands;

use models\Settings as SettingsModel;

class Settings
{
    public function actionSet($id, $value)
    {
        if (!$model = SettingsModel::findOne(['id' => $id])) {
            $model = new SettingsModel();
            $model->id = $id;
        }
        $model->value = $value;
        $model->save();
        return $model;
    }
}