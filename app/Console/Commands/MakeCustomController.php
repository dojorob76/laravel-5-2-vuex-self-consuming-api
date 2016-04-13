<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand;

class MakeCustomController extends ControllerMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-controller
    {name : The name of the class}
    {custom : The directory for the controller ("Web", "Api", or "SubdomainName")}
    {--resource : Generate a resource controller class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Custom Controller at App\CustomDirectory\Controllers';

    /**
     * Get the stub file for the generator (overrides ControllerMakeCommand method).
     *
     * @return string
     */
    protected function getStub()
    {
        $arg = strtolower($this->argument('custom'));
        $custom = $arg != 'web' && $arg != 'api' ? 'subdomain' : $arg;
        $stubPath = 'Console/Commands/stubs/custom_controllers/';

        if ($this->option('resource')) {
            return app_path($stubPath . $custom . '-controller.stub');
        }

        return app_path($stubPath . $custom . '-controller.plain.stub');
    }

    /**
     * Get the default namespace for the class (overrides ControllerMakeCommand method).
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $directory = $this->getDirectory();

        if($directory == 'Api'){
            $namespace = $rootNamespace. '\\' . $directory . '\Controllers';
        }
        else{
            $namespace = $rootNamespace . '\Http\Controllers';

            if($directory != 'Http'){
                $namespace .= '\\' . $directory;
            }
        }

        return $namespace;
    }

    /**
     * Replace the namespace for the given stub (Extends GeneratorCommand method by adding DummyDirectory).
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            'DummyDirectory', $this->getDirectory(), $stub
        );

        $stub = str_replace(
            'DummyNamespace', $this->getNamespace($name), $stub
        );

        $stub = str_replace(
            'DummyRootNamespace', $this->laravel->getNamespace(), $stub
        );

        return $this;
    }

    /**
     * Get the directory that the Controller should be stored in from the custom argument and return it.
     *
     * @return string
     */
    private function getDirectory(){
        $custom = $this->argument('custom');
        $directory = $custom == 'Web' ? 'Http' : $custom;

        return $directory;
    }

    /**
     * Create a new MakeApiController command instance.
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
