
<?= $this->Form->create(false,['url' => ['action' => 'checar'],'autocomplete' => "off" , 'class' => 'disable', 'data-disable-with' => 'Checando...']) ?>
    <div class="form-group center">
        <div class="form-group col-md-offset-3">
        <?= $this->Form->label('id', 'Empleado: ', ['class' => 'col-md-2 control-label']) ?>

        <select name="empleados" id='mostrar' required>
            <?php $i=0;
             
            foreach ($empleados as $row)  {
                $entrada="false";
                $mixto=false;

                if($i==0)
                {
                    echo '<option value="">--Seleccionar--</option>';
                }

                if($row->horario_mixto==true)
                {
                    $mixto=true;
                }

                foreach($checadas as $checada)
                {
                    if($checada->empleados_id==$row->id)
                    {
                        $entrada="true";
                    }
                }

                    echo '<option data-mixto="'.$mixto.'" data-entrada="'.$entrada.'" value="'.$row['id'].'">'.$row['nombre'].'</option>';
                
                $i++;
            }?>
        </select>

        <!--<select class="selectpicker" name="horarios" id='horarios' class='hidden'>
            <?php             
                /*echo '<option value="">--Seleccionar--</option>';
                echo '<option value="10:00,18:00">10:00-06:00</option>';
                echo '<option value="10:00,20:00">10:00-08:00</option>';
                echo '<option value="10:30,18:30">10:30-06:30</option>';
                echo '<option value="11:00,19:00">11:00-07:00</option>';
                echo '<option value="12:00,20:00">12:00-08:00</option>';
                echo '<option value="13:00,17:00">01:00-05:00</option>';
                echo '<option value="13:00,20:00">01:00-08:00</option>';*/
            ?>
        </select> -->
        
        <br><br>
        <div class=" col-md-1 col-md-offset-3">
            <?= $this->Form->submit('Checar', ['class' => 'btn btn-info']) ?>
        </div>
            
        </div>
        
    </div>

<?= $this->Form->end() ?>
<br><br><br>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 ">
        <table  class="table table-striped">
                <tr class="active">
                    <th>Nombre completo</th>
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
                        <td><?= ($checada->horas!=null)? Horas($checada->horas) : ""  ?></td>
                    </tr>
                <?php }}
                endforeach; ?>
        </table>
    </div>
</div>