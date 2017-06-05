<h2>Usuarios</h2>
<?php if($usuario->admin): ?><h4><?=$this->Html->link('Nuevo',['controller' =>'Usuarios','action' => 'nuevo']); ?></h4> <?php endif; ?>
<br>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 ">
        <table  class="table table-striped">
                <tr class="active">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Sucursal</th>
                    <th>Tipo Usuario</th>
                    <th colspan="2"></th>
                </tr>

                <?php 
                $contador=1;
                foreach ($user as $usr):
                $tipo=($usr["usuario"]->admin)? "Admin" : ""; ?>
                    <tr>
                        <td><?= $contador ?></td>
                        <td><?= $usr["usuario"]->nombre ?></td>
                        <td><b><?= $usr["sucursal"] ?></b></td>
                        
                        <td><?= $tipo ?></td>
                        <td><?= $this->Html->link('Editar', ['action' => 'editar', 'id' => $usr["usuario"]->id]) ?></td>
                        <td><?= $this->Html->link('Eliminar', ['action' => 'eliminar', 'id' => $usr["usuario"]->id]) ?></td>
                    </tr>
                <?php
                    $contador++;
                endforeach; ?>
        </table>
    </div>
</div>