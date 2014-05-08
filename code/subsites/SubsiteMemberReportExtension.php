<?php

/**
 * Extends the {@see Member} class with additional descriptions for elements.
 * See {@see UserSecurityReport} for usage.
 * 
 * @package securityreport
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
	 * Describes the subsites this user has any access to
	 * 
	 * @return string
	 */
	public function getSubsiteDescription() {
		$subsites = Subsite::accessible_sites('SITETREE_EDIT_ALL', true, "Main site", $this->owner)
			->column('Title');
		return implode(', ', $subsites);
	}
}
