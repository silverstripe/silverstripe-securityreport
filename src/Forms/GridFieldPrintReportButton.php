<?php

namespace SilverStripe\SecurityReport\Forms;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;

/**
 * An extension to GridFieldPrintButton to support printing custom Reports
 *
 * This code was adapted from a solution posted in SilverStripe.org forums:
 * http://www.silverstripe.org/customising-the-cms/show/38202
 *
 * @package securityreport
 * @author Michael Armstrong <http://www.silverstripe.org/ForumMemberProfile/show/30887>
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class GridFieldPrintReportButton extends GridFieldPrintButton
{

    /**
     * Export core
     *
     * Replaces definition in GridFieldPrintButton
     * same as original except sources data from $gridField->getList() instead of $gridField->getManipulatedList()
     *
     * @param GridField
     * @return ArrayData
     */
    public function generatePrintData(GridField $gridField)
    {
        $printColumns = $this->getPrintColumnsForGridField($gridField);
        $header = null;

        if ($this->printHasHeader) {
            $header = new ArrayList();
            foreach ($printColumns as $field => $label) {
                $header->push(new ArrayData(array(
                    "CellString" => $label,
                )));
            }
        }

        // The is the only variation from the parent class, using getList() instead of getManipulatedList()
        $items = $gridField->getList();

        $itemRows = new ArrayList();

        foreach ($items as $item) {
            $itemRow = new ArrayList();

            foreach ($printColumns as $field => $label) {
                $value = $gridField->getDataFieldValue($item, $field);
                $itemRow->push(new ArrayData(array(
                    "CellString" => $value,
                )));
            }

            $itemRows->push(new ArrayData(array(
                "ItemRow" => $itemRow
            )));

            $item->destroy();
        }

        $ret = new ArrayData(array(
            "Title" => $this->getTitle($gridField),
            "Header" => $header,
            "ItemRows" => $itemRows,
            "Datetime" => DBDatetime::now(),
            "Member" => Security::getCurrentUser(),
        ));

        return $ret;
    }
}
