<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

/**
 * Reportes Controller
 *
 * @property \App\Model\Table\ReportesTable $Reportes
 */
class EmpleadosController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->loadModel('Empleados');
        $this->loadModel('Sucursales');
        
    }

    public function empleados() {

    	$usuario = $this->getUsuario();

        $condicion = ["empleados.status=true"];

        if(!$usuario->admin)
        {
            $condicion[] = ["sucursal_id= '" .$usuario->suc. "'"];
        }

    	$empleados=$this->Empleados->find()
    	->contain(['sucursales'])
    	->where($condicion)
        ->order('sucursales.nombre,empleados.nombre');

        $this->set(compact('empleados'));
    }

    public function nuevo() {
        $usuario = $this->getUsuario();

        $sucursal='';
        $empleado = $this->Empleados->newEntity();
        $sucursales =$this->Sucursales->find()
        ->order(['nombre']);

        $this->set(compact('empleado','sucursales','sucursal'));
    }

    public function crear() {

    	$usuario = $this->getUsuario();

        if($usuario->admin)
        {
            $sucursal = $this->request->getData('sucursal');
        }
        else
        {
            $sucursal = $usuario->sucursal_id;
        }

		$nombre = $this->request->getData('nombre');
		$apellidos = $this->request->getData('apellidos');
        $entrada = $this->request->getData('entrada') ?? 0;
        $salida = $this->request->getData('salida') ?? 0;
        $descanso = $this->request->getData('descanso')?? 0;
        $diaextra = $this->request->getData('diaextra')?? 0;
        $tipoextra = $this->request->getData('tipoextra')?? 0;

		//$entrada = $this->request->getData('entrada');
		//$salida = $this->request->getData('salida');

        $nombre=htmlentities($nombre, ENT_QUOTES,'UTF-8');
        $nombre = ucwords(strtolower($nombre));

        $apellidos=htmlentities($apellidos, ENT_QUOTES,'UTF-8');
        $apellidos = ucwords(strtolower($apellidos));

        $empleado_existente = $this->Empleados->find()
        ->where(['nombre' => $nombre,'apellidos'=>$apellidos])
        ->first();
        if ($empleado_existente) 
        {
            $this->Flash->error('Ya existe un Empleado con ese nombre: ' . $nombre.' '. $apellidos);
            $this->redirect(['action' => 'nuevo']);
            return;
        }

        $empleado = $this->Empleados->newEntity();
        $empleado->nombre = $nombre;
        $empleado->apellidos=$apellidos;
        $empleado->status=true;
        $empleado->sucursal_id=$sucursal;
        $empleado->entrada=$entrada;
        $empleado->salida=$salida;
        $empleado->descanso=$descanso;
        $empleado->dia_extra=$diaextra;
        $empleado->tipo_extra=$tipoextra;

        if($nombre=="" || $apellidos=="" || $descanso=="" || $sucursal=="")
        {
            $this->Flash->default("Necesita llenar todos los campos");
            $this->redirect(['action' => 'nuevo']); 
        }
        else
        {
            $this->Empleados->save($empleado);

            $this->Flash->default("Se creó el Empleado: ".$nombre.' '.$apellidos." , Exitosamente.");
            $this->redirect(['action' => 'empleados']); 
        }
    }

    public function editar() {

        $sucursales =$this->Sucursales->find()
        ->order(['nombre']);
        $empleado = $this->Empleados->get($this->request->getQuery('id'));
        $sucursal=$empleado->sucursal_id;
        
        $this->set(compact('empleado','sucursales','sucursal'));
    }

    public function actualizar() {

        $empleado = $this->getEmpleado();

        $nombres = $this->request->getData('nombre') ?? '';
        $apellidos = $this->request->getData('apellidos') ?? '';
        $entrada = $this->request->getData('entrada');
        $salida = $this->request->getData('salida');
        $sucursal = $this->request->getData('sucursal') ?? '';
        $descanso = $this->request->getData('descanso')?? 0;
        $diaextra = $this->request->getData('diaextra')?? 0;
        $tipoextra = $this->request->getData('tipoextra')?? 0;

        $nombres = ucwords(strtolower($nombres));
        $apellidos=htmlentities($apellidos, ENT_QUOTES,'UTF-8');
        $apellidos = ucwords(strtolower($apellidos));

        $separar[1]=explode(':',$entrada);
        $separar[2]=explode(':',$salida);

        $entrada=$this->gethora($separar[1][0],$separar[1][1]);
        $salida=$this->gethora($separar[2][0],$separar[2][1]);

        $empleado->nombre=$nombres;
        $empleado->apellidos=$apellidos;
        $empleado->entrada=$entrada;
        $empleado->salida=$salida;
        $empleado->descanso=$descanso;
        $empleado->dia_extra=$diaextra;
        $empleado->tipo_extra=$tipoextra;
        $empleado->sucursal_id=$sucursal;

        if ($this->request->is('post'))
        {
            if($this->Empleados->save($empleado))
             {
                $this->Flash->default("Se actualizo al empleado: ".$nombres.' '.$apellidos." ,exitosamente.");
                $this->redirect(['action' => 'empleados']);
             }
             else
             {
               $this->Flash->error("Hubo un Error al Actualizar el Empleado.");
               $this->redirect(['action' => 'editar']);
             }
        }
    }

    public function eliminar() {

        if ($this->Empleados->get($this->request->getQuery('id'))) 
        {
            $empleado = $this->Empleados->get($this->request->getQuery('id'));
            $empleado->status=0;

            $this->Empleados->save($empleado);

            $this->Flash->default("Se elimino el Empleado exitosamente.");
            $this->redirect(['action' => 'empleados']);
        }
        else
        {
        	$this->Flash->default("no");
            $this->redirect(['action' => 'empleados']);
        }
    }

    private function getEmpleado() {
        $id_empleado = $this->request->getQuery('id');
        return $this->Empleados->get($id_empleado);
    }

    private function gethora($hora,$h2) {
        
        if ($hora == 1) {
            $hora=13;
        } elseif ($hora == 2) {
            $hora=14;
        } elseif ($hora == 3) {
            $hora=15;
        } elseif ($hora == 4) {
            $hora=16;
        }elseif ($hora == 5) {
            $hora=17;
        }elseif ($hora == 6) {
            $hora=18;
        }elseif ($hora == 7) {
            $hora=19;
        }elseif ($hora == 8) {
            $hora=20;
        }

        return $hora.':'.$h2;
    }
}