<?php

use Illuminate\Database\Seeder;

class ImportStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $storages = [
            ['name'=>'Oficina Central ','description'=>' '],
            ['name'=>'Planta Achacachi','description'=>' '],
            ['name'=>'Planta Caranavi','description'=>' '],
            ['name'=>'Planta Challapata','description'=>' '],
            ['name'=>'Planta San Lorenzo','description'=>' '],
            ['name'=>'Planta Villa 14 de Septiembre','description'=>' '],
            ['name'=>'Planta Valle Sacta','description'=>' '],
            ['name'=>'Planta Ivirgarzama','description'=>' '],
            ['name'=>'Planta San Andres','description'=>' '],
            ['name'=>'Planta Riberalta - Escritorio','description'=>' '],
            ['name'=>'Planta El Sena','description'=>' '],
            ['name'=>'Planta Cobija','description'=>' '],
            ['name'=>'Planta Derivados El Alto','description'=>' '],
            ['name'=>'Planta Zamusavety','description'=>' '],
            ['name'=>'Planta Irupana','description'=>' '],
            ['name'=>'Planta Monteagudo','description'=>' '],
            ['name'=>'Planta Stevia','description'=>' '],
            ['name'=>'Planta Riberalta - Combustible','description'=>' '],
            ['name'=>'Planta Cobija-Escritorio','description'=>' '],
            ['name'=>'Planta Cobija-Combustible','description'=>' '],
            ['name'=>'Planta Shinahota','description'=>' '],
            ['name'=>'Planta Liofilizadora Palos Blancos','description'=>' '],
            ['name'=>'Planta Liofilizadora Villa 14','description'=>' '],
        ];

        foreach($storages as $storage)
        {
            App\Storage::create($storage);
        }
    }
}
