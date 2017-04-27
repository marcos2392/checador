<h3><b>Reportes</b></h3>

<?= $this->Form->create(null, ['class' => ' form-inline hidden-print', 'id' => 'reporte_ventas_forma']) ?>

<div class="form-group ">
    <div class="radio">
        <label >
            <input type="radio" name="filtro" value="semanal" <?php if ($filtro == "semanal") echo "checked" ?> /> Semanal Nomina
        </label>
    </div>
</div>
<br><br>
<div class="form-group  ">
    <div class="radio">
        <label>
            <input type="radio" name="filtro" value="rango" <?php if ($filtro == "rango") echo "checked" ?> /> Rango
        </label>
    </div>
</div>
<div class="form-group ">
    
    <?= $this->element('select_fecha', [
        'prefijo' => 'fecha1',
        'fecha' => $fechas['f1']
    ]) ?>
    -
    <?= $this->element('select_fecha', [
        'prefijo' => 'fecha2',
        'fecha' => $fechas['f2']
    ]) ?>
</div>
<?php if ($usuario->admin): ?>
    <div class="form-group">
        <div class="col-md-1">
            <?= $this->Form->label('sucursal', 'Sucursal:', ['class' => 'control-label']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= $this->Form->select('sucursal', $this->Select->options($sucursales, 'id', 'nombre'), ['value' => $sucursal, 'class' => 'form-control']) ?>
    </div>

<?php endif; ?>
<div class="form-group">
    <div class="col-md-offset-1 col-md-11">
        <?= $this->Form->button('Enviar', ['class' => 'btn btn-info']) ?>
    </div>
</div>
<?= $this->Form->end() ?>

<br>   
<?php if(!empty($registro)): ?>
<h4><b>Sucursal :</b><?= $sucursal_nombre ?></h4>
<h4><b>Fecha :</b><?= $inicio ?>/<?= $fin ?></h4>
<br>
<h4><div class="inline link hidden-print"><?= $this->Html->link('Imprimir', '#', ['class' => 'link_imprimir']) ?></div></h4>

<div class="row">
    <div class="col-sm-12 ">
        <table  class="table table-striped table table-bordered">
            <tr class="active">
	            <th colspan="3">Nombre</th>
	            <th colspan="2">Lunes</th>
	            <th colspan="2">Martes</th>
	            <th colspan="2">Miercoles</th>
	            <th colspan="2">Jueves</th>
                <th colspan="2">Viernes</th>
                <th colspan="2">Sabado</th>
                <th colspan="2">Domingo</th>
                <th colspan="2">R</th>
                <th colspan="2">F</th>
                <th colspan="2">Hrs</th>
	            </tr>
                <?php
                foreach($registro as $id=>$reg): ?>
                    <tr>
                        <td colspan="3"><?= $reg[0]->empleado->ncompleto ?></td>
                        <?php 
                        $contador=false;
                        $retardos=0;
                        $faltas=0;
                        $minutos=0;

                        foreach(range(1,7) as $dia):
                            $descanso=false;
                            $falta=false;
                            $asistio=false;
                            $entrada="";
                            $salida="";

                            foreach($reg as $r):
                                if($contador==false)
                                {
                                    if($r->retardo): $retardos++; endif;
                                    if($r->falta): $faltas++; endif;
                                    if($r->horas!=NULL): $minutos+=$r->minutos(); endif;
                                }
                                if($r->dia==$dia)
                                { 
                                    $asistio=true;
                                    if($r->entrada!= NULL)
                                    {
                                        if($entrada!="")
                                        {
                                            $entrada.= "<br>";
                                            $salida.= "<br>";
                                        }

                                        $entrada.=$r->entrada->format("h:i");
                                        if ($r->salida) {
                                            $salida.=$r->salida->format("h:i");
                                        }
                                    }
                                    else
                                    {
                                        if($r->descanso==1):$descanso=true; endif;
                                        if($r->falta==1):$falta=true; endif;
                                    }
                                }
                            endforeach;
                            $contador=true;
                            if($asistio)
                            { 
                                if($descanso==true || $falta==true)
                                    { 
                                        if($descanso==true): ?><td colspan="2">Descanso</td><?php endif;
                                        if($falta==true): ?><td colspan="2">Falta</td><?php endif;
                                    }
                                else
                                {  ?>
                                        <td><?= $entrada ?></td>
                                        <td><?= $salida ?></td>
                                <?php }
                            }
                            else
                            { ?>
                                <td colspan="2"></td>
                            <?php }
                        endforeach; ?>
                        <td colspan="2"><?=  $retardos ?></td>
                        <td colspan="2"><?=  $faltas ?></td>
                        <td colspan="2"><?= $hrs_t=horas($minutos); ?></td>
                    </tr>
                <?php endforeach; ?>
        </table>
    </div>
</div>
<?php endif; ?>

