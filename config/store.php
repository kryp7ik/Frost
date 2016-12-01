<?php

/**
 * DO NOT CHANGE BOTTLE PRICES IN THIS FILE!
 */
return [

    'socket_url' => '127.0.0.1:3000',

    'tax' => 0.06,

    'preferred_discount' => 1,

    'manager_pin' => 133769,

    'stores' => [
        1 => 'Hudsonville',
        2 => 'Wyoming',
        3 => 'Coopersville'
    ],

    'bottle_prices' => [
        '10' => 4.99,
        '30' => 11.99,
        '50' => 18.99,
        '100' => 34.99
    ],

    'bottle_sizes' => [
        10 => '10ml',
        30 => '30ml',
        50 => '50ml',
        100 => '100ml',
    ],

    'menthol_levels' => [
        0 => 'None',
        1 => 'Light',
        2 => 'Medium',
        3 => 'Heavy',
        4 => 'Super',
    ],

    'vg_levels' => [
        40 => '40%',
        60 => '60%',
        100 => 'MAX',
    ],

    'nicotine_levels' => [
        0 => '0mg',
        1 => '1mg',
        2 => '2mg',
        3 => '3mg',
        4 => '4mg',
        5 => '5mg',
        6 => '6mg',
        7 => '7mg',
        8 => '8mg',
        9 => '9mg',
        10 => '10mg',
        11 => '11mg',
        12 => '12mg',
        13 => '13mg',
        14 => '14mg',
        15 => '15mg',
        16 => '16mg',
        17 => '17mg',
        18 => '18mg',
        19 => '19mg',
        20 => '20mg',
        21 => '21mg',
        22 => '22mg',
        23 => '23mg',
        24 => '24mg',
        25 => '25mg',
        26 => '26mg',
        27 => '27mg',
        28 => '28mg',
        29 => '29mg',
        30 => '30mg',
    ],

    'product_categories' => [
        'accessory' => 'Accessory',
        'battery' => 'Battery',
        'tank' => 'Tank',
        'rda' => 'RDA',
        'regulated_mod' => 'Regulated Mod',
        'mechanical_mod' => 'Mechanical Mod',
        'liquid' => 'Liquid',
        'coil' => 'Coil',
        'drip_tip' => 'Drip Tip',
        'beverage' => 'Beverage',
    ],

    'announcement_types' => [
        'default' => [
            'icon' => 'fa fa-comment fa-2x',
            'color' => ''
        ],
        'alert' => [
            'icon' => 'text-danger fa fa-warning fa-2x',
            'color' => 'text-danger'
        ],
        'event' => [
            'icon' => 'text-success fa fa-calendar fa-2x',
            'color' => 'text-success'
        ],
        'info' => [
            'icon' => 'text-info fa fa-info-circle fa-2x',
            'color' => 'text-info'
        ],
        'bullshit' => [
            'icon' => 'text-warning fa fa-beer fa-2x',
            'color' => 'text-warning'
        ]

    ],

    'colors' => [
        1 => '#626567',
        2 => '#FF0000',
        3 => '#B7950B',
        4 => '#239B56',
        5 => '#008000',
        6 => '#00FFFF',
        7 => '#008080',
        8 => '#FF00FF',
        9 => '#808000',
        10 => '#800080',
        11 => '#E9967A',
        12 => '#EB984E',
        13 => '#3498DB',
    ],

];