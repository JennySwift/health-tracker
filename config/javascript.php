<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View to Bind JavaScript Vars To
    |--------------------------------------------------------------------------
    |
    | Set this value to the name of the view (or partial) that
    | you want to prepend all JavaScript variables to.
    |
    */
   
    /**
     * @VP:
     * Why wouldn't my commented attempt work here? (See views/exercises.php.)
     * The only way I got it to work was to use a blade include.
     */
    'bind_js_vars_to_this_view' => 'templates.footer',
    // 'bind_js_vars_to_this_view' => base_path() . '/resources/views/footer.blade.php',

    /*
    |--------------------------------------------------------------------------
    | JavaScript Namespace
    |--------------------------------------------------------------------------
    |
    | By default, we'll add variables to the global window object. However,
    | it's recommended that you change this to some namespace - anything.
    | That way, you can access vars, like "SomeNamespace.someVariable."
    |
    */
    'js_namespace' => 'window'

];