<?php namespace Immovables\Immovables\Models;
class RealestatedsExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        foreach (Realestated::cursor() as $record) {
            $record->addVisible($columns);
            yield $record->toArray();
        }
    }
}