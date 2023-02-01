<?php

namespace App\Http\Controllers\Sistema\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{

    public function panel()
    {
        if(Auth()->user()->activo == 0){
            return redirect()->route('inactivo')->with('messageDelete', 'su usuario ha sido bloqueado temporalmente. Por favor, contacte al administrador del sistema');
        }else{
        //$user = auth()->user()->idu;
        //$area = Auth()->user()->idar_areas;
        //return Auth()->user();

            //$user = DB::SELECT("SELECT idu, CONCAT(titulo, ' ',nombre, ' ', app, ' ', apm) AS nombre FROM users;");
            $nombre = Auth()->user()->titulo.Auth()->user()->nombre.Auth()->user()->app.Auth()->user()->apm;

            return view('home',[
                'user' => Auth()->user()->idu,
                'nombre' => $nombre
            ]);
        
        }
    }

}
