<?php
return;
/**
 * @broken, SubSites currently is unavailable on SS4
 *
 */
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Member;
use SilverStripe\SecurityReport\MemberReportExtension;
use SilverStripe\SecurityReport\SubsiteMemberReportExtension;
use SilverStripe\SecurityReport\UserSecurityReport;

/**
 * User Security Report Tests.
 *
 * @package securityreport
 * @subpackage tests
 * @author Damian Mooyman <damian@silverstripe.com>
 *
class SubsitesReportTest extends SapphireTest {

	protected static $fixture_file = 'SubsitesReportTest.yml';
	
	protected $records;
	
	protected $requiredExtensions = array(
		Member::class => [MemberReportExtension::class, SubsiteMemberReportExtension::class]
	);

    public function setUp() {
		parent::setUp();
		
		if(!class_exists('Subsite')) {
			$this->skipTest = true;
			$this->markTestSkipped("Please install Subsites to run this test");
		}
		
		$reports = Report::get_reports();
		$report = $reports[UserSecurityReport::class];
		$this->records = $report->sourceRecords()->toArray();
	}

	public function testSourceRecords() {
		$this->assertNotEmpty($this->records);
	}

	public function testGetMemberGroups() {
		
		// Admin
		$admin = $this->objFromFixture(Member::class, 'memberadmin');
		$subsites = $admin->SubsiteDescription;
		$this->assertContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
		
		// Editor
		$membereditor = $this->objFromFixture(Member::class, 'membereditor');
		$subsites = $membereditor->SubsiteDescription;
		$this->assertContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
		
		// First User
		$membersubsite1 = $this->objFromFixture(Member::class, 'membersubsite1');
		$subsites = $membersubsite1->SubsiteDescription;
		$this->assertNotContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertNotContains('TestSubsite2', $subsites);
		
		// Second user
		$memberallsubsites = $this->objFromFixture(Member::class, 'memberallsubsites');
		$subsites = $memberallsubsites->SubsiteDescription;
		$this->assertNotContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
	}
}
/**/