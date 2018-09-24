<?php

namespace LiveCMS\Commands;

use Illuminate\Console\Command;

class TemplateCommand extends Command
{
    protected $templateDir = __DIR__.'/../../templates';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livecms:template
                            {--f|force : Force to publish}
                            {--a|author : Act like a boss}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add / Update / Publish Default Template to be edited.';

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
        if ($this->option('author')) {
            $this->publishMixFile($this->templateDir);
            $this->publishViews($this->templateDir);
            return;
        }
        if (($force = $this->option('force')) && !$this->confirm('Are you sure to forcing reset templates?')) {
            return $this->alert('Action is canceled');
        }

        $this->publishTemplates($force);

        $this->publishViews();

    }

    protected function publishTemplates($force = false)
    {
        $templateDir = $this->templateDir;;
        $templatePath = config('livecms.template_path');
        foreach (scandir($templateDir) as $dir) {
            if (is_dir($templateDir.'/'.$dir) && $dir != '.' && $dir != '..') {
                if (!file_exists($target = $templatePath.'/'.$dir) || $force) {
                    $this->recursiveCopy($templateDir.'/'.$dir, $target);
                }
            }
        }
        $this->publishMixFile($templatePath);
    }

    protected function publishMixFile($dir)
    {
        $content = null;
        foreach (scandir($dir) as $temp) {
            if ($temp != '.' && $temp != '..') {
                if (is_dir($tempDir = $dir.'/'.$temp) && file_exists($tempDir.'/mix.js')) {
                    $content .= <<<JS
\r\n
var templateDir = '{$tempDir}';
eval(require('fs').readFileSync(templateDir+'/mix.js') + '');
JS;
                }
            }
        }
        $content .= <<<JS
\r\n
var Mix = require('laravel-mix');

Mix
.options({
    fileLoaderDirs: {
        fonts: 'vendor/livecms/fonts'
    }
})
.copyDirectory('public/vendor/livecms/fonts', '{$dir}/fonts');

JS;
        file_put_contents(base_path($fileName = 'livecms.webpack.config.js'), $content);
        $this->writeToMixConfig($fileName);
    }

    protected function writeToMixConfig($fileName)
    {
        $content = <<<JS
\r\n
// Livecms
eval(require('fs').readFileSync('./{$fileName}') + '');
\r\n
JS;
        $configLocation = base_path($name = 'webpack.mix.js');
        if (!file_exists($configLocation)) {
            throw new \Exception("file $name is not found.");
        }
        $file = file_get_contents($configLocation);
        if (strpos($file, $content) === false) {
            file_put_contents($configLocation, $content, FILE_APPEND);
        }
    }

    protected function publishViews($templatePath  = null)
    {
        $templatePath = $templatePath ?? config('livecms.template_path');
        $targetPath = config('livecms.view_path');
        foreach (scandir($templatePath) as $dir) {
            if ($dir != '.' && $dir != '..') {
                if (is_dir($tempDir = $templatePath.'/'.$dir.'/views')) {
                    $this->createRecursiveFileView($tempDir, $targetPath, $templatePath.'/'.$dir);
                }
            }
        }
    }

    protected function createRecursiveFileView($src, $dst, $sourceDir)
    {
        if ($dst) {
            $dir = opendir($src);
            @mkdir($dst, 0775, true);
            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src.'/'.$file) ) {
                        $this->createRecursiveFileView($src.'/'.$file, $dst.'/'.$file, $sourceDir);
                    } else {
                        if (!file_exists($target = $dst.'/'.$file)) {
                            $this->createFileView($src.'/'.$file, $target, $sourceDir);
                        }
                    }
                }
            }
            closedir($dir);
        }
    }

    public function createFileView($src, $dst, $sourceDir)
    {
        if (strpos($src, '.blade.php') !== false) {
            $source = str_replace('/', '.', ltrim(str_replace('.blade.php', '', str_replace($sourceDir, '', $src)), '/'));
            $content = <<<PHP
<?php
\$source = LC_CurrentTheme().'.{$source}';
\$targetView = 'livecms-templates::'.\$source; ?>
@extends(\$targetView)
PHP;
            file_put_contents($dst, $content);
        } 
    }

    protected function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0775, true);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src.'/'.$file) ) {
                    $this->recursiveCopy($src.'/'.$file, $dst.'/'.$file);
                } else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }
}
