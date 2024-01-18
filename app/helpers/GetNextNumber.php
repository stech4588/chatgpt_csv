<?php

use App\Models\Setting\Setting;

function getNextNumber($moduleName, $name, $userId)
{
    $setting = Setting::where(['module_name' => $moduleName, 'name' => $name])->where('created_by', $userId)->withTrashed()->first();

    if ($setting) {
        $nextNo = $setting->value + 1;
        $setting->update(['value' => $nextNo]);
        return $nextNo;
    } else {
        return [
            'error' => config('constants.not_found')('Setting'),
        ];
    }
}
