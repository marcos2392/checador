<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
 * Reportes Controller
 *
 * @property \App\Model\Table\ReportesTable $Reportes
 */
class UsuariosController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->Auth->allow('login');
        $this->loadModel('Empleados');
        $this->loadModel('Checadas');
        $this->loadModel('Sucursales');
        $this->loadModel('Usuarios');
    }

    public function login() {

        if ($this->request->is('post')) {
            $usuario = $this->Auth->identify();
            if ($usuario) {
                $this->Auth->setUser($usuario);
                $this->descansos();
                $this->faltas();
                $this->extras();
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Usuario o contraseña incorrectos.');
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function usuarios() {
        
        //$usuarios=$this->Usuarios->find()
        //->contain(['Sucursales']);
        $user=[];

        $usuarios=$this->Usuarios->find();
        foreach($usuarios as $s)
        {
            $sucursales=$this->Sucursales->find()
            ->where(['id'=>$s->sucursal_id]);

            foreach($sucursales as $sucursal)
            {
                $user[$s->id]=[];
                $user[$s->id]["usuario"]=$s;
                $user[$s->id]["sucursal"]=$sucursal->nombre;
            }
        }
        

        $this->set(compact('usuarios','user'));
    }

    public function nuevo() {

        $sucursal='';
        $user = $this->Usuarios->newEntity();
        $sucursales =$this->Sucursales->find()
        ->order(['nombre']);

        $this->set(compact('user','sucursales','sucursal'));
    }

    public function crear() {

        $usuario=$this->getusuario();

        if($usuario->admin)
        {
            $sucursal = $this->request->getData('sucursal');
        }
        else
        {
            $sucursal = $usuario->sucursal_id;
        }

        $nombre = $this->request->getData('nombre');
        $us = $this->request->getData('usuario');
        $password = $this->request->getData('password');
        $tipo = $this->request->getData('tipo') ?? 0;

        $usuario_existente = $this->Usuarios->find()
        ->where(['usuario' => $us])
        ->first();
        if ($usuario_existente) 
        {
            $this->Flash->error('Ya existe un Usuario con ese nombre: ' . $nombre);
            $this->redirect(['action' => 'nuevo']);
            return;
        }

        $user = $this->Usuarios->newEntity();
        $user->nombre=$nombre;
        $user->usuario=$us;
        $user->password=$password;
        $user->sucursal_id=$sucursal;
        $user->tipo_usuario=$tipo;
        $user->status=true;

        if($nombre=="" || $usuario=="" || $password=="" || $sucursal=="")
        {
            $this->Flash->default("Necesita llenar todos los campos");
            $this->redirect(['action' => 'nuevo']); 
        }
        else
        {
            $this->Usuarios->save($user);

            $this->Flash->default("Se creó el Usuario: ".$us." , Exitosamente.");
            $this->redirect(['action' => 'usuarios']); 
        }
    }

    public function editar() {

        $sucursales =$this->Sucursales->find()
        ->order(['nombre']);
        $user = $this->Usuarios->get($this->request->getQuery('id'));
        $sucursal=$user->sucursal_id;
        
        $this->set(compact('user','sucursales','sucursal'));
    }

    public function actualizar() {

        $usuario = $this->Usuarios->get($this->request->getQuery('id'));

        $nombres = $this->request->getData('nombre') ?? '';
        $user = $this->request->getData('usuario') ?? '';
        $password = $this->request->getData('password');
        $tipo = $this->request->getData('tipo');
        $sucursal = $this->request->getData('sucursal') ?? '';
        
        $nombres = ucwords(strtolower($nombres));
        
        $usuario->nombre=$nombres;
        $usuario->usuario=$user;
        $usuario->password=$password;
        $usuario->sucursal_id=$sucursal;
        $usuario->tipo_usuario=$tipo;

        if ($this->request->is('post'))
        {
            if($this->Usuarios->save($usuario))
             {
                $this->Flash->default("Se actualizo al usuario: ".$nombres." ,exitosamente.");
                $this->redirect(['action' => 'usuarios']);
             }
             else
             {
               $this->Flash->error("Hubo un Error al Actualizar el Empleado.");
               $this->redirect(['action' => 'editar']);
             }
        }
    }

    public function eliminar() {

        if ($this->Usuarios->get($this->request->getQuery('id'))) 
        {
            $usuario = $this->Usuarios->get($this->request->getQuery('id'));
            $usuario->status=0;

            $this->Usuarios->delete($usuario);

            $this->Flash->default("Se elimino el Usuario exitosamente.");
            $this->redirect(['action' => 'usuarios']);
        }
        else
        {
            $this->Flash->default("no");
            $this->redirect(['action' => 'usuarios']);
        }
    }

    private function descansos(){

        $fecha=date('Y-m-d');
        $dia=getdia();

        $registrar=false;

        $empleados=$this->Empleados->find()
        ->where(['status'=>true]);
        
        foreach($empleados as $empleado)
        { 
            $checadas=$this->Checadas->find()
            ->where(['empleados_id'=>$empleado->id,'fecha'=>$fecha]);

            if($checadas->isEmpty())
            {
                if($empleado->descanso==$dia)
                {
                    if($empleado->dia_extra!=$dia)
                    { 
                        $registrar=true;
                    }
                }
                else
                {
                    if($dia==6 || $dia==7)
                    {
                        if($empleado->descanso==8)
                        {
                            $registrar=true;
                        }
                    }
                }

                if($registrar==true)
                {
                    $checar = $this->Checadas->newEntity();
                    $checar->empleados_id = $empleado->id;
                    $checar->fecha = $fecha;
                    $checar->dia = $dia;
                    $checar->descanso=true;
                    $checar->sucursal=$empleado->sucursal_id;

                    $this->Checadas->save($checar);
                }
            }
            $registrar=false;
        }
    }

    private function faltas(){

        $fecha=date('Y-m-d', strtotime('-1 day')); 
        $dia=getdia();
        $dia=($dia==1)? 7 : $dia-1;

        $empleados=$this->Empleados->find()
        ->where(['status'=>true]);
        
        foreach($empleados as $empleado) 
        {
            $entrada=$this->gethorario($dia,$empleado,"entrada");
            
            if($entrada!=null)
            {
                $checadas=$this->Checadas->find()
                ->where(['empleados_id'=>$empleado->id,'fecha'=>$fecha]);

                if($checadas->isEmpty())
                {
                    $salida=$this->gethorario($dia,$empleado,"salida");
                    $hrs_dia=getcalcular($salida,$entrada,true);

                    $checar = $this->Checadas->newEntity();
                    $checar->empleados_id = $empleado->id;
                    $checar->fecha = $fecha;
                    $checar->dia = $dia;
                    $checar->falta=true;
                    $checar->sucursal=$empleado->sucursal_id;
                    $checar->entrada_horario=$entrada;
                    $checar->salida_horario=$salida;
                    $checar->hrs_dia=$hrs_dia;

                    $this->Checadas->save($checar);
                }
            }
        }
    }

    private function extras(){

        $dia=getdia();

        $empleados=$this->Empleados->find()
        ->where(['dia_extra'=>$dia-1]);
        
        foreach($empleados as $empleado)
        {
            $borrar_extra = $this->Empleados->get($empleado->id);
            $borrar_extra->dia_extra=0;
            $borrar_extra->tipo_extra=0;

            $this->Empleados->save($borrar_extra);
        }
    }

    private function getHorario($dia,$empleado,$tipo) { 
        
        if($dia==1)
        {
            $entrada=$empleado->lunes_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==2)
        {
            $entrada=$empleado->martes_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==3)
        {
            $entrada=$empleado->miercoles_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==4)
        {
            $entrada=$empleado->jueves_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==5)
        {
            $entrada=$empleado->viernes_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==6)
        {
            $entrada=$empleado->sabado_entrada;
            $salida=$empleado->lunes_salida;
        }
        if($dia==7)
        {
            $entrada=$empleado->domingo_entrada;
            $salida=$empleado->lunes_salida;
        }

        if($tipo=="entrada")
        {
            if($entrada!=null)
            {
                return $entrada->format("H:i");
            }
            else
            {
                return $entrada;
            }
        }
        else
        {
            return $salida->format("H:i");
        }
    }
}