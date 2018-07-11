<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

return function($request, $response) {
    /**
     * Add Template Builder
     */
    $this->package('cradlephp/cradle-api')->addMethod('template', function (
        $type,
        $file,
        array $data = [],
        $partials = [],
        $customFileRoot  = null,
        $customPartialsRoot = null
    ) {
        // get the root directory
        $root =  $customFileRoot;
        $partialRoot = $customPartialsRoot;

        // get the root directory
        $type = ucwords($type);
        $originalRoot =  sprintf('%s/%s/template/', __DIR__, $type);

        if (!$customFileRoot) {
            $root = $originalRoot;
        }

        if (!$customPartialsRoot) {
            $partialRoot =  $originalRoot;
        }

        // check for partials
        if (!is_array($partials)) {
            $partials = [$partials];
        }

        $paths = [];

        foreach ($partials as $partial) {
            //Sample: product_comment => product/_comment
            //Sample: flash => _flash
            $path = str_replace('_', '/', $partial);
            $last = strrpos($path, '/');

            if($last !== false) {
                $path = substr_replace($path, '/_', $last, 1);
            }

            $path = $path . '.html';

            if (strpos($path, '_') === false) {
                $path = '_' . $path;
            }

            $paths[$partial] = $partialRoot . $path;
        }

        $file = $root . $file . '.html';

        //render
        return cradle('global')->template($file, $data, $paths);
    });
};
