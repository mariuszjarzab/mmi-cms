<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsCronQuery">
/**
 * @method CmsCronQuery limit($limit = null)
 * @method CmsCronQuery offset($offset = null)
 * @method CmsCronQuery orderAsc($fieldName, $tableName = null)
 * @method CmsCronQuery orderDesc($fieldName, $tableName = null)
 * @method CmsCronQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsCronQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsCronQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsCronQuery resetOrder()
 * @method CmsCronQuery resetWhere()
 * @method QueryHelper\CmsCronQueryField whereId()
 * @method QueryHelper\CmsCronQueryField andFieldId()
 * @method QueryHelper\CmsCronQueryField orFieldId()
 * @method CmsCronQuery orderAscId()
 * @method CmsCronQuery orderDescId()
 * @method CmsCronQuery groupById()
 * @method QueryHelper\CmsCronQueryField whereActive()
 * @method QueryHelper\CmsCronQueryField andFieldActive()
 * @method QueryHelper\CmsCronQueryField orFieldActive()
 * @method CmsCronQuery orderAscActive()
 * @method CmsCronQuery orderDescActive()
 * @method CmsCronQuery groupByActive()
 * @method QueryHelper\CmsCronQueryField whereMinute()
 * @method QueryHelper\CmsCronQueryField andFieldMinute()
 * @method QueryHelper\CmsCronQueryField orFieldMinute()
 * @method CmsCronQuery orderAscMinute()
 * @method CmsCronQuery orderDescMinute()
 * @method CmsCronQuery groupByMinute()
 * @method QueryHelper\CmsCronQueryField whereHour()
 * @method QueryHelper\CmsCronQueryField andFieldHour()
 * @method QueryHelper\CmsCronQueryField orFieldHour()
 * @method CmsCronQuery orderAscHour()
 * @method CmsCronQuery orderDescHour()
 * @method CmsCronQuery groupByHour()
 * @method QueryHelper\CmsCronQueryField whereDayOfMonth()
 * @method QueryHelper\CmsCronQueryField andFieldDayOfMonth()
 * @method QueryHelper\CmsCronQueryField orFieldDayOfMonth()
 * @method CmsCronQuery orderAscDayOfMonth()
 * @method CmsCronQuery orderDescDayOfMonth()
 * @method CmsCronQuery groupByDayOfMonth()
 * @method QueryHelper\CmsCronQueryField whereMonth()
 * @method QueryHelper\CmsCronQueryField andFieldMonth()
 * @method QueryHelper\CmsCronQueryField orFieldMonth()
 * @method CmsCronQuery orderAscMonth()
 * @method CmsCronQuery orderDescMonth()
 * @method CmsCronQuery groupByMonth()
 * @method QueryHelper\CmsCronQueryField whereDayOfWeek()
 * @method QueryHelper\CmsCronQueryField andFieldDayOfWeek()
 * @method QueryHelper\CmsCronQueryField orFieldDayOfWeek()
 * @method CmsCronQuery orderAscDayOfWeek()
 * @method CmsCronQuery orderDescDayOfWeek()
 * @method CmsCronQuery groupByDayOfWeek()
 * @method QueryHelper\CmsCronQueryField whereName()
 * @method QueryHelper\CmsCronQueryField andFieldName()
 * @method QueryHelper\CmsCronQueryField orFieldName()
 * @method CmsCronQuery orderAscName()
 * @method CmsCronQuery orderDescName()
 * @method CmsCronQuery groupByName()
 * @method QueryHelper\CmsCronQueryField whereDescription()
 * @method QueryHelper\CmsCronQueryField andFieldDescription()
 * @method QueryHelper\CmsCronQueryField orFieldDescription()
 * @method CmsCronQuery orderAscDescription()
 * @method CmsCronQuery orderDescDescription()
 * @method CmsCronQuery groupByDescription()
 * @method QueryHelper\CmsCronQueryField whereModule()
 * @method QueryHelper\CmsCronQueryField andFieldModule()
 * @method QueryHelper\CmsCronQueryField orFieldModule()
 * @method CmsCronQuery orderAscModule()
 * @method CmsCronQuery orderDescModule()
 * @method CmsCronQuery groupByModule()
 * @method QueryHelper\CmsCronQueryField whereController()
 * @method QueryHelper\CmsCronQueryField andFieldController()
 * @method QueryHelper\CmsCronQueryField orFieldController()
 * @method CmsCronQuery orderAscController()
 * @method CmsCronQuery orderDescController()
 * @method CmsCronQuery groupByController()
 * @method QueryHelper\CmsCronQueryField whereAction()
 * @method QueryHelper\CmsCronQueryField andFieldAction()
 * @method QueryHelper\CmsCronQueryField orFieldAction()
 * @method CmsCronQuery orderAscAction()
 * @method CmsCronQuery orderDescAction()
 * @method CmsCronQuery groupByAction()
 * @method QueryHelper\CmsCronQueryField whereDateAdd()
 * @method QueryHelper\CmsCronQueryField andFieldDateAdd()
 * @method QueryHelper\CmsCronQueryField orFieldDateAdd()
 * @method CmsCronQuery orderAscDateAdd()
 * @method CmsCronQuery orderDescDateAdd()
 * @method CmsCronQuery groupByDateAdd()
 * @method QueryHelper\CmsCronQueryField whereDateModified()
 * @method QueryHelper\CmsCronQueryField andFieldDateModified()
 * @method QueryHelper\CmsCronQueryField orFieldDateModified()
 * @method CmsCronQuery orderAscDateModified()
 * @method CmsCronQuery orderDescDateModified()
 * @method CmsCronQuery groupByDateModified()
 * @method QueryHelper\CmsCronQueryField whereDateLastExecute()
 * @method QueryHelper\CmsCronQueryField andFieldDateLastExecute()
 * @method QueryHelper\CmsCronQueryField orFieldDateLastExecute()
 * @method CmsCronQuery orderAscDateLastExecute()
 * @method CmsCronQuery orderDescDateLastExecute()
 * @method CmsCronQuery groupByDateLastExecute()
 * @method QueryHelper\CmsCronQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCronQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsCronQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsCronQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsCronQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsCronRecord[] find()
 * @method CmsCronRecord findFirst()
 * @method CmsCronRecord findPk($value)
 */
//</editor-fold>
class CmsCronQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_cron';

	/**
	 * @return CmsCronQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

	/**
	 * Zapytanie o aktywne cron'y
	 * @return CmsCronQuery
	 */
	public static function active() {
		return self::factory()
				->whereActive()->equals(1)
				->orderAscId();
	}

}
