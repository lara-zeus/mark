<?php

namespace LaraZeus\Mark\Commands;

use Illuminate\Console\Command;
use LaraZeus\Mark\Facades\Mark;

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
        $marker = Mark::getMarkerModel();
        $rows = [];
        $markerRelations = [];
        foreach (Mark::getMarkablesWithMarks() as $markable => $marks) {
            $markableRelations = [];
            foreach ($marks as $mark) {
                $markableRelations[] = '(' . Mark::getMarksRelationName($mark) . ', type: morphMany)';
                $markableRelations[] = '(' . Mark::getMarkRelationName($mark) . ', type: morphOne)';
                $markableRelations[] = '(' . Mark::getMarkersFromMarkableRelationName($markable, $mark) . ', type: morphedToMany)';

                $markerRelations[] = '(' . Mark::getMarksRelationName($mark) . ', type: hasMany)';
                $markerRelations[] = '(' . Mark::getMarkRelationName($mark) . ', type: hasOne)';
                $markerRelations[] = '(' . Mark::getMarkableFromMarkerRelationName($markable, $mark) . ', type: morphedByMany)';
            }
            $rows[] = [$markable, implode(PHP_EOL, $markableRelations)];
        }

        $rows[] = [$marker, implode(PHP_EOL, array_unique($markerRelations))];

        $this->table(['Model', 'Relations'], $rows, 'box');
    }
}
