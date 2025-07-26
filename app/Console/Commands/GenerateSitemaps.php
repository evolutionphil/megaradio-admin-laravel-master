<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gen:sitemaps {lang=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemaps';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lang = $this->argument('lang');

        try {
            (new SitemapService)->generatePagesSitemap($lang);

            (new SitemapService)->generateGenreSitemap($lang);

            (new SitemapService)->generateStationsSitemap($lang);

            (new SitemapService)->generateIndexSitemap($lang);

            $this->info('Sitemap generated for '.$lang);
        } catch (\Exception $exception) {
            $this->error('Sitemap generation failed: '.$exception->getMessage());
        }

        return 0;
    }
}
