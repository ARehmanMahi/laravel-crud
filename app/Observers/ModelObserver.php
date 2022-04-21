<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * retrieved,
     * creating,
     * created,
     * updating,
     * updated,
     * saving,
     * saved,
     * deleting,
     * deleted,
     * restoring,
     * restored,
     * replicating
     ** /

    /**
     * Handle the Model "creating" event.
     *
     * @param Model $model
     * @return void
     */
    public function creating(Model $model): void
    {
        if (!$model->isDirty('created_by')) {
            $model->created_by = auth()->id();
        }
    }

    /**
     * Handle the Model "updating" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function updating(Model $model): void
    {
        if (!$model->isDirty('updated_by')) {
            $model->updated_by = auth()->id();
        }
    }

    /**
     * Handle the Model "deleting" event.
     *
     * @param  Model  $model
     * @return void
     */
    public function deleting(Model $model): void
    {
        if (!$model->isDirty('deleted_by')) {
            $model->deleted_by = auth()->id();
        }
    }
}
