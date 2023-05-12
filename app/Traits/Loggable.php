<?php

namespace App\Traits;

use App\Models\ModelLog;
use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\ModelInfo\ModelInfo;


trait Loggable
{
    /**
     * @var Model
     */
    
    static $changedFields = [];

    /*
    static $model;
    public static function bootLoggable()
    {
        static::saved(function($model) {
            static::$model = $model;
            static::afterSaved();
        });
    }

    public static function afterSaved()
    {
        $log = new ModelLog();
        $user = current_user();
        $log->action = static::$model->wasRecentlyCreated ? 'CREATE' : 'UPDATE';
        $log->model = get_class(static::$model);
        $log->model_id = static::$model->getKey();
        $log->ip_address = request()->ip();
        $log->fields = implode('\\n', model_changed_attributes(static::$model));
        $log->user_id = $user ? $user->id : 'NULL';
        $log->save();
    }

    public static function afterDelete()
    {

    }*/

    
    public static function boot() {
        parent::boot();

        static::created(function($item) {
            DB::table('logs')->insert([
                'action' => 'Created',
                'user_id' => Auth::user()->id ?? null,
                'date' => now(),
                'model_type' => get_class($item),
                'model_id' => $item->id
            ]);
        });


        static::updated(function($item){
            foreach($item->changes as $key => $value)
            {
                if ($key != "updated_at" && $key != 'created_at')
                {
                    static::$changedFields[$key] = $value;
                }
            }
            DB::table('logs')->insert([
                'action' => 'Updated',
                'user_id' => Auth::user()->id ?? null,
                'date' => now(),
                'model_type' => get_class($item),
                'model_id' => $item->id,
                'changed_data' => json_encode(static::$changedFields)           
            ]);
        });


        static::deleted(function($item){
            DB::table('logs')->insert([
                'action' => 'Deleted',
                'user_id' => Auth::user()->id ?? null,
                'date' => now(),
                'model_type' => get_class($item),
                'model_id' => $item->id
            ]);
        });
    }

    public function log()
    {
        return $this->morphTo(Log::class, 'model');
    }

}
