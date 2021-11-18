<?php

namespace SilverStripe\SecurityReport\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Member;
use SilverStripe\SecurityReport\MemberReportExtension;
use SilverStripe\SecurityReport\Subsites\SubsiteMemberReportExtension;
use SilverStripe\SecurityReport\UserSecurityReport;
use SilverStripe\Subsites\Model\Subsite;

/**
 * User Security Report Tests.
 *
 * @author Damian Mooyman <damian@silverstripe.com>
 */
class SubsitesReportTest extends SapphireTest
{

    protected static $fixture_file = 'SubsitesReportTest.yml';

    protected $records;

    protected static $required_extensions = [
        Member::class => [
            MemberReportExtension::class,
            SubsiteMemberReportExtension::class,
        ],
    ];

    protected function setUp(): void
    {
        if (!class_exists(Subsite::class)) {
            // Don't break the parent:setUp() when failing to create Subsite fixtures
            static::$fixture_file = null;
            parent::setUp();
            $this->markTestSkipped("Please install Subsites to run this test");
        }

        parent::setUp();

        $reports = Report::get_reports();
        $report = $reports[UserSecurityReport::class];
        $this->records = $report->sourceRecords()->toArray();
    }

    public function testSourceRecords()
    {
        $this->assertNotEmpty($this->records);
    }

    public function testGetMemberGroups()
    {

        // Admin
        $admin = $this->objFromFixture(Member::class, 'memberadmin');
        $subsites = $admin->SubsiteDescription;
        $this->assertStringContainsString('TestMainSite', $subsites);
        $this->assertStringContainsString('TestSubsite1', $subsites);
        $this->assertStringContainsString('TestSubsite2', $subsites);

        // Editor
        $membereditor = $this->objFromFixture(Member::class, 'membereditor');
        $subsites = $membereditor->SubsiteDescription;
        $this->assertStringContainsString('TestMainSite', $subsites);
        $this->assertStringContainsString('TestSubsite1', $subsites);
        $this->assertStringContainsString('TestSubsite2', $subsites);

        // First User
        $membersubsite1 = $this->objFromFixture(Member::class, 'membersubsite1');
        $subsites = $membersubsite1->SubsiteDescription;
        $this->assertStringNotContainsString('TestMainSite', $subsites);
        $this->assertStringContainsString('TestSubsite1', $subsites);
        $this->assertStringNotContainsString('TestSubsite2', $subsites);

        // Second user
        $memberallsubsites = $this->objFromFixture(Member::class, 'memberallsubsites');
        $subsites = $memberallsubsites->SubsiteDescription;
        $this->assertStringNotContainsString('TestMainSite', $subsites);
        $this->assertStringContainsString('TestSubsite1', $subsites);
        $this->assertStringContainsString('TestSubsite2', $subsites);
    }
}
