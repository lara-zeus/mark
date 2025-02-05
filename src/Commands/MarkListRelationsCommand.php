<?php

namespace LaraZeus\Mark\Commands;

use Illuminate\Console\Command;
use LaraZeus\Mark\Mark as MarkFacade;

class MarkListRelationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Mark system related models and relations';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $marker = MarkFacade::getMarkerModel();
        $rows = [];
        $markerRelations = [];
        foreach (MarkFacade::getMarkablesWithMarks() as $markable => $marks) {
            $markableRelations = [];
            foreach ($marks as $mark) {
                $markableRelations[] = MarkFacade::getMarksRelationName($mark);
                $markableRelations[] = MarkFacade::getMarkRelationName($mark);
                $markableRelations[] = MarkFacade::getMarkersFromMarkableRelationName($markable, $mark);

                $markerRelations[] = MarkFacade::getMarksRelationName($mark);
                $markerRelations[] = MarkFacade::getMarkRelationName($mark);
                $markerRelations[] = MarkFacade::getMarkableFromMarkerRelationName($markable, $mark);
            }
            $rows[] = [$markable, implode(', ', $markableRelations)];
        }

        $rows[] = [$marker, implode(', ', array_unique($markerRelations))];

        $this->table(['Model', 'Relations'], $rows);
    }
}
