<?php
namespace App\Traits;

use Illuminate\Http\Response;

trait ModelHelper
{
    /**
     * Build success response
     * @param  string $message
     * @param  string|array $data
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function getFcmTokenByDriver($driver_id)
    {
        if($driver_id){
            $model =  \App\Models\Driver::find($driver_id);
            return is_null($model->device_token)?null:$model->device_token;
        }

        return null;

    }

    /**
     * Build success response
     * @param  string $message
     * @param  string|array $data
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function getFcmTokenBySupervisor($supervisor_id)
    {
        if($supervisor_id){
            $model =  \App\Models\Supervisor::find($supervisor_id);
            return is_null($model->device_token)?null:$model->device_token;
        }

        return null;

    }
}
