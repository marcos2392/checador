<h2>Edicion Horario Proxima Semana</h2>
<br>
<?= $this->element('forma_horario_semanal', ['url' => ['action' => 'actualizar','sucursal'=>$sucursal,'horarios_semanal'=>$horarios_semanal], 'submit' => 'Actualizar']) ?>