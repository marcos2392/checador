<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;

/**
 * Reportes Controller
 *
 * @property \App\Model\Table\ReportesTable $Reportes
 */
class PrincipalController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->loadModel('Empleados');
        $this->loadModel('Checadas');
        $this->loadModel('HorariosNomina');
        $this->loadModel('SucursalesEmpleados');
    }

    public function inicio() {

        $fecha=date('Y-m-d');
    	$usuario = $this->getUsuario();

        $empleados=$this->Empleados->find('sucursalesChecadas',['usuario'=>$usuario]); //debug($empleados->toArray()); die;

        $checadas=$this->Checadas->find()
        ->where(["DATE_FORMAT(fecha, '%Y-%m-%d')='". $fecha ."' and descanso<>1"])
        ->toArray();

        $this->set(compact('empleados','checadas'));
    }

    public function checar() {

        $fecha=date('Y-m-d');
        $id = $this->request->getData('empleados'); 

        $empleado=$this->getempleado();

        $checada_existente = $this->Checadas->find()
        ->where(['empleados_id' => $id,'fecha'=>$fecha])
        ->order('id desc')
        ->first();

        if(empty($checada_existente))
        {
            $this->checador($empleado,$checada_existente,$id,"entrada",false);
        }
        else
        {
            if($checada_existente->descanso==true)
            {
                $this->Flash->default("El empleado descansa este dia.");
                $this->redirect(['action' => 'inicio']);
            }
            else
            {
                if($checada_existente->salida!=null)
                {
                    $this->ChecadaMultiple($empleado,$checada_existente,$id);
                }
                else
                {
                    $this->checador($empleado,$checada_existente,$id,"salida");
                }
            }
        }
    }

    private function checador($empleado,$checada_existente,$id,$tipo){

        $fecha=date('Y-m-d');
        $dia=getdia();
        $usuario = $this->getUsuario();
        $hora = $this->gethora();
        $registrar=false;
        $descanso=false;
        $retardo=false;

        $sucursal_empleado=$this->SucursalesEmpleados->find()
        ->where(['empleado_id'=>$empleado->id])
        ->first();

        if($tipo=="entrada")
        {
            if($empleado->sucursal_id==$usuario->sucursal_id  or $sucursal_empleado['sucursal_id']=$usuario->sucursal_id ) 
            { 
                
                $tolerancia=5; 

                $segundos_hora=strtotime($this->gethorario($empleado,"entrada")->format("H:i"));
                $entrada=$this->gethorario($empleado,"entrada")->format("H:i");
                $salida=$this->gethorario($empleado,"salida")->format("H:i");

                $entrada_horario=$entrada;
                $salida_horario=$salida;

                $hrs_dia=CalcularHorasDia($salida_horario,$entrada_horario);
                
                $segundos_tolerancia=$tolerancia*60;
                $hora_tolerancia=date("H:i",$segundos_hora+$segundos_tolerancia);

                $hora1 = strtotime($hora);
                $hora_tolerancia = strtotime($hora_tolerancia);

                if($hora1 > $hora_tolerancia): $retardo=true; endif;
                $hora_ent=$hora;

                $horarios_nomina=$this->HorariosNomina->find()
                ->where(['entrada_real'=>$entrada_horario,'salida_real'=>$salida_horario])
                ->first();

                if($horarios_nomina!=null)
                {  
                    $entrada_nomina=$horarios_nomina->entrada_nomina->format("H:i");
                    $salida_nomina=$horarios_nomina->salida_nomina->format("H:i");
                }
                else
                {
                    $entrada_nomina=$entrada_horario;
                    $salida_nomina=$salida_horario;
                }

                $hrs_nomina=CalcularHorasDia($salida_nomina,$entrada_nomina);

                if($hora1>$segundos_hora)
                {   
                    $hora=explode(':',$hora);

                    $hora_retardo=CalcularHorasDia(date("H:i",$hora1),$entrada_horario);

                    if($hora_retardo>.17)
                    {
                        $entrada_nomina=date("H:i",$hora1); 
                    }
                }
                
                $checar = $this->Checadas->newEntity();
                $checar->empleados_id = $id;
                $checar->fecha = $fecha;
                $checar->entrada = $hora_ent;
                $checar->retardo = $retardo;
                $checar->dia = $dia;
                $checar->entrada_horario=$entrada_horario;
                $checar->salida_horario=$salida_horario;
                $checar->hrs_dia=$hrs_dia;
                $checar->hrs_nomina=$hrs_nomina;
                $checar->entrada_nomina=$entrada_nomina;
                $checar->salida_nomina=$salida_nomina;
                $checar->sucursal = $empleado->sucursal_id;
                $checar->sucursal_checada_id=$usuario->sucursal_id;

                $this->Checadas->save($checar);

                $this->Flash->default("Se Checo exitosamente.");
                $this->redirect(['action' => 'inicio']);
                
            }
            else
            {
                $this->Flash->default("El empleado no pertenece a esta sucursal.");
                $this->redirect(['action' => 'inicio']); 
            }
        }
        else
        { 
            $registro = $this->Checadas->get($checada_existente->id);

            $horas_trabajadas= Calcular($hora,$registro);

            $registro->salida = $hora;
            $registro->horas = $horas_trabajadas;
            $registro->hrs_finales=$registro->hrs_nomina-($registro->hrs_dia-$horas_trabajadas);

            $this->Checadas->save($registro);
            
            $this->redirect(['action' => 'inicio']);
        }
    }

    private function ChecadaMultiple($empleado,$checada_existente,$id){

        $fecha=date('Y-m-d');
        $dia=getdia();
        $usuario = $this->getUsuario();
        $hora = $this->gethora();

        $hora_ent=$hora;

        $checar = $this->Checadas->newEntity();
        $checar->empleados_id = $id;
        $checar->fecha = $fecha;
        $checar->sucursal = $usuario->sucursal_id;
        $checar->sucursal_checada_id = $usuario->sucursal_id;
        $checar->entrada = $hora_ent;
        $checar->entrada_horario = $hora_ent;
        $checar->salida_horario=$checada_existente->salida_horario->format("H:i");
        $checar->dia = $dia;

        $this->Checadas->save($checar);

        $this->Flash->default("Se Checo exitosamente.");
        $this->redirect(['action' => 'inicio']);

    }


    private function getEmpleado() {
        
        $id_empleado = $this->request->getData('empleados') ?? null;
        if ($id_empleado) {
            try {
                return $this->Empleados->get($id_empleado);
            } catch (RecordNotFoundException $exception) {
                return null;
            }
        }
    }

    private function getHora() {
        $hora = date('H:i');
        return $hora ;
    }

    private function getHorario($empleado,$tipo) { 
        
        $dia = date('l', strtotime(date('Y-m-d'))); 
        if($dia=="Monday")
        {
            $entrada=$empleado->lunes_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia=="Tuesday")
        {
            $entrada=$empleado->martes_entrada;
            $salida=$empleado->martes_salida;
        }
        if($dia=="Wednesday")
        {
            $entrada=$empleado->miercoles_entrada;
            $salida=$empleado->miercoles_salida;
        }
        if($dia=="Thursday")
        {
            $entrada=$empleado->jueves_entrada;
            $salida=$empleado->jueves_salida;
        }
        if($dia=="Friday")
        {
            $entrada=$empleado->viernes_entrada;
            $salida=$empleado->viernes_salida;
        }
        if($dia=="Saturday")
        {
            $entrada=$empleado->sabado_entrada;
            $salida=$empleado->sabado_salida;
        }
        if($dia=="Sunday")
        {
            $entrada=$empleado->domingo_entrada;
            $salida=$empleado->domingo_salida;
        }

        if($tipo=="entrada")
        {  
            return $entrada;
        }
        else
        {
           return $salida; 
        }
    }
}