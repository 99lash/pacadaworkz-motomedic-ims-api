<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Collection;

class SystemSettingService
{
    /**
     * Get all system settings as key-value pairs.
     *
     * @return array
     */
    public function getAllSettings(): array
    {
        return SystemSetting::all()->pluck('setting_value', 'setting_key')->toArray();
    }

    /**
     * Bulk update or create system settings.
     *
     * @param array $settings
     * @return Collection
     */
    public function updateSettings(array $settings): Collection
    {
        $updatedSettings = collect();

        foreach ($settings as $key => $value) {
            $setting = SystemSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
            $updatedSettings->push($setting);
        }

        return $updatedSettings;
    }
}