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
     * This works for my exercises view, but I want access to the variables in my other views, too.
     * But when I replace 'exercises' with anything else, I get an error.
     * I tried replacing it with 'footer', and base_path() . '/resources/views/footer.php', and neither worked.
     * Please tell me what I need to do in order to access my variables in my other views.
     * I was testing it out with views/exercises.php, where I have used an include in an attempt to get this to work.
     */
    'bind_js_vars_to_this_view' => 'footer',
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