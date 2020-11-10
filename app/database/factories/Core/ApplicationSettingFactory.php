<?php

namespace Database\Factories\Core;

use App\Models\Core\ApplicationSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationSettingFactory extends Factory
{

    protected $model = ApplicationSetting::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->name,
            'value' => $this->faker->name,
        ];
    }
}
