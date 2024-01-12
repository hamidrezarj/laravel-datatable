<?php

namespace HamidRrj\LaravelDatatable\Commands;

use Illuminate\Console\Command;

class LaravelDatatableCommand extends Command
{
    public $signature = 'laravel-datatable';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
