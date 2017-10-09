<?php

namespace proyectoSFA\Http\Controllers;

use Illuminate\Http\Request;

use proyectoSFA\Http\Requests;

use proyectoSFA\Marca;
use Illuminate\Support\Facades\Redirect;
use proyectoSFA\Http\Requests\MarcaFormRequest;
use DB;

class MarcaController extends Controller
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
            $marcas = DB::table('marca')
            ->where('nombreMarca','LIKE','%'.$query.'%')
            ->where('status','=','1')
            ->orderBy('idmarca','asc')
            ->paginate(7);
            return view('inventario.marca.index',["marcas"=>$marcas,"searchText"=>$query]);
        }

    }
    public function create()
    {
        return view("inventario.marca.create");
    }
    public function store(MarcaFormRequest $request)
    {
         $marca = new Marca;
         $marca->nombreMarca = $request->get('nombre');
         $marca->descripcion = $request->get('descripcion');
         $marca->status = 1;
         $marca->save();

         return Redirect::to('inventario/marca');

    }
    public function show($id)
    {
       
       return view("inventario.marca.show",["marca"=>Marca::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("inventario.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function update(CategoriaFormRequest $request, $id)
    {
         $categoria = Categoria::findOrFail($id);
         $categoria->nombre = $request->get('nombre');
         $categoria->descripcion = $request->get('descripcion');
         $categoria->update();
         return Redirect::to('inventario/categoria');
    }
    public function destroy($id)
    {
           $categoria = Categoria::findOrFail($id);
           $categoria->condicion = 0;
           $categoria->update();
           return Redirect::to('inventario/categoria');
    }
}