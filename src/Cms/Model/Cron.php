<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm;

class Cron
{

    /**
     * Pobiera zadania crona
     */
    public static function run()
    {
        foreach (Orm\CmsCronQuery::active()->find() as $cron) {
            $logData = [];
            $logData['name'] = $cron->name;
            $logData['dateLastExecute'] = $cron->dateLastExecute;
            $logData['message'] = $cron->name;
            if (!self::_getToExecute($cron)) {
                continue;
            }
            $output = '';
            try {
                $start = microtime(true);
                $output = \Mmi\Mvc\ActionHelper::getInstance()->action(new \Mmi\Http\Request(['module' => $cron->module, 'controller' => $cron->controller, 'action' => $cron->action]));
                $logData['time'] = round(microtime(true) - $start, 4) . 's';
                if ($output) {
                    $logData['message'] = gethostname() . '@' . $cron->name . ': ' . $output;
                }
            } catch (Exception $e) {
                $logData['message'] = $e->__toString();
                //ponowne łączenie
                \App\Registry::$db->connect();
                Log::add('Cron exception', $logData);
            }
            //zmień datę ostatniego wywołania
            $cron->dateLastExecute = date('Y-m-d H:i:s');
            //ponowne łączenie
            \App\Registry::$db->connect();
            //zapis do bazy
            $cron->save();
            if (!$output) {
                continue;
            }
            Log::add('Cron done', $logData);
        }
    }

    /**
     * Sprawdza czy dane zadanie jest do wykonania
     * @param \Cms\Orm\CmsCronRecord $record
     */
    protected static function _getToExecute($record)
    {
        return self::_valueMatch(date('i'), $record->minute) &&
            self::_valueMatch(date('H'), $record->hour) &&
            self::_valueMatch(date('d'), $record->dayOfMonth) &&
            self::_valueMatch(date('m'), $record->month) &&
            self::_valueMatch(date('N'), $record->dayOfWeek);
    }

    /**
     * Dopasowuje 
     * @param integer $current
     * @param integer $value
     * @return boolean
     */
    protected static function _valueMatch($current, $value)
    {
        //Wszystko
        if ($value == '*') {
            return true;
        }
        //Każda wartość podzielna przez
        if (false !== strpos($value, '/')) {
            if ($current % intval(ltrim($value, '*/ ')) == 0) {
                return true;
            }
        }
        //Lista wartości
        $values = explode(',', $value);
        foreach ($values as $val) {
            if (intval($val) == $val && $val == $current) {
                return true;
            }
        }
        //zakres wartości
        if (false !== strpos($value, '-')) {
            $range = explode('-', $value);
            if ($current >= intval($range[0]) && $current <= intval($range[1])) {
                return true;
            }
        }
        return false;
    }

}
