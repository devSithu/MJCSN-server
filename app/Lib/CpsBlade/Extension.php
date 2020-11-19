<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\CpsBlade;

use Blade;

/**
 * Description of Extension
 *
 * @author truong.nguyen
 */
class Extension
{

    public static function extendBlade()
    {
        static::registerDebugDirectives();

        Blade::directive('let', function ($expression) {
            return "<?php ($expression); ?>";
        });
        Blade::directive('php', function ($expression) {
            return "<?php $expression; ?>";
        });
        Blade::directive('token', function ($expression) {
            return "<?php echo csrf_field(); ?>";
        });
        Blade::directive('method', function ($expression) {
            return "<?php echo method_field($expression); ?>";
        });
        Blade::directive('errorIf', function ($expression) {
            return "<?php if(\$errors->has($expression)){ echo CpsForm::printErrorMessage(\$errors->first($expression)); } ?>";
        });
        Blade::directive('tooltip', function ($expression) {
            return '<div class="qb-tooltip-container">
                        <div class="tooltip fade top in qb-tooltip" role="tooltip">
                            <div class="tooltip-arrow"></div><div class="tooltip-inner">' . "<?php echo ($expression); ?>" . '</div>
                        </div>
                    </div>';
        });
        Blade::directive('customizeIf', function ($expression) {
            return "<?php echo App\Lib\CpsBlade\Directive::readCustomFileIf($expression); ?>";
        });

        Blade::directive('scope', function ($expression) {
            return "<?php echo csp_scope($expression); ?>";
        });
        Blade::directive('script', function ($expression) {
            return "<?php echo csp_script($expression); ?>";
        });
        Blade::directive('scriptIf', function ($expression) {
            return "<?php echo csp_script_if($expression); ?>";
        });
        Blade::directive('css', function ($expression) {
            return "<?php echo App\Lib\CpsBlade\Directive::css($expression); ?>";
        });
        Blade::directive('cssIf', function ($expression) {
            return "<?php echo App\Lib\CpsBlade\Directive::cssIf($expression); ?>";
        });

        Blade::directive('dataTable', function ($expression) {
            if ($expression) {
                return "<?php echo App\Lib\CpsBlade\Directive::dataTable(\$dataTable ?? null,$expression); ?>";
            }
            return "<?php echo App\Lib\CpsBlade\Directive::dataTable(\$dataTable); ?>";
        });
    }

    protected static function registerDebugDirectives()
    {
        Blade::directive('dd', function ($expression) {
            if (config('app.debug')) {
                return "<?php dd($expression); ?>";
            }
        });
        Blade::directive('log', function ($expression) {
            if (config('app.debug')) {
                return "<?php \Log::info($expression); ?>";
            }
        });
        Blade::directive('debug', function ($expression) {
            if (!config('app.debug')) {
                return "<?php if(false): ?>";
            }
        });
        Blade::directive('enddebug', function ($expression) {
            if (!config('app.debug')) {
                return '<?php endif; ?>';
            }
        });
        Blade::directive('dump', function ($param) {
            if (config('app.debug')) {
                return "<?php echo (new \App\Lib\CpsBlade\Dumper)->dump($param); ?>";
            }
        });
    }

}
