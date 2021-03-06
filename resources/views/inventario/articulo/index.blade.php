@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Vehiculos  <a href="articulo/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('inventario.articulo.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Descripción</th>
					<th>Unidad</th>
					<th>Marca</th>
					<th>Opciones</th>
				</thead>
               @foreach ($articulos as $articulo)
				<tr>
					<td>{{ $articulo->id_inventario}}</td>
					<td>{{ $articulo->descripcion}}</td>
					<td>{{ $articulo->unidad}}</td>
				    <td>{{ $articulo->categoria}}</td>
					<td>
						<a href="{{URL::action('ArticuloController@edit',$articulo->id_inventario)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$articulo->id_inventario}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('inventario.articulo.modal')
				@endforeach
			</table>
		</div>
		{{$articulos->render()}}
	</div>
</div>

@endsection