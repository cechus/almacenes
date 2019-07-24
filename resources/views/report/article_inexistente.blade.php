@extends('layouts.print')

@section('content')

<br>
Realizando control físico de materiales en el almacén de la oficina central se encuentra que el ítem descrito en cuadro detalle es inexistente.
<br>
<br>
<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 py text-center text-xxs ">
                Nro.
            </td>
            <td class="px-15 py text-center  text-xxs">
                Nombre
            </td>

        </tr>
    </thead>
    <tbody>
        @foreach ($articles as $item)
        <tr class="text-sm">
            <td class="text-center text-xxs uppercase font-bold px-5 py-3">{{ $count++ }}</td>
            <td class="text-center text-xxs uppercase font-bold px-5 py-3">{{ $item->name??'' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>
Como constancia de lo mencionado se firma al pie del presente kardex.
<br>
<br>
<br>
<br>
<br>
<table>
    <tr>
        <td class="text-center">______________</td>
        <td class="text-center">______________</td>
    </tr>
    <tr>
        <td class="text-center text-xxs">TECNICO DE ALMACEN</td>
        <td class="text-center text-xxs"> RESPONSABLE ADMINISTRATIVO</td>
    </tr>
</table>

@endsection
