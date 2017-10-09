@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Marcas <a href="marca/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('inventario.marca.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Marca</th>
					<th>Descripción</th>
					<th>Opciones</th>
				</thead>
               @foreach ($marcas as $marca)
				<tr>
					<td>{{ $marca->idmarca}}</td>
					<td>{{ $marca->nombreMarca}}</td>
					<td>{{ $marca->descripcion}}</td>
					<td>
						<a href="{{URL::action('MarcaController@edit',$marca->idmarca)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$marca->idmarca}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('inventario.marca.modal')
				@endforeach
			</table>
		</div>
		{{$marcas->render()}}
	</div>
</div>

@endsection