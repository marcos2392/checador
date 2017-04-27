<?= $this->Form->create(null, ['url' => $url, 'class' => 'form-horizontal disable', 'id' => 'forma_crear_sucursal', 'autocomplete' => "off"]) ?>
<div class="form-group">
	<?= $this->Form->label('nombre', 'Nombre: ', ['class' => 'col-md-2 control-label']) ?>
	<div class="col-md-3">
		<?= $this->Form->text('nombre', ['class' => 'focus form-control', 'value' => $sucursal->nombre]) ?>
	</div>
</div>
<div class="form-group">
	<div class="col-md-3 col-md-offset-2">
		<?= $this->Form->button($submit, ['class' => 'btn btn-primary']) ?>
	</div>
</div>
<?= $this->Form->end() ?>