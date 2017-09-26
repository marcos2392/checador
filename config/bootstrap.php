<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// You can remove this if you are confident that your PHP version is sufficient.
if (version_compare(PHP_VERSION, '5.6.0') < 0) {
    trigger_error('Your PHP version must be equal or higher than 5.6.0 to use CakePHP.', E_USER_ERROR);
}

/*
 *  You can remove this if you are confident you have intl installed.
 */
if (!extension_loaded('intl')) {
    trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
}

/*
 * You can remove this if you are confident you have mbstring installed.
 */
if (!extension_loaded('mbstring')) {
    trigger_error('You must enable the mbstring extension to use CakePHP.', E_USER_ERROR);
}

/*
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Request;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
}

/*
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('America/Denver');

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
Email::setConfigTransport(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link http://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')
    ->useImmutable();
Type::build('date')
    ->useImmutable();
Type::build('datetime')
    ->useImmutable();
Type::build('timestamp')
    ->useImmutable();

Inflector::rules('irregular', ['sucursal' => 'sucursales']);

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

/*
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

/*
 * Only try to load DebugKit in development mode
 * Debug Kit should not be installed on a production system
 */
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

/*function horas($minutos)
{
    $horas=floor($minutos/60);
    $min = $minutos%60;
    $m=''; 
    if($min<10): $m='0'; endif;
    $h=''; 
    if($horas<10): $h='0'; endif;
    return $h.$horas.':'.$m.$min;
}*/

function Horas($horas){

    $hrs=floor($horas);
    $min=round(($horas-$hrs)*60);

    return sprintf("%02d:%02d",$hrs,$min);
}

function getDia() {
    
    $dia = date('l', strtotime(date('Y-m-d')));
    if($dia=="Monday")
    {
        $dia=1;
    }
    if($dia=="Tuesday")
    {
        $dia=2;
    }
    if($dia=="Wednesday")
    {
        $dia=3;
    }
    if($dia=="Thursday")
    {
        $dia=4;
    }
    if($dia=="Friday")
    {
        $dia=5;
    }
    if($dia=="Saturday")
    {
        $dia=6;
    }
    if($dia=="Sunday")
    {
        $dia=7;
    }
    return $dia;
}

function Calcular($hora1,$registro,$tipo_extra){

    $salida=explode(':',$hora1); 
    $entrada=explode(':',$registro->entrada->format("H:i"));
    $entrada_horario=explode(':',$registro->entrada_horario->format("H:i"));
    $salida_horario=explode(':',$registro->salida_horario->format("H:i"));

    $minutos_horario_salida=$salida_horario[0]*60+$salida_horario[1]; 
    $minutos_salida=$salida[0]*60+$salida[1];

    $hrs_diferencia=$entrada[0]-$entrada_horario[0];
    $minutos_diferencia=$entrada[1]-$entrada_horario[1];
    
    if($hrs_diferencia<1)
    {
        if($minutos_diferencia>10)
        {
            $entrada[0]=$entrada[0]+1;
            $entrada[1]=$entrada_horario[1];
        }
        else
        {
            if($tipo_extra!=1)
            {
                $entrada=$entrada_horario;
            }
        }
    }

    if($tipo_extra!=2)
    { 
        if($minutos_salida>$minutos_horario_salida)
        {
            $salida=$salida_horario; 
        }
    }

    $total_minutos_transcurridos[1] = ($salida[0]*60)+$salida[1];
    $total_minutos_transcurridos[2] = ($entrada[0]*60)+$entrada[1];
    $total_minutos_transcurridos = $total_minutos_transcurridos[1]-$total_minutos_transcurridos[2];

    $total_minutos_transcurridos=$total_minutos_transcurridos/60;
    $hrs=floor($total_minutos_transcurridos);
    $minutos=($total_minutos_transcurridos*60)%60;

    return ($hrs+$minutos/60);
    
} 

function CalcularHorasDia($hora1,$hora2){

    $salida=explode(':',$hora1);
    $entrada=explode(':',$hora2);

    $total_minutos_transcurridos[1] = ($salida[0]*60)+$salida[1];
    $total_minutos_transcurridos[2] = ($entrada[0]*60)+$entrada[1];
    $total_minutos_transcurridos = $total_minutos_transcurridos[1]-$total_minutos_transcurridos[2];

    $total_minutos_transcurridos=$total_minutos_transcurridos/60; 
    $hrs=floor($total_minutos_transcurridos);
    $minutos=($total_minutos_transcurridos*60)%60;

    return ($hrs+$minutos/60);
} 

function FormatoHora($hora) {

    if($hora!="00:00")
    {
        $separar[1]=explode(':',$hora); 

        $hora=$separar[1][0];
        $minutos=$separar[1][1];
        
        if ($hora == 1) {
            $hora=13;
        } elseif ($hora == 2) {
            $hora=14;
        } elseif ($hora == 3) {
            $hora=15;
        } elseif ($hora == 4) {
            $hora=16;
        }elseif ($hora == 5) {
            $hora=17;
        }elseif ($hora == 6) {
            $hora=18;
        }elseif ($hora == 7) {
            $hora=19;
        }elseif ($hora == 8) {
            $hora=20;
        }

        return $hora.':'.$minutos;
    }
    else
    {
        return null;
    }
}

function ChecadaVisible($empleado,$sucursal){

    if($empleado->sucursal_id==$sucursal and $empleado->checada_visible==false)
    {
        return false;
    }
    else
    {
        return true;
    }
}



