<?= $this->Form->create(null, ['url' => $url, 'class' => 'form-horizontal disable', 'id' => 'forma_crear_empleado', 'autocomplete' => "off"]) ?>
<div class="form-group">
	<?= $this->Form->label('nombre', 'Nombre: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('nombre', ['class' => 'focus form-control', 'value' => $empleado->nombre]) ?>
	</div>
</div>
<div class="form-group">
	<?= $this->Form->label('apellidos', 'Apellidos: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('apellidos', ['class' => 'focus form-control', 'value' => $empleado->apellidos]) ?>
	</div>
</div>

<?php if($usuario->admin): ?>
<div class="form-group">
	<?= $this->Form->label('entrada', 'Entrada: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('entrada', ['class' => 'focus form-control', 'value' =>$entrada=($empleado->entrada!="")? $empleado->entrada->format("h:i"): "00:00" ]) ?>
	</div>
</div>
<div class="form-group">
	<?= $this->Form->label('salida', 'Salida: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('salida', ['class' => 'focus form-control', 'value' =>$salida=($empleado->salida!="")? $empleado->salida->format("h:i"): "00:00" ]) ?>
	</div>
</div>
<div class="form-group">
	<?= $this->Form->label('descanso', 'Descanso: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('descanso', ['class' => 'focus form-control', 'value' => $empleado->descanso]) ?>
	</div>
</div>
<div class="form-group">
	<?= $this->Form->label('diaextra', 'Dia Extra: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('diaextra', ['class' => 'focus form-control', 'value' => $diaextra=($empleado->dia_extra!=null)?$empleado->dia_extra: 0]) ?>
	</div>
</div>
<div class="form-group">
	<?= $this->Form->label('tipoextra', 'Tipo Extra: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('tipoextra', ['class' => 'focus form-control', 'value' => $tipoextra=($empleado->tipo_extra!=null)?$empleado->tipo_extra: 0 ]) ?>
	</div>
	<div class="col-md-6">
        <span class="help-block">1=Entrada, 2=Salida, 3=Descanso</span>
    </div>
</div>
<?php endif;

if($usuario->admin)
{ ?>
	<div class="form-group">
	<?= $this->Form->label('sucursal', 'Sucursal: ', ['class' => 'col-md-2 control-label']) ?>
		<div class="col-md-3">
			<?= $this->Form->select('sucursal', $this->Select->options($sucursales, 'id', 'nombre', ['blank' => ['' => '--Seleccionar--']]), ['value' => $sucursal, 'class' => 'form-control']) ?>
		</div>
	</div>
<?php
}
else
{ ?>
	<?= $this->Form->hidden('sucursal', ['value' => $usuario->sucursal_id]) ?>
	<div class="form-group">
		<?= $this->Form->label('sucursal', 'Sucursal: ', ['class' => 'col-md-2 control-label']) ?>
		<div class="col-md-3">
			<p class="form-control-static"><?= $usuario->nombre ?></p>
		</div>
	</div>
<?php
} ?>

<div class="form-group">
	<div class="col-md-3 col-md-offset-2">
		<?= $this->Form->button($submit, ['class' => 'btn btn-primary']) ?>
	</div>
</div>
<?= $this->Form->end() ?>