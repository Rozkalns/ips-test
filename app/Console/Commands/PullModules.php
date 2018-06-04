<?php

namespace App\Console\Commands;

use App\Http\Helpers\InfusionsoftHelper;
use App\Module;
use Illuminate\Console\Command;

class PullModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infusionsoft:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull and store all tags in a database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $infusionsoft = new InfusionsoftHelper();

        if (Module::count()) {
            \DB::statement("SET foreign_key_checks=0");
            Module::truncate();
            \DB::statement("SET foreign_key_checks=1");
        }

        foreach ($infusionsoft->getAllTags()->toArray() as $tag) {
            $t = $tag->toArray();
            preg_match('/[A-Z]{3}/', $t['name'], $course_key);
            preg_match('/[A-Z]{3}.*\d/', $t['name'], $name);

            $id = $t['id'];
            $course_key = strtolower($course_key[0]);
            $name = $name[0];

            (new Module(compact('id', 'course_key', 'name')))->save();
        }
    }
}
