<?php

use Illuminate\Database\Seeder;

class ImportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            ["name"=>"MATERIAL DE ESCRITORIO","description"=>" "],
            ["name"=>"MATERIAL DE LIMPIEZA","description"=>" "],
            ["name"=>"RESIDUOS RECICLABLES","description"=>" "],
            ["name"=>"ROPA DE TRABAJO","description"=>" "],
            ["name"=>"MANTENIMIENTO","description"=>" "],
            ["name"=>"INSTRUMENTAL MENOR","description"=>" "],
            ["name"=>"MEDICION ","description"=>" "],
            ["name"=>"OTROS","description"=>" "],
            ["name"=>"PRODUCTOS QUIMICOS","description"=>" "],
            ["name"=>"BOTIQUIN","description"=>" "],
        ];
        foreach($categories as $category)
        {
            App\Category::create($category);
        }
    }
}
