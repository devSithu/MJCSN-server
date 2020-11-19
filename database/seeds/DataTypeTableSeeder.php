<?php

use Illuminate\Database\Seeder;
use App\Models\Datatype;

class DataTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $key   = 'data_type_id';
        $refl  = new \ReflectionClass(Datatype::class);
        $rules = \collect($refl->getConstants())->where($key, '<>', null);
        $rules->each(function ($item) use ($key) {
            Datatype::firstOrNew([$key => $item[$key]])->fill($item)->save();
        });
    }
}
