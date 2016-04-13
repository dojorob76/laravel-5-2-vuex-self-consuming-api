<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\RequestMakeCommand;

class MakeFormRequest extends RequestMakeCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:form-request
    {name : The name of the class}
    {--folder= : Optional name of a sub-directory within "App/Requests"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a form request that is Dingo API compatible';

    /**
     * Get the stub file for the generator (Overrides MakeFormRequest method).
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path('Console/Commands/stubs/form-request.stub');
    }

    /**
     * Get the default namespace for the class (Overrides MakeFormRequest method).
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $basePath = $rootNamespace . '\Http\Requests';
        $subDirectory = $this->option('folder');

        $final = $basePath;

        if ($subDirectory != null) {
            $final .= '\\' . $subDirectory;
        }

        return $final;
    }

    /**
     * Create a new MakeFormRequest command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->parseName($this->getNameInput());

        if($this->files->exists($path = $this->getPath($name))){
            return $this->error($this->type . ' already exists!');
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        return $this->info($this->type . ' created successfully.');
    }
}
