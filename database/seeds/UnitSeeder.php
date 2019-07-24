<?php

use Illuminate\Database\Seeder;
use App\Unit;
class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $unidades = [
            ["name"=>"METRO","short_name"=>"m"],
            ["name"=>"KILOGRAMO","short_name"=>"kg"],
            ["name"=>"METRO CUADRADO","short_name"=>"m2"],
            ["name"=>"METRO CÚBICO","short_name"=>"m3"],
            ["name"=>"UNIDAD","short_name"=>"u"],
            ["name"=>"LIBRA","short_name"=>"lb"],
            ["name"=>"LITRO","short_name"=>"lt"],
            ["name"=>"GALON","short_name"=>"GALON "],
            ["name"=>"FRASCO","short_name"=>"FRASCO"],
            ["name"=>"BOLSA","short_name"=>"BOLSA"],
            ["name"=>"CAJA","short_name"=>"CAJA"],
            ["name"=>"HOJA","short_name"=>"HOJA"],
            ["name"=>"PAQUETE","short_name"=>"PAQUETE"],
            ["name"=>"PIE TABLAR","short_name"=>"PIE TABLAR"],
            ["name"=>"PAR","short_name"=>"PAR "],
            ["name"=>"PIEZA","short_name"=>"PIEZA"],
            ["name"=>"QUINTAL","short_name"=>"QUINTAL"],
            ["name"=>"ROLLO","short_name"=>"ROLLO"],
            ["name"=>"JUEGO","short_name"=>"JUEGO"],
            ["name"=>"CENTIMETRO","short_name"=>"cm"],

        ];
        foreach($unidades as $unidad)
        {
            Unit::create($unidad);
        }

    }
}
