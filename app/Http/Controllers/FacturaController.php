<?php

namespace proyectoSeminario\Http\Controllers;

use Illuminate\Http\Request;

use proyectoSeminario\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

use proyectoSeminario\Http\Requests\FacturaFormRequest;
use proyectoSeminario\Factura;
use proyectoSeminario\FacturaDetalle;

use DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection; 

class FacturaController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if($request)
        {
            $query = trim($request->get('searchText'));
            $facturas = DB::table('factura as fac')
            ->join('serie as ser','fac.codigo_serie','=','ser.idserie')
            ->join('cliente as clie','fac.cliente_id','=','clie.idcliente')
            ->join('fac_detalle as dt','fac.idfactura','=','dt.idfactura')
            ->select(
                'fac.idfactura'
                ,'fac.numero_fac'
                ,'ser.serie'
                ,'fac.fecha_documento'
                ,'fac.fecha_creacion'
                ,'clie.nombre'
                ,DB::raw('sum((dt.cantidad*dt.costo)) as costo')
                )          
            ->groupBy('fac.idfactura','fac.numero_fac','ser.serie','fac.fecha_documento','fac.fecha_creacion','clie.nombre')
            ->where('fac.numero_fac','LIKE','%'.$query.'%')
            ->orderBy('fac.numero_fac','desc')
            ->paginate(7);
            return view('ventas/factura.index',["facturas"=>$facturas,"searchText"=>$query]);
        }

    }
    public function create()
    {
        $series = DB::table('serie')->where('tipo_documento','=','Fac')->get();
        $clientes = DB::table('cliente')->where('cliente.estado','=','1')->get();
        $vendedores = DB::table('vendedor')->where('vendedor.estado','=','1')->get();
        $vehiculos = DB::table('vehiculo')
        ->select(
            'vehiculo.idvehiculo',
            DB::raw('CONCAT(marca.nombreMarca,"-",mod.modelo, "-", vehiculo.lote ) as vehiculo')
            )
        ->Where('estado','=','6')
        ->join('modelo as mod','mod.idmodelo','=','vehiculo.idmodelo' )
        ->join('marca','vehiculo.idmarca','=','marca.idmarca')
        ->get();
        return view("ventas/factura.create",["series" => $series,"clientes" => $clientes, "vendedores" => $vendedores,"vehiculos"=>$vehiculos ]);
    }
    
    public function store(FacturaFormRequest $request)
    {
       /*try
        {
            DB::beginTransaction();
       */
                  $date = Carbon::now();
                  $date = $date->toDateString();  
                  $factura = new Factura;
                  $factura->codigo_serie = $request->get('codigoserie');
                  $factura->numero_fac = 1;
                  $factura->estado = 1;
                  $factura->fecha_documento = $date;
                  $factura->fecha_creacion = $date; 
                  $factura->cliente_id = $request->get('clienteid');
                  $factura->vendedor_id= $request->get('vendedorid');
                  $factura->total =  0;
                  $factura->save();
                  
                  $idarticulo = $request->get('idinv');
                  $cantidad = $request->get('cantidad');
                  $precio = $request->get('precio');
                  $impuesto = $request->get('impuesto');
         
                  $contador = 0;
                  while($contador < count($idarticulo))
                  {
                    $detalle = new FacturaDetalle;
                    $detalle->idfactura = $factura->idfactura;
                    $detalle->id_inv =  $idarticulo[$contador];
                    $detalle->id_almacen = $idalmacen[$contador];
                    $detalle->cantidad = 1;
                    $detalle->precio = $precio[$contador];
                    $detalle->impuesto = $impuesto[$contador];
                    $detalle->save();

                    $contador = $contador + 1;
                  }               
    
    /*        DB::commit();
   
        }
        catch (\Exception $e) 
        {
            DB::rollback();      
        }  
¨*/
         return Redirect::to('ventas/factura');

    }
    public function show($id)
    {
        $factura = DB::table('factura as fac')
            ->join('serie as ser','fac.codigo_serie','=','ser.idserie')
            ->join('cliente as clie','fac.cliente_id','=','clie.idcliente')
            ->join('fac_detalle as dt','fac.idfactura','=','dt.idfactura')
            ->select('fac.idfactura','fac.numero_fac','ser.serie','fac.fecha_documento','fac.fecha_creacion','clie.nombre',DB::raw('sum(dt.cantidad*dt.precio) as total'))  
            ->where('fac.idfactura','=', $id)
            ->first();
       
       $detalle =DB::table('fac_detalle as dt')
                ->join('inventario as articulos','dt.id_inv'.'=','articulos.id_inventario')
                ->join('categoria','categoria.idcategoria','=','articulos.idcategoria')
                ->select('articulos.descripcion','articulos.unidad','categoria.nombre','dt.cantidad','dt.precio')
                ->where('dt.idfactura','=',$id)
                ->get();
       return view("ventas/factura.show",["cliente"=>$factura,"detalles"=>$detalle]);
    }

    public function destroy($id)
    {
           $factura = Factura::findOrFail($id);
           $factura->estado = 0;
           $factura->update();
           return Redirect::to('ventas/factura');
    }
}
