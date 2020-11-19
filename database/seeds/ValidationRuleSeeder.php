<?php

use Illuminate\Database\Seeder;
use App\Models\ValidationRule;

class ValidationRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $key   = 'validation_rule_id';
        $refl  = new \ReflectionClass(ValidationRule::class);
        $rules = \collect($refl->getConstants())->where($key, '<>', null);
        $rules->each(function ($item) use ($key) {
            ValidationRule::firstOrNew([$key => $item[$key]])->fill($item)->save();
        });
    }
}
