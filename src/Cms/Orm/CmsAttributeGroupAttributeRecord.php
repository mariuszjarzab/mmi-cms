<?php

namespace Cms\Orm;

class CmsAttributeGroupAttributeRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsAttributeId;
	public $cmsAttributeGroupId;
	public $required;
	public $unique;
	public $materialized;
	public $active;

}
