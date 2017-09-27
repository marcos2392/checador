<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HorariosEmpleadas Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Sucursales
 * @property \Cake\ORM\Association\BelongsTo $Empleados
 *
 * @method \App\Model\Entity\HorariosEmpleada get($primaryKey, $options = [])
 * @method \App\Model\Entity\HorariosEmpleada newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HorariosEmpleada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HorariosEmpleada|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HorariosEmpleada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HorariosEmpleada[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HorariosEmpleada findOrCreate($search, callable $callback = null, $options = [])
 */
class HorariosEmpleadasTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('horarios_empleadas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sucursales', [
            'foreignKey' => 'sucursal_id'
        ]);
        $this->belongsTo('Empleados', [
            'foreignKey' => 'empleado_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->dateTime('fecha')
            ->allowEmpty('fecha');

        $validator
            ->dateTime('fecha_actualizacion')
            ->allowEmpty('fecha_actualizacion');

        $validator
            ->integer('descanso')
            ->allowEmpty('descanso');

        $validator
            ->time('lunes_entrada')
            ->allowEmpty('lunes_entrada');

        $validator
            ->time('lunes_salida')
            ->allowEmpty('lunes_salida');

        $validator
            ->time('martes_entrada')
            ->allowEmpty('martes_entrada');

        $validator
            ->time('martes_salida')
            ->allowEmpty('martes_salida');

        $validator
            ->time('miercoles_entrada')
            ->allowEmpty('miercoles_entrada');

        $validator
            ->time('miercoles_salida')
            ->allowEmpty('miercoles_salida');

        $validator
            ->time('jueves_entrada')
            ->allowEmpty('jueves_entrada');

        $validator
            ->time('jueves_salida')
            ->allowEmpty('jueves_salida');

        $validator
            ->time('viernes_entrada')
            ->allowEmpty('viernes_entrada');

        $validator
            ->time('viernes_salida')
            ->allowEmpty('viernes_salida');

        $validator
            ->time('sabado_entrada')
            ->allowEmpty('sabado_entrada');

        $validator
            ->time('sabado_salida')
            ->allowEmpty('sabado_salida');

        $validator
            ->time('domingo_entrada')
            ->allowEmpty('domingo_entrada');

        $validator
            ->time('domingo_salida')
            ->allowEmpty('domingo_salida');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['sucursal_id'], 'Sucursales'));
        $rules->add($rules->existsIn(['empleado_id'], 'Empleados'));

        return $rules;
    }
}
