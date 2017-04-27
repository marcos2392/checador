
<?= $this->Form->create(false,['url' => ['action' => 'checar'],'autocomplete' => "off"]) ?>
    <div class="form-group center">
        <div class="form-group col-md-offset-3">
        <?= $this->Form->label('id', 'Empleado: ', ['class' => 'col-md-2 control-label']) ?>
            <div class="col-md-3">
                <?= $this->Form->select('id', $this->Select->options($empleados, 'id', 'nombre', ['blank' => ['' => '--Seleccionar--']]), ['value' => $empleados, 'class' => 'form-control']) ?>
            </div>
        </div>
        <div class=" col-md-1">
            <?= $this->Form->submit('Entrada', ['class' => 'btn btn-info']) ?>
        </div>
    </div>

<?= $this->Form->end() ?>
<br><br><br>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 ">
        <table  class="table table-striped">
                <tr class="active">
                    <th>Nombre</th>
                    <th>Sucursal</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Horas</th>
                </tr>
                
                <?php foreach ($empleados as $empleado):
                $id_dup='';
                foreach($checadas as $checada){
                    if($checada->empleados_id==$empleado->id){
                        if($empleado->id==$id_dup)
                        {
                            $emp="";
                        }
                        else
                        {
                            $emp=$empleado->ncompleto;
                            $id_dup=$empleado->id;
                        }
                    ?>
                    <tr>
                        <td ><?= $emp ?></td>
                        <td><?= $empleado->sucursal->nombre ?></td>
                        <td><?= $checada->entrada->format("h:i") ?></td>
                        <td><?= ($checada->salida!=null)? $checada->salida->format("h:i"): ""  ?></td>
                        <td><?= ($checada->horas!=null)? $checada->horas->format("H:i"): ""  ?></td>
                    </tr>
                <?php }}
                endforeach; ?>
        </table>
    </div>
</div>