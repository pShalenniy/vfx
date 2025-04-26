<?php

declare(strict_types=1);

return [
    'nested' => [
        [
            'expected' => 'Hello.The user Foo Bar, email: test@example.com, phone number: +38066111111. Please handle the request.',
            'given' => 'Hello.[loop:users]The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number].[/loop:users] Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'user:email' => 'test@example.com',
                                'user:phone_number' => '+38066111111',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 2,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello.[loop:users]The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number]. Please handle the request.',
            'given' => 'Hello.[loop:users]The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number]. Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'user:email' => 'test@example.com',
                                'user:phone_number' => '+38066111111',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello. [user:first_name]The user Bar, email: test@example.com. Please handle the request.',
            'given' => 'Hello. [user:first_name][loop:users]The user [user:last_name], email: [user:email][/loop:users]. Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'user:email' => 'test@example.com',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello. Foo The user Bar, email: test@example.com. Please handle the request.',
            'given' => 'Hello. [user:first_name] [loop:users]The user [user:last_name], email: [user:email][/loop:users]. Please handle the request.',
            'data' => [
                'user:first_name' => 'Foo',
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:last_name' => 'Bar',
                                'user:email' => 'test@example.com',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello.[loop:users]The user Boo Baz.[loop:users] Please handle the request.',
            'given' => 'Hello.[loop:users]The user Boo Baz.[loop:users] Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello.[loop:users]The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number]. Please handle the request.',
            'given' => 'Hello.[loop:users]The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number]. Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello.The user Foo Bar. Subscription seats: 2. Please handle the request.',
            'given' => 'Hello.[loop:users]The user [user:first_name] [user:last_name].[loop:subscriptions] Subscription seats: [subscription:seats]. [/loop:subscriptions][/loop:users]Please handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'user:email' => 'test@example.com',
                                'user:phone_number' => '+38066111111',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 2,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello.The user Foo Bar, email: test@example.com, phone number: +38066111111. Please handle the request.',
            'given' => 'Hello.The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number]. Please handle the request.',
            'data' => [
                'user:first_name' => 'Foo',
                'user:last_name' => 'Bar',
                'user:email' => 'test@example.com',
                'user:phone_number' => '+38066111111',
            ],
        ],
    ],
    'multiple_nested' => [
        [
            'expected' => 'Hello. The user Foo Bar. Subscription seats: 2.<br> The user Bar Baz. Subscription seats: 4.Handle the request.',
            'given' => 'Hello.[loop:users] The user [user:first_name] [user:last_name].[loop:subscriptions] Subscription seats: [subscription:seats].[/loop:subscriptions][/loop:users]Handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 2,
                                    ],
                                ],
                            ],
                            [
                                'user:first_name' => 'Bar',
                                'user:last_name' => 'Baz',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 4,
                                    ],
                                ],
                            ],
                        ],
                        'separator' => '<br>',
                    ],
                ],
            ],
        ],
        [
            'expected' => 'Hello. The user Foo Bar.<br> The user Bar Baz.Handle the request.',
            'given' => 'Hello.[loop:users] The user [user:first_name] [user:last_name].[/loop:users]Handle the request.',
            'data' => [
                'loops' => [
                    'users' => [
                        'data' => [
                            [
                                'user:first_name' => 'Foo',
                                'user:last_name' => 'Bar',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 2,
                                    ],
                                ],
                            ],
                            [
                                'user:first_name' => 'Bar',
                                'user:last_name' => 'Baz',
                                'loops' => [
                                    'subscriptions' => [
                                        'subscription:seats' => 4,
                                    ],
                                ],
                            ],
                        ],
                        'separator' => '<br>',
                    ],
                ],
            ],
        ],
    ],
];
