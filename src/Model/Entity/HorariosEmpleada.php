<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HorariosEmpleada Entity
 *
 * @property int $id
 * @property \Cake\I18n\Time $fecha
 * @property \Cake\I18n\Time $fecha_actualizacion
 * @property int $sucursal_id
 * @property int $empleado_id
 * @property int $descanso
 * @property \Cake\I18n\Time $lunes_entrada
 * @property \Cake\I18n\Time $lunes_salida
 * @property \Cake\I18n\Time $martes_entrada
 * @property \Cake\I18n\Time $martes_salida
 * @property \Cake\I18n\Time $miercoles_entrada
 * @property \Cake\I18n\Time $miercoles_salida
 * @property \Cake\I18n\Time $jueves_entrada
 * @property \Cake\I18n\Time $jueves_salida
 * @property \Cake\I18n\Time $viernes_entrada
 * @property \Cake\I18n\Time $viernes_salida
 * @property \Cake\I18n\Time $sabado_entrada
 * @property \Cake\I18n\Time $sabado_salida
 * @property \Cake\I18n\Time $domingo_entrada
 * @property \Cake\I18n\Time $domingo_salida
 *
 * @property \App\Model\Entity\Sucursal $sucursal
 * @property \App\Model\Entity\Empleado $empleado
 */
class HorariosEmpleada extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
