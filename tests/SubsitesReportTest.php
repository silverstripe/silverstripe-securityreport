<?php
/**
 * User Security Report Tests.
 *
 * @package securityreport
 * @subpackage tests
 * @author Damian Mooyman <damian@silverstripe.com>
 */
class SubsitesReportTest extends SapphireTest {

	protected static $fixture_file = 'SubsitesReportTest.yml';
	
	protected $records;
	
	protected $requiredExtensions = array(
		'Member' => array('MemberReportExtension', 'SubsiteMemberReportExtension')
	);
	
	public function setUp() {
		parent::setUp();
		
		if(!class_exists('Subsite')) {
			$this->skipTest = true;
			return $this->markTestSkipped("Please install Subsites to run this test");
		}
		
		$reports = SS_Report::get_reports();
		$report = $reports['UserSecurityReport'];
		$this->records = $report->sourceRecords()->toArray();
	}

	public function testSourceRecords() {
		$this->assertNotEmpty($this->records);
	}

	public function testGetMemberGroups() {
		
		// Admin
		$admin = $this->objFromFixture('Member', 'memberadmin');
		$subsites = $admin->SubsiteDescription;
		$this->assertContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
		
		// Editor
		$membereditor = $this->objFromFixture('Member', 'membereditor');
		$subsites = $membereditor->SubsiteDescription;
		$this->assertContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
		
		// First User
		$membersubsite1 = $this->objFromFixture('Member', 'membersubsite1');
		$subsites = $membersubsite1->SubsiteDescription;
		$this->assertNotContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertNotContains('TestSubsite2', $subsites);
		
		// Second user
		$memberallsubsites = $this->objFromFixture('Member', 'memberallsubsites');
		$subsites = $memberallsubsites->SubsiteDescription;
		$this->assertNotContains('TestMainSite', $subsites);
		$this->assertContains('TestSubsite1', $subsites);
		$this->assertContains('TestSubsite2', $subsites);
	}
}
