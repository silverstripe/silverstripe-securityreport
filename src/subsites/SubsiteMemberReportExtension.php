<?php

namespace SilverStripe\SecurityReport;

use SilverStripe\ORM\DataExtension;

return;
/**
 * Adds 'SubsiteDescription' for to show which subsites this Member has edit access to
 *
 * This part is broken, as SubSites is not working on SS4 yet
 *
 * @author Damian Mooyman <damian@silverstripe.com>
 * @package securityreport
 * @subpackage subsites
 */
class SubsiteMemberReportExtension extends DataExtension {
	
	/**
	 * Set cast of additional field
	 *
	 * @var array
	 * @config
	 */
	private static $casting = array(
		'SubsiteDescription' => 'Text'
	);
	
	/**
	 * Default permission to filter for
	 * 
	 * @var string
	 * @config
	 */
	private static $subsite_description_permission = 'SITETREE_EDIT_ALL';
	
	/**
	 * Describes the subsites this user has SITETREE_EDIT_ALL access to
	 * 
	 * @return string
	 */
	public function getSubsiteDescription() {
		$subsites = Subsite::accessible_sites(
			$this->owner->config()->subsite_description_permission,
			true,
			"Main site",
			$this->owner
		);
		return implode(', ', $subsites->column('Title'));
	}
}
