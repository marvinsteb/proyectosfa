@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de repuestos  <a href="articulo/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('inventario.repuesto.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Descripción</th>
					<th>Estado</th>
					<th>Esistencias</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Tipo</th>
					<th>Opciones</th>
				</thead>
               @foreach ($repuestos as $repuesto)
				<tr>
					<td>{{ $repuesto->idrepuesto}}</td>
					<td>{{ $repuesto->descripcion}}</td>
					<td>{{ $repuesto->estado}}</td>
				    <td>{{ $repuesto->existencias}}</td>
					<td>{{ $repuesto->nombreMarca}}</th>
					<td>{{ $repuesto->modelo}}</th>
					<td>{{ $repuesto->tipo}}</th>
					<td>
						<a href="{{URL::action('RepuestoController@edit',$repuesto->idrepuesto)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$repuesto->idrepuesto}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('inventario.repuesto.modal')
				@endforeach
			</table>
		</div>
		{{$repuestos->render()}}
	</div>
</div>

@endsection