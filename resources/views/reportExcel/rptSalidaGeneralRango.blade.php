 @php

$user= DB::table('rrhh.employees')
                ->where('id','=',Auth::user()->usr_prs_id)
                ->first();
$storage=Auth::user()->getStorage();
$almacen = DB::table('sisme.storages')->select('name')->get();
                // ->where('prs_id','=',Auth::user()->usr_prs_id)
$tam=count($almacen) + 4 
@endphp
<html>
<table>
  <td><img src="img/logo_small.jpg" width="100" /></td>
  <td colspan="6" style="text-align:center; vertical-align: middle;"><h1>EMPRESA BOLIVIANA DE ALIMENTOS</h1></td>
</table>
<table>
    <tr>
       <td colspan="7" style="text-align:center;"><strong><h7>SALIDA GENERAL {{$storage->description}}</h7></strong></td>
    </tr>
    <tr>
      <td colspan="7" align="center"><strong><h1>DE: {{$date}}</h1></strong></td>
    </tr>
    <tr>
      <td colspan="7"><strong><h1>GENERADO POR: {{$user->first_name}} {{$user->second_name}} {{$user->last_name}} {{$user->mother_last_name}}</h1></strong></td>
   </tr>
</table>
<table border="1">
  <thead class="table_head">
   <tr>
     <td align="center" width="10" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>N°</strong></td>
      <td align="center" width="30" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>ALMACEN</strong></td>
      <td align="center" width="15" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>N° SALIDA</strong></td>
      <td align="center" width="20" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>CODIGO</strong></td>
      <td align="center" width="35" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>ARTICULO</strong></td>
      <td align="center" width="20" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>CANTIDAD</strong></td>
      <td align="center" width="20" style="background-color: #808080; border: 1px solid #000000; vertical-align: middle;"><strong>CANT. APROB</strong></td>
   </tr>
  </thead>
  <tbody>  
    {{$num=0}}
    @foreach($salidas as $sal)
    {{$num=$num+1}}
      <tr>
        <td align="center" style="border: 1px solid #000000;">{{$num}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->almacen}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->num}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->codigo}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->articulo}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->cantidad}}</td>
        <td align="center" style="border: 1px solid #000000;">{{$sal->cantapro}}</td>
      </tr>
    @endforeach 
  </tbody>
</table>
</html>
