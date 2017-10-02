<?= $this->Form->create(null, ['url' => $url, 'class' => 'form-horizontal disable', 'id' => 'forma_horario_semanal', 'autocomplete' => "off"]) ?>
<div class="row">
    <div class="col-sm-12 ">
        <table  class="table table-striped table table-bordered">
            <tr class="active">
                <th width="10%">ID</th>
                <th width="10%">Nombre</th>
                <th >Lunes</th>
                <th >Martes</th>
                <th >Miercoles</th>
                <th >Jueves</th>
                <th >Viernes</th>
                <th >Sabado</th>
                <th >Domingo</th>
                <th >Descanso</th>
            </tr>
            <?php 

            $dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];

                foreach($empleados as $emp)
                {
                    
                        $nombre=$emp->ncompleto;
                    
                 ?>
                <tr>
                    <td width="10%"><?= $emp->id ?></td>
                    <td width="10%"><?= $nombre ?></td>
                <?php 
                foreach($dias as $d)
                {
                    $entrada=($emp->{$d."_entrada"}!=null)? $emp->{$d."_entrada"}->format("h:s"): "00:00" ;
                    $salida=($emp->{$d."_salida"}!=null)? $emp->{$d."_salida"}->format("h:s"): "00:00" ;

                    ?>
                    
                    <td width="80px"><?= $this->Form->text('empleados['.$emp->id.']['.$emp->empleado_id.']['.$d.'_entrada]', ['class' => 'focus form-control', 'value'=> $entrada ]) ?>
                    <?= $this->Form->text('empleados['.$emp->id.']['.$emp->empleado_id.']['.$d.'_salida]', ['class' => 'focus form-control', 'value'=> $salida ]) ?> </td>
                     
                    <?php
                }?>
                    <td width="80px"><?= $this->Form->text('empleados['.$emp->id.']['.$emp->empleado_id.'][descanso]', ['class' => 'focus form-control', 'value'=> ($emp->descanso)?$emp->descanso: 0 ]) ?>
                    </td>
                </tr>
                <?php
                }

            ?>
        </table>
    </div>
</div>
<div class="form-group">
    <div class="col-md-6 col-md-offset-5">
        <?= $this->Form->button($submit, ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?= $this->Form->end() ?>