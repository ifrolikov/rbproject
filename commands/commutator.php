<?php

namespace commands;

use models\Commutator as CommutatorModel;

class Commutator
{
    public function actionSet($id, $value)
    {
        if (!$model = CommutatorModel::findOne(['id' => $id])) {
            $model = new CommutatorModel();
            $model->id = $id;
            $model->name = 'cm '.$id;
        }
        $model->value = $value;
        $model->save();
        return $model;
    }
}