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
class SucursalesController extends AppController
{
    public function beforeFilter(Event $event) {
        
    }

    public function sucursales() {

    	$sucursales=$this->Sucursales->find()
    	->order('nombre')
    	->where(['status'=>1]);

        $this->set(compact('sucursales'));
    }

    public function nuevo() {

        $sucursal = $this->Sucursales->newEntity();

        $this->set(compact('sucursal'));
    }

    public function crear() {

        $usuario=$this->getusuario();
        $nombre = $this->request->getData('nombre');
        $nombre = ucwords(strtolower($nombre));

        $sucursal_existente = $this->Sucursales->find()
        ->where(['nombre' => $nombre])
        ->first();

        if ($sucursal_existente) 
        {
            $this->Flash->error('Ya existe una Sucursal con ese nombre: ' . $nombre);
            $this->redirect(['action' => 'nuevo']);
            return;
        }

        $sucursal = $this->Sucursales->newEntity();
        $sucursal->nombre=$nombre;
        $sucursal->status=1;
       
        if($nombre=="")
        {
            $this->Flash->default("Necesita llenar todos los campos");
            $this->redirect(['action' => 'nuevo']); 
        }
        else
        {
            $this->Sucursales->save($sucursal);

            $this->Flash->default("Se creó la Sucursal: ".$nombre." , Exitosamente.");
            $this->redirect(['action' => 'sucursales']); 
        }
    }

    public function editar() {

        $sucursal = $this->Sucursales->get($this->request->getQuery('id'));
        
        $this->set(compact('sucursal'));
    }

    public function actualizar() {

        $sucursal = $this->Sucursales->get($this->request->getQuery('id'));

        $nombres = $this->request->getData('nombre') ?? '';
        $nombres = ucwords(strtolower($nombres));
        
        $sucursal->nombre=$nombres;
 
       	if ($this->request->is('post'))
        {
            if($this->Sucursales->save($sucursal))
             {
                $this->Flash->default("Se actualizo la Sucursal: ".$nombres." ,exitosamente.");
                $this->redirect(['action' => 'sucursales']);
             }
             else
             {
               $this->Flash->error("Hubo un Error al Actualizar la Sucursal.");
               $this->redirect(['action' => 'editar']);
             }
        }
    }

    public function eliminar() {

        if ($this->Sucursales->get($this->request->getQuery('id'))) 
        {
            $sucursal = $this->Sucursales->get($this->request->getQuery('id'));
            $sucursal->status=0;

            $this->Sucursales->save($sucursal);

            $this->Flash->default("Se elimino la sucursal correctamente.");
            $this->redirect(['action' => 'sucursales']);
        }
        else
        {
            $this->Flash->default("no");
            $this->redirect(['action' => 'sucursales']);
        }
    }
}