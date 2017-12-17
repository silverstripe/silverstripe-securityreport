<?php

namespace SilverStripe\SecurityReport\Forms;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;

/**
 * An extension to GridFieldExportButton to support downloading a custom Report as a CSV file
 *
 * This code was adapted from a solution posted in SilverStripe.org forums:
 * http://www.silverstripe.org/customising-the-cms/show/38202
 *
 * @package securityreport
 * @author Michael Armstrong <http://www.silverstripe.org/ForumMemberProfile/show/30887>
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class GridFieldExportReportButton extends GridFieldExportButton
{

    /**
     * Generate export fields for CSV.
     *
     * Replaces the definition in GridFieldExportButton, this is the same as original except
     * it sources the {@link List} from $gridField->getList() instead of $gridField->getManipulatedList()
     *
     * @param GridField $gridField
     * @return array
     */
    public function generateExportFileData($gridField)
    {
        $separator = $this->csvSeparator;
        $csvColumns = ($this->exportColumns)
            ? $this->exportColumns
            : singleton($gridField->getModelClass())->summaryFields();
        $fileData = '';
        $columnData = array();

        if ($this->csvHasHeader) {
            $headers = array();

            // determine the CSV headers. If a field is callable (e.g. anonymous function) then use the
            // source name as the header instead
            foreach ($csvColumns as $columnSource => $columnHeader) {
                if (is_array($columnHeader) && array_key_exists('title', $columnHeader)) {
                    $headers[] = $columnHeader['title'];
                } else {
                    $headers[] = (!is_string($columnHeader) && is_callable($columnHeader))
                        ? $columnSource
                        : $columnHeader;
                }
            }

            $fileData .= "\"" . implode("\"{$separator}\"", array_values($headers)) . "\"";
            $fileData .= "\n";
        }

        // The is the only variation from the parent, using getList() instead of getManipulatedList()
        $items = $gridField->getList();

        // @todo should GridFieldComponents change behaviour based on whether others are available in the config?
        foreach ($gridField->getConfig()->getComponents() as $component) {
            if ($component instanceof GridFieldFilterHeader || $component instanceof GridFieldSortableHeader) {
                $items = $component->getManipulatedData($gridField, $items);
            }
        }

        foreach ($items->limit(null) as $item) {
            $columnData = array();

            foreach ($csvColumns as $columnSource => $columnHeader) {
                if (!is_string($columnHeader) && is_callable($columnHeader)) {
                    if ($item->hasMethod($columnSource)) {
                        $relObj = $item->{$columnSource}();
                    } else {
                        $relObj = $item->relObject($columnSource);
                    }

                    $value = $columnHeader($relObj);
                } else {
                    $value = $gridField->getDataFieldValue($item, $columnSource);
                }

                $value = str_replace(array("\r", "\n"), "\n", $value);
                $columnData[] = '"' . str_replace('"', '\"', $value) . '"';
            }
            $fileData .= implode($separator, $columnData);
            $fileData .= "\n";

            $item->destroy();
        }

        return $fileData;
    }
}
