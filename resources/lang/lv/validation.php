<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute ir jābūt apstiprinātam.',
    'active_url'           => ':attribute nederīgs URL.',
    'after'                => ':attribute ir jābūt datumam pēc :date.',
    'after_or_equal'       => ':attribute ir jābūt datumam vienādam vai pēc :date.',
    'alpha'                => ':attribute drīkst saturēt tikai burtus.',
    'alpha_dash'           => ':attribute drīkst saturēt tikai burtus, ciparus un domuzīmes.',
    'alpha_num'            => ':attribute drīkst saturēt tikai burtus un ciparus.',
    'array'                => ':attribute ir jābūt ir masīvam.',
    'before'               => ':attribute ir jābūt datumam pirms :date.',
    'before_or_equal'      => ':attribute ir jābūt vienādam vai pirms :date.',
    'between'              => [
        'numeric' => ':attribute ir jābūt starp :min un :max.',
        'file'    => ':attribute ir jābūt starp :min un :max kilobaitiem.',
        'string'  => ':attribute ir jābūt starp :min un :max rakstzīmēm.',
        'array'   => ':attribute ir jābūt starp :min un :max vērtībām.',
    ],
    'boolean'              => ':attribute laukam ir jābūt vai nu true vai false.',
    'confirmed'            => ':attribute apstiprinājums nesakrīt.',
    'date'                 => ':attribute nav derīgs datums.',
    'date_format'          => ':attribute neatbilst formātam :format.',
    'different'            => ':attribute un :other ir jābūt atšķirīgiem.',
    'digits'               => ':attribute ir jābūt :digits digits.',
    'digits_between'       => ':attribute ir jābūt starp :min un :max cipariem.',
    'dimensions'           => ':attribute ir nederīgs attēla izmērs.',
    'distinct'             => ':attribute laukam ir dublikāta vērtība.',
    'email'                => ':attribute ir jābūt derīgai e-pasta adresei.',
    'exists'               => 'izvēlētais :attribute ir nederīgs.',
    'file'                 => ':attribute ir jābūt failam.',
    'filled'               => ':attribute laukam ir jābūt vērtībai.',
    'image'                => ':attribute ir jābūt bildei.',
    'in'                   => 'izvēlētais :attribute ir nederīgs.',
    'invalid'              => ':attribute ir nederīgs.',
    'in_array'             => ':attribute lauks neeksistē iekš :other.',
    'integer'              => ':attribute ir jābūt veselam skaitlim.',
    'ip'                   => ':attribute ir jābūt derīgai IP adresei.',
    'ipv4'                 => ':attribute ir jābūt derīgai IPv4 adresei.',
    'ipv6'                 => ':attribute ir jābūt derīgai IPv6 adresei.',
    'json'                 => ':attribute ir jābūt derīgam JSON stringam.',
    'max'                  => [
        'numeric' => ':attribute nedrīkst būt lielāks par :max.',
        'file'    => ':attribute nedrīkst būt lielāks par :max kilobaitiem.',
        'string'  => ':attribute nedrīkst būt lielāks par :max rakstzīmēm.',
        'array'   => ':attribute nedrīkst saturēt vairāk kā :max vērtības.',
    ],
    'mimes'                => ':attribute ir jābūt failam type: :values.',
    'mimetypes'            => ':attribute ir jābūt failam type: :values.',
    'min'                  => [
        'numeric' => ':attribute ir jābūt vismaz :min.',
        'file'    => ':attribute ir jābūt vismaz :min kilobaitiem.',
        'string'  => ':attribute ir jābūt vismaz :min rakstzīmēm.',
        'array'   => ':attribute ir jābūt vismaz :min vērtībām.',
    ],
    'not_in'               => 'izvēlētais :attribute ir nederīgs.',
    'numeric'              => ':attribute jābūt ir numuram.',
    'present'              => ':attribute laukam jābūt tagadnē.',
    'regex'                => ':attribute formāts ir nederīgs.',
    'required'             => ':attribute laukam ir jābūt aizpildītam.',
    'required_if'          => ':attribute laukam ir jābūt aizpildītam kamēr :other ir :value.',
    'required_unless'      => ':attribute laukam ir jābūt aizpildītam kamēr :other nav :values.',
    'required_with'        => ':attribute laukam ir jābūt aizpildītam kamēr :values ir aizpildīts.',
    'required_with_all'    => ':attribute laukam ir jābūt aizpildītam :values ir aizpildīti.',
    'required_without'     => ':attribute laukam ir jābūt aizpildītam :values nav aizpildīti.',
    'required_without_all' => ':attribute laukam ir jābūt aizpildītam kamēr neviens no :values nav aizpildīti.',
    'same'                 => ':attribute un :other ir jāsakrīt.',
    'size'                 => [
        'numeric' => ':attribute ir jābūt :size.',
        'file'    => ':attribute ir jābūt :size kilobaitiem.',
        'string'  => ':attribute ir jābūt :size rakstīmēm.',
        'array'   => ':attribute ir jāsatur :size vērtības.',
    ],
    'string'               => ':attribute ir jābūt stringam.',
    'timezone'             => ':attribute ir jābūt derīgai laika zonai.',
    'unique'               => ':attribute ir jau aizņemts.',
    'uploaded'             => ':attribute nav izdevies augšuplādēt.',
    'url'                  => ':attribute formāts ir nederīgs.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
];
