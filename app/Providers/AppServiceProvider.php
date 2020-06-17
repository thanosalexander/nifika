<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::macro('labelRequired', function ($name, $value = null, $options = array()) {
            $requiredMark = ' <span class="required">*</span>';
            $labelHTMLString = \Form::label($name, $value, $options);
            
            $labelRequiredHTMLString = str_replace("</label>", $requiredMark.'</label>', $labelHTMLString);
            return $labelRequiredHTMLString;
        });
        /**
         * Extend Form::checkbox 
         * var label can be html label string or plain test
         * return the ckeckbox wrapped by given label
         */
        \Form::macro('checkboxLabeled', function ($name, $label = '', $value = 1, $checked = null, $options = array()){
            $options['id'] = !empty($options['id']) ? $options['id'] : $name . str_random(6);
            $checkboxHTMLString = \Form::checkbox($name, $value, $checked, $options);
            if(str_contains($label, "</label>")){
                //label is html string
                //enccapsulate checkbox into label and add 'for' attribute value
                $pattern = '/(<label.*) for=".*?"(.*?>)(.*)(<\/label>)/i';
                $checkboxLabeledHTMLString = preg_replace($pattern, '$1 for="' . $options['id'] . '"$2'. $checkboxHTMLString .'&nbsp;$3$4', $label);
            }else{
                //label is plain test
                $checkboxLabeledHTMLString = '<label for="' . $options['id'] . '">' . $checkboxHTMLString .'&nbsp;'. $label . '</label>';
            }
            return $checkboxLabeledHTMLString;
        });
        
        //Register morphMap for polymorphic tables
        $models = [
            \App\Language::class,
            \App\MenuItem::class,
            \App\Page::class,
            \App\PageImage::class,
            \App\Setting::class,
            \App\User::class,
        ];
        $map = [];
        foreach ($models as $modelClass){
            $map[lcfirst(array_last(explode('\\', $modelClass)))] = $modelClass;
        }
        Relation::morphMap($map);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
