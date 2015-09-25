<?php

namespace Cms\Orm\Contact\Option;

//<editor-fold defaultstate="collapsed" desc="cms_contact_option Query">
/**
 * @method \Cms\Orm\Contact\Option\Query limit($limit = null)
 * @method \Cms\Orm\Contact\Option\Query offset($offset = null)
 * @method \Cms\Orm\Contact\Option\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Contact\Option\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Contact\Option\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Contact\Option\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Contact\Option\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Contact\Option\Query resetOrder()
 * @method \Cms\Orm\Contact\Option\Query resetWhere()
 * @method \Cms\Orm\Contact\Option\QueryField whereId()
 * @method \Cms\Orm\Contact\Option\QueryField andFieldId()
 * @method \Cms\Orm\Contact\Option\QueryField orFieldId()
 * @method \Cms\Orm\Contact\Option\Query orderAscId()
 * @method \Cms\Orm\Contact\Option\Query orderDescId()
 * @method \Cms\Orm\Contact\Option\Query groupById()
 * @method \Cms\Orm\Contact\Option\QueryField whereSendTo()
 * @method \Cms\Orm\Contact\Option\QueryField andFieldSendTo()
 * @method \Cms\Orm\Contact\Option\QueryField orFieldSendTo()
 * @method \Cms\Orm\Contact\Option\Query orderAscSendTo()
 * @method \Cms\Orm\Contact\Option\Query orderDescSendTo()
 * @method \Cms\Orm\Contact\Option\Query groupBySendTo()
 * @method \Cms\Orm\Contact\Option\QueryField whereName()
 * @method \Cms\Orm\Contact\Option\QueryField andFieldName()
 * @method \Cms\Orm\Contact\Option\QueryField orFieldName()
 * @method \Cms\Orm\Contact\Option\Query orderAscName()
 * @method \Cms\Orm\Contact\Option\Query orderDescName()
 * @method \Cms\Orm\Contact\Option\Query groupByName()
 * @method \Cms\Orm\Contact\Option\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Contact\Option\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Contact\Option\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Contact\Option\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Contact\Option\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Contact\Option\Record[] find()
 * @method \Cms\Orm\Contact\Option\Record findFirst()
 * @method \Cms\Orm\Contact\Option\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_contact_option';

	/**
	 * @return \Cms\Orm\Contact\Option\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
