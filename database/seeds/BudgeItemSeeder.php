<?php

use Illuminate\Database\Seeder;
use App\BudgetItem;
class BudgeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $partidas = [
            ["number"=>311,"name"=>"ALIMENTOS Y BEBIDAS PARA PERSONAS, DESAYUNO ESCOLAR Y OTROS"],
            ["number"=>312,"name"=>'ALIMENTOS PARA ANIMALES'],
            ["number"=>313,"name"=>"PRODUCTOS AGRÍCOLAS, PECUARIOS Y FORESTALES"],
            ["number"=>321,"name"=>'PAPEL'],
            ["number"=>322,"name"=>'PRODUCTOS DE ARTES GRÁFICAS'],
            ["number"=>323,"name"=>"LIBROS, MANUALES Y REVISTAS"],
            ["number"=>324,"name"=>'TEXTOS DE ENSEÑANZA'],
            ["number"=>325,"name"=>'PERIÓDICOS Y BOLETINES'],
            ["number"=>331,"name"=>"HILADOS, TELAS, FIBRAS Y ALGODÓN"],
            ["number"=>332,"name"=>'CONFECCIONES TEXTILES'],
            ["number"=>333,"name"=>'PRENDAS DE VESTIR'],
            ["number"=>334,"name"=>'CALZADOS'],
            ["number"=>341,"name"=>"COMBUSTIBLES, LUBRICANTES, DERIVADOS Y OTRAS FUENTES DE ENERGÍA"],
            ["number"=>342,"name"=>'PRODUCTOS QUÍMICOS Y FARMACÉUTICOS'],
            ["number"=>343,"name"=>'LLANTAS Y NEUMÁTICOS'],
            ["number"=>344,"name"=>'PRODUCTOS DE CUERO Y CAUCHO'],
            ["number"=>345,"name"=>'PRODUCTOS DE MINERALES NO METÁLICOS Y PLÁSTICOS'],
            ["number"=>346,"name"=>'PRODUCTOS METÁLICOS'],
            ["number"=>347,"name"=>'MINERALES'],
            ["number"=>348,"name"=>'HERRAMIENTAS MENORES'],
            ["number"=>349,"name"=>'MATERIAL Y EQUIPO MILITAR'],
            ["number"=>391,"name"=>'MATERIAL DE LIMPIEZA E HIGIENE'],
            ["number"=>392,"name"=>'MATERIAL DEPORTIVO Y RECREATIVO'],
            ["number"=>393,"name"=>'UTENSILIOS DE COCINA Y COMEDOR'],
            ["number"=>394,"name"=>'INSTRUMENTAL MENOR MÉDICO-QUIRÚRGICO'],
            ["number"=>395,"name"=>'ÚTILES DE ESCRITORIO Y OFICINA'],
            ["number"=>396,"name"=>"ÚTILES EDUCACIONALES, CULTURALES Y DE CAPACITACIÓN"],
            ["number"=>397,"name"=>'ÚTILES Y MATERIALES ELÉCTRICOS'],
            ["number"=>398,"name"=>'OTROS REPUESTOS Y ACCESORIOS'],
            ["number"=>399,"name"=>'OTROS MATERIALES Y SUMINISTROS'],
        ];

        foreach($partidas as $partida){
            BudgetItem::create($partida);
        }
    }
}
