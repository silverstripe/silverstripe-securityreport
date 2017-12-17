<?php

namespace SilverStripe\SecurityReport\Subsites;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Subsites\Model\Subsite;

/**
 * Adds 'SubsiteDescription' for to show which subsites this Member has edit access to
 *
 * @author Damian Mooyman <damian@silverstripe.com>
 */
class SubsiteMemberReportExtension extends DataExtension
{
    
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
    public function getSubsiteDescription()
    {
        $subsites = Subsite::accessible_sites(
            $this->owner->config()->get('subsite_description_permission'),
            true,
            "Main site",
            $this->owner
        );
        return implode(', ', $subsites->column('Title'));
    }
}
