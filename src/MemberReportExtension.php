<?php
namespace SilverStripe\SecurityReport;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Permission;
use SilverStripe\Subsites\Model\Subsite;

/**
 * Extends the {@see Member} class with additional descriptions for elements.
 * See {@see UserSecurityReport} for usage.
 */
class MemberReportExtension extends DataExtension
{
    
    /**
     * Set cast of additional fields
     *
     * @var array
     * @config
     */
    private static $casting = array(
        'GroupsDescription' => 'Text',
        'PermissionsDescription' => 'Text'
    );

    /**
     * Builds a comma separated list of member group names for a given Member.
     *
     * @return string
     */
    public function getGroupsDescription()
    {
        if (class_exists(Subsite::class)) {
            Subsite::disable_subsite_filter(true);
        }
        
        // Get the member's groups, if any
        $groups = $this->owner->Groups();
        if ($groups->Count()) {
            // Collect the group names
            $groupNames = array();
            foreach ($groups as $group) {
                $groupNames[] = html_entity_decode($group->getTreeTitle());
            }
            // return a csv string of the group names, sans-markup
            $result = preg_replace("#</?[^>]>#", '', implode(', ', $groupNames));
        } else {
            // If no groups then return a status label
            $result = _t(__CLASS__ . '.NOGROUPS', 'Not in a Security Group');
        }
        
        if (class_exists(Subsite::class)) {
            Subsite::disable_subsite_filter(false);
        }
        return $result;
    }
    
    /**
     * Builds a comma separated list of human-readbale permissions for a given Member.
     *
     * @return string
     */
    public function getPermissionsDescription()
    {
        if (class_exists(Subsite::class)) {
            Subsite::disable_subsite_filter(true);
        }
        
        $permissionsUsr = Permission::permissions_for_member($this->owner->ID);
        $permissionsSrc = Permission::get_codes(true);
        sort($permissionsUsr);
        
        $permissionNames = array();
        foreach ($permissionsUsr as $code) {
            $code = strtoupper($code);
            foreach ($permissionsSrc as $k => $v) {
                if (isset($v[$code])) {
                    $name = empty($v[$code]['name'])
                        ? _t(__CLASS__ . '.UNKNOWN', 'Unknown')
                        : $v[$code]['name'];
                    $permissionNames[] = $name;
                }
            }
        }

        $result = $permissionNames
            ? implode(', ', $permissionNames)
            : _t(__CLASS__ . '.NOPERMISSIONS', 'No Permissions');
        
        if (class_exists(Subsite::class)) {
            Subsite::disable_subsite_filter(false);
        }
        return $result;
    }
}
