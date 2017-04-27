<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property string $nombre
 * @property string $usuario
 * @property string $password
 * @property bool $status
 * @property int $sucursal_id
 * @property int $tipo_usuario
 *
 * @property \App\Model\Entity\Sucursal $sucursal
 */
class Usuario extends Entity
{

    protected $_accessible = [
        '*' => true,
        'usuario' => false
    ];
    
    function hashPasswords($data) {
        return $data;
    }

    /*protected function _getNombre() {
        return $this->_properties['nombre'];
    }*/

    protected function _getSuc() {
        return $this->_properties['sucursal_id'];
    }

    protected function _getAdmin() {
        return trim($this->_properties['tipo_usuario']) == '1';
    }
}
