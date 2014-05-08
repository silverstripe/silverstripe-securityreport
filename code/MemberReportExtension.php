<?php

/**
 * Extends the {@see Member} class with additional descriptions for elements.
 * See {@see UserSecurityReport} for usage.
 * 
 * @package securityreport
 */
class MemberReportExtension extends DataExtension {
	
	/**
	 * Set cast of additional fields
	 *
	 * @var array
	 * @config
	 */
	private static $casting = array(
		'LastVisitedDescription' => 'Text',
		'GroupsDescription' => 'Text',
		'PermissionsDescription' => 'Text'
	);
	
	/**
	 * Returns a status message for a last visited date
	 * 
	 * @return string
	 */
	public function getLastVisitedDescription() {
		return $this->owner->LastVisited ?: _t('MemberReportExtension.NEVER', 'never');
	}
	
	/**
	 * Builds a comma separated list of member group names for a given Member.
	 *
	 * @return string
	 */
	public function getGroupsDescription() {
		if(class_exists('Subsite')) Subsite::disable_subsite_filter(true);
		
		// Get the member's groups, if any
		$groups = $this->owner->Groups();

		// If no groups then return a status label
		if ($groups->Count() == 0) {
			return _t('MemberReportExtension.NOGROUPS', 'Not in a Security Group');
		}
		
		// Collect the group names
		$groupNames = array();
		foreach ($groups as $group) {
			$groupNames[] = html_entity_decode($group->getTreeTitle());
		}
		
		if(class_exists('Subsite')) Subsite::disable_subsite_filter(false);
		
		// return a csv string of the group names, sans-markup
		return preg_replace("#</?[^>]>#", '', implode(', ', $groupNames));
	}
	
	/**
	 * Builds a comma separated list of human-readbale permissions for a given Member.
	 * 
	 * @return string
	 */
	public function getPermissionsDescription() {
		if(class_exists('Subsite')) Subsite::disable_subsite_filter(true);
		
		$permissionsUsr = Permission::permissions_for_member($this->owner->ID);
		/*
		 * Notes: 
		 * - Permission::get_declared_permissions_list() always returns null.
		 * - Only alternative is to do it how it's done on the Member class. 
		 */
		$permissionsSys = new PermissionCheckboxSetField_Readonly('', '', '', 'GroupID', $this->owner->DirectGroups());
		$permissionsSrc = $permissionsSys->source;
		
		$permissionNames = array();
		foreach ($permissionsUsr as $code) {
			$code = strtoupper($code);
			foreach($permissionsSrc as $k=>$v) {
				if(isset($v[$code])) {
					$name = empty($v[$code]['name'])
						? _t('MemberReportExtension.UNKNOWN', 'Unknown')
						: $v[$code]['name'];
					$permissionNames[] = $name;
				}
			}
		}

		if(!count($permissionNames)) {
			return _t('MemberReportExtension.NOPERMISSIONS', 'No Permissions');
		}
		
		if(class_exists('Subsite')) Subsite::disable_subsite_filter(false);
		
		return implode(', ', $permissionNames);
	}
}
