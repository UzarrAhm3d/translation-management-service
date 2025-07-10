<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;

class PopulateTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:populate {count=100000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database with test translations';

    public function __construct(
        private TranslationService $translationService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("Populating {$count} translations...");

        $locales = ['en', 'fr', 'es', 'de', 'it'];
        $contexts = ['mobile', 'desktop', 'web'];
        $translations = [];

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $translations[] = [
                'key' => 'key_' . $i,
                'locale' => $locales[array_rand($locales)],
                'content' => 'Translation content for key ' . $i,
                'tags' => [$contexts[array_rand($contexts)]],
            ];

            if (count($translations) >= 1000) {
                $this->translationService->bulkCreateTranslations($translations);
                $translations = [];
            }

            $bar->advance();
        }

        if (!empty($translations)) {
            $this->translationService->bulkCreateTranslations($translations);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Translations populated successfully!');

        return 0;
    }
}
