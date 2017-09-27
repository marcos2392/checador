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
class HorariosController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->loadModel('Checadas');
        $this->loadModel('Empleados');
        $this->loadModel('Sucursales');
        $this->loadModel('HorariosEmpleadas');
    }

    public function semanal() {

        $usuario = $this->getUsuario();
        $sucursal=$usuario->sucursal_id;

        $empleados=$this->Empleados->find()
        ->where(['sucursal_id'=>$sucursal,'status'=>true])
        ->toArray();

        $horarios_semanal=$this->RegistroHorarios($sucursal);

        if($horarios_semanal==[])
        {
            foreach($empleados as $emp)
            {
                $this->CrearRegistro($sucursal,$emp->id);
            }
            
        }
        else
        {
            foreach($empleados as $emp)
            {
                $existe=false;

                foreach($horarios_semanal as $hs)
                { 
                    if($emp->id== $hs->empleado_id)
                    {
                        $existe=true;
                    } 
                }

                if($existe==false)
                {
                    $this->CrearRegistro($sucursal,$emp->id);
                }
            }
        }

        $horarios_semanal=$this->RegistroHorarios($sucursal); 

         $this->set(compact('empleados','sucursal','horarios_semanal'));
    }

    private function CrearRegistro($sucursal,$empleado){

        $registro = $this->HorariosEmpleadas->newEntity();
        $registro->empleado_id=$empleado;
        $registro->sucursal_id=$sucursal;
        $this->HorariosEmpleadas->save($registro);

    }

    public function actualizar() {

        $usuario = $this->getUsuario();
        $sucursal=$usuario->sucursal_id;

        $registros=$this->request->getData('empleados');

        foreach($registros as $id=>$reg)
        { 
            foreach($reg as $id_emp=>$dias)
            {
                foreach($dias as $dia=>$hora)
                {
                    if($hora!='')
                    {
                        $horarios_semanal=$this->HorariosEmpleadas->get($id);

                        if($dia=='descanso')
                        {
                            $horarios_semanal->descanso=$hora;
                        }
                        else
                        {
                            $horarios_semanal->$dia=FormatoHora($hora);
                        }

                        $this->HorariosEmpleadas->save($horarios_semanal);
                    }
                }
            }
        }

        $this->Flash->default("Se Actualizo Correctamente.");
        $this->redirect(['action' => 'semanal']);
        
    }

    public function RegistroHorarios($sucursal){

        $horarios_semanal=$this->HorariosEmpleadas->find() 
        ->where(['sucursal_id'=>$sucursal])
        ->toArray();

        return $horarios_semanal;
    }
}

