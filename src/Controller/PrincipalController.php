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
        
    }

    public function inicio() {

        $fecha=date('Y-m-d');
    	$usuario = $this->getUsuario();

    	$empleados=$this->Empleados->find()
    	->contain(['sucursales'])
    	->where(['sucursal_id'=>$usuario->sucursal_id])
        ->order(['empleados.nombre']);

        $checadas=$this->Checadas->find()
        ->where(["DATE_FORMAT(fecha, '%Y-%m-%d')='". $fecha ."' and descanso<>1"]);

        $this->set(compact('empleados','checadas'));
    }

    public function checar() {

        $usuario = $this->getUsuario();

        $registrar=false;
        $descanso=false;
        $retardo=false;

        $fecha=date('Y-m-d');
        $hora = $this->gethora();
        $id = $this->request->getData('id');
        $dia=getdia();

        $empleado=$this->getempleado();

        if(!$empleado)
        {
            $this->Flash->default("No existe el empleado con el ID: ".$id."");
            return $this->redirect(['action' => 'inicio']);
        }

        
        $checada_existente = $this->Checadas->find()
        ->where(['empleados_id' => $id,'fecha'=>$fecha])
        ->order('id desc')
        ->first();

        if(empty($checada_existente))
        { 
            if($empleado->sucursal_id==$usuario->sucursal_id) 
            { 
                if($empleado->descanso==$dia)
                {
                    if($empleado->dia_extra==$dia and $empleado->tipo_extra==3):$registrar=true; else: $this->Flash->success("Este empleado descansa.Cualquier cosa comuniquese con algun administrador.");
                    $this->redirect(['action' => 'inicio']); endif;
                }
                else
                {
                    if($empleado->descanso!=$dia ):$registrar=true; endif;
                }

                if($registrar==true)
                {
                    if($empleado->tipo_extra==1 and $empleado->dia_extra==$dia)
                    {
                        $hora_ent=$hora;
                    }
                    else
                    {
                        $tolerancia=5; 

                        $segundos_hora=strtotime($this->gethorario($empleado,"entrada")->format("H:i"));
                        $entrada=$this->gethorario($empleado,"entrada")->format("H:i");
                        $salida=$this->gethorario($empleado,"salida")->format("H:i");
                        
                        $entrada_horario=$entrada;
                        $salida_horario=$salida; 

                        $hrs_dia=getcalcular($salida_horario,$entrada_horario,true);
                        
                        $segundos_tolerancia=$tolerancia*60;
                        $hora_tolerancia=date("H:i",$segundos_hora+$segundos_tolerancia); 

                        $hora1 = strtotime($hora);
                        $hora_tolerancia = strtotime($hora_tolerancia);

                        if($hora1 > $hora_tolerancia): $retardo=true; endif;
                        $hora_ent=($retardo==false)? $entrada : $hora;
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
                    $checar->sucursal = $empleado->sucursal_id;

                    $this->Checadas->save($checar);

                    $this->Flash->default("Se Checo exitosamente.");
                    $this->redirect(['action' => 'inicio']);
                }
            }
            else
            {
                $this->Flash->default("El empleado no pertenece a esta sucursal.");
                $this->redirect(['action' => 'inicio']); 
            }
        }
        else
        {
            if ($checada_existente->entrada!=null and $checada_existente->salida==null) 
            {
                $registro = $this->Checadas->get($checada_existente->id);

                if($empleado->tipo_extra!=2)
                {
                    
                    $salida=$this->gethorario($empleado,"salida")->format("H:i");
                    
                    $salida_empleado=strtotime($salida);
                    $hora1 = strtotime($hora);

                    $hora=($hora1 > $salida_empleado)? $salida : $hora;
                }

                $horas_trabajadas= getcalcular($hora,$checada_existente->entrada->format("H:i"),false); 

                $registro->salida = $hora;
                $registro->horas = $horas_trabajadas;

                $this->Checadas->save($registro);

                $this->redirect(['action' => 'inicio']);
            }
            else
            {
                if($checada_existente->descanso==true)
                {
                    $this->Flash->success("Este empleado descansa.Cualquier cosa comuniquese con algun administrador.");
                    $this->redirect(['action' => 'inicio']);
                }
                else
                {
                    $checar = $this->Checadas->newEntity();
                    $checar->empleados_id = $id;
                    $checar->entrada = $hora;
                    $checar->fecha=$fecha;
                    $checar->dia = $dia;
                    $checar->sucursal = $empleado->sucursal_id;
                    $this->Checadas->save($checar);

                    $this->Flash->default("Se Checo exitosamente.");
                    $this->redirect(['action' => 'inicio']);
                }
            }
        }
    }

    private function getEmpleado() {
        
        $id_empleado = $this->request->getData('id') ?? null;
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