<?php

namespace MatthiasWilbrink\FeatureToggle\Commands;

use Illuminate\Console\Command;
use MatthiasWilbrink\FeatureToggle\Facades\Feature;

class ListFeaturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all known features';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->table(['id', 'name', 'state', 'created_at', 'updated_at'], Feature::allArray());
    }
}