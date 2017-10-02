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

         $this->set(compact('empleados','sucursal'));
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
                        $horarios_empleada=$this->Empleados->get($id);

                        if($dia=='descanso')
                        {
                            $horarios_empleada->descanso=$hora;
                        }
                        else
                        {
                            $horarios_empleada->$dia=FormatoHora($hora);
                        }

                        $this->Empleados->save($horarios_empleada);
                    }
                }
            }
        }

        $this->Flash->default("Se Actualizo Correctamente.");
        $this->redirect(['action' => 'semanal']);
    }
}

