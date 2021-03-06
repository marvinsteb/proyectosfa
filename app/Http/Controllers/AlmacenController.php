<?php

namespace proyectoSeminario\Http\Controllers;

use Illuminate\Http\Request;
use proyectoSeminario\Http\Requests;

use proyectoSeminario\Almacen;
use Illuminate\Support\Facades\Redirect;
use proyectoSeminario\Http\Requests\AlmacenFormRequest;
use DB;

class AlmacenController extends Controller
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
    	$almacenes = DB::table('almacen')
    	->where('nombre','LIKE','%'.$query.'%')
    	->orderBy('idalmacen','asc')
    	->paginate(7);
    	return view('inventario.almacen.index',["almacenes"=>$almacenes,"searchText"=>$query]);
        }    
    }
    public function create()
    {
        return view('inventario.almacen.create');
    }
    public function store(AlmacenFormRequest $request)
    {
         $almacen = new Almacen;
         $almacen->nombre = $request->get('nombre');
         $almacen->ubicacion = $request->get('ubicacion');
         $almacen->save();

         return Redirect::to('inventario/almacen');
    }

    public function show($id)
    {
        return view("inventario.almacen.show",["almacen"=>Almacen::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view("inventario.almacen.edit",["almacen"=>Almacen::findOrFail($id)]);
    }
    public function update(AlmacenFormRequest $request, $id)
    {
         $almacen = almacen::findOrFail($id);
         $almacen->nombre = $request->get('nombre');
         $almacen->ubicacion = $request->get('ubicacion');
         $almacen->update();
         return Redirect::to('inventario/almacen');
    public function destroy($id)
    {
        /*
        implementar en la base de datos una columna para el estatus 
        verificar si no existe existencias antes de cambiar el estatus del almacen
           $almacen = almacen::findOrFail($id);
           $almacen->condicion = 0;
           $almacen->update();
           return Redirect::to('inventario/almacen');
           */
    return Redirect::to('inventario/almacen');
    }

}
