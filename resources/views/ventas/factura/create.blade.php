@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nueva Factura</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif 
        </div>
    </div>


			{!!Form::open(array('url'=>'ventas/factura','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
            {{Form::token()}}
<div class = "row">
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
			<div class="form-group">
				<label>Serie</label>
				<select name="codigoserie" class="form-control">
					@foreach($series as $serie)
						<option value="{{$serie->idserie}}">{{$serie->serie}}</option>
					@endforeach            
				</select>
			</div>        
    	</div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	        <div class="form-group">
            	<label for="fecha_documento">Fecha Documento</label>
            	<input type="text" name="fecha_documento" required value="{{old('fecha_documento')}}"  class="form-control" placeholder="Fecha...">
            </div>        
    </div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            	<label for="unidad">Cliente</label>
            	<select name="clienteid" id="clienteid"  class="form-control selectpicker" data-live-search = "true">
					@foreach($clientes as $cliente)
						<option value="{{$cliente->idcliente}}">{{$cliente->nombre}}</option>
					@endforeach            
            	</select>
            </div>  
    </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="form-group">
					<label for="vendedorid">Vendedor</label>
					<select name="vendedorid" id="vendedorid"  class="form-control selectpicker"  data-live-search = "true">
					@foreach($vendedores as $vendedor)
						<option value="{{$vendedor->idvendedor}}">{{$vendedor->nombre}}</option>
					@endforeach            
				    </select>
			</div>  
	</div>
</div>



<div class = "row">
	<div class = "panel panel-primary">
		<div class = "panel-body">


			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="form-group">
					<label for="articulo">Vehiculo</label>
					<select name="pidinv"   class="form-control selectpicker" id="pidinv" data-live-search = "true">
						<option value="art" selected="selected"></option>
						@foreach($vehiculos as $vehiculo)
							<option value="{{$vehiculo->idvehiculo}}">{{$vehiculo->vehiculo}}</option>
						@endforeach            
					</select>
			    </div>
			</div>


			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="form-group">
            	<label for="precio">Precio</label>
            	<input type="number" step="any" name="pprecio" id="pprecio"   class="form-control" placeholder="Precio...">
            	</div>        
			</div>

			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="form-group">
					<label>Impuesto</label>
					<select name="pimpuesto"   class="form-control " id="pimpuesto" >
						    <option value="impu" selected="selected"></option>
							<option value="1.12">12 %</option>
							<option value="1.00">Excento</option>             
					</select>
			    </div>
			</div>

			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="form-group">
				<br>
				<button type="button" id="btn_add" class="btn btn-primary" >Agregar</button>
				</div>
			</div>


		</div>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<table id="tb_detalles" class= "table table-striped table-bordered table-condensed tale-hover ">
			<thead style = "background-color:#A9D0F5">
				<th>Opciones</th>
				<th>Vehiculo</th>
			    <th>Precio</th>
				<th>Impuesto</th>
				<th>Sub. Total</th>
			</thead>
				<th>TOTAL</th>
				<th></th>
			    <th></th>
				<th></th>
				<th><h4 id="total">Q 0.00</h4></th>
			<tfoot>
			</tfoot>
			<body>

			</body>
		<table>
	</div>
	<div id= "gtotal"></div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="guardar">
		<div class="form-group">
			<input name"_token" value="{{csrf_token()}}" type="hidden"></input>
			<button class="btn btn-primary" type="submit">Guardar</button>
			<button class="btn btn-danger" type="reset">Cancelar</button>
		</div>
	</div>
</div>
			{!!Form::close()!!}		
@push ('scripts')
<script>

$(document).ready(function(){
    $('#btn_add').click(function(){
		agregar();
	});
});


var cont = 0;
total = 0;
subtotal=[];

$("#guardar").hide();

function agregar(){


idarticulo = $('#pidinv').val();
articulo = $('#pidinv option:selected').text();
impuesto = $('#pimpuesto').val();
precio = $("#pprecio").val();


if(idarticulo != "" && articulo != "" && impuesto !=""  && precio !="" )
{


subtotal[cont] = ( precio * impuesto);
total = total + subtotal[cont];


var fila = '<tr class="selected" id = "fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont
+')">X</button></td><td><input type="hidden" name="idinv[]" value="'+idarticulo+'">'+articulo
+'</input></td><td><input type="hidden" name="precio[]" value="'+precio+'" >'+precio
+'</input></td><td><input type="hidden" name="impuesto[]" value="'+impuesto+'" >'+impuesto
+'</input></td><td>'+subtotal[cont].toFixed(2)+'</td></tr>'; 

cont++;
limpiar();
$("#total").html("Q" + total.toFixed(2));
evaluar();
$("#tb_detalles").append(fila);	
}
else
{
	alert("error al ingrear detalle de articulos.");
}

}

function limpiar()
{
	$('#pidinv > option[value="art"]').attr('selected', 'selected');
	$('#pimpuesto > option[value="impu"]').attr('selected', 'selected');
	$("#pprecio").val("");
	
}
 function evaluar()
 {
	 if(total > 0)
	 {
		 $("#guardar").show();
	 }
	 else 
	 {
		 $("#guardar").hide();
	 }
 }
 function eliminar(index)
 {
   total = total -subtotal[index];
   $("#total").html("Q" + total.toFixed(2)); 
   $("#fila"+index).remove();
   evaluar();
 }
</script>
@endpush
@endsection
