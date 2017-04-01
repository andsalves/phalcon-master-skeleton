<?php

return array(
    'home' => array(
        'title' => 'Home',
        'path' => '',
        'icon_class' => 'fa-home'
    ),
    'two-level' => array(
        'title' => 'Two Level Menu',
        'path' => '',
        'icon_class' => 'fa-user',
        'subpages' => array(
            array(
                'title' => 'Some Route',
                'path' => 'app/path/to/route.html'
            ),
            array(
                'title' => 'List',
                'path' => '/list'
            )
        )
    ),
);