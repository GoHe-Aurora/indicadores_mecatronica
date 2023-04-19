<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromCollection;

class TrayectoriaCExport implements /*FromCollection*/FromView
{
	protected $materia;
	protected $grupo;
	protected $alumnos;
	protected $length;

 function __construct($materia,$grupo,$alumnos,$length) {
        $this->materia = $materia;
        $this->grupo = $grupo;
        $this->alumnos = $alumnos;
        $this->length = $length;
 }
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
        $alumnos = DB::select("SELECT nombre FROM grupos WHERE idgr=1");   
        return collect($alumnos);
    }*/
    public function view(): View
    {
        return view('trayectoriac.reporte_pdf', [
            'alumnos' => $this->alumnos,
            'length' => $this->length,
            'materia' => $this->materia,
            'grupo' => $this->grupo
        ]);
    }
}
