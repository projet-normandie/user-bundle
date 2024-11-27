<?php

namespace ProjetNormandie\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ProjetNormandie\UserBundle\Controller\Security\ConfirmRegistration;
use ProjetNormandie\UserBundle\Controller\Security\ConfirmPassword;
use ProjetNormandie\UserBundle\Controller\Security\SendPasswordResetLink;

#[ApiResource(
    operations: [
        new Post(
            name: 'send-password-reset-link',
            uriTemplate: '/security/send-password-reset-link',
            controller: SendPasswordResetLink::class,
            openapi: new Model\Operation(
                summary: 'Send password reset link',
                description: 'Send as email with a link to change password',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'email' => 'exemple@exemple.com'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Operation is successful ?',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => ['type' => 'boolean']
                                    ]
                                ],
                                'example' => [
                                    'success' => true
                                ]
                            ]
                        ])
                    )
                ]
            )
        ),
        new Post(
            description: 'Password reset',
            name: 'confirm-password',
            uriTemplate: '/security/confirm-password',
            controller: ConfirmPassword::class,
            openapi: new Model\Operation(
                summary: 'Confirm password',
                description: 'Confirm password from token',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'token' => ['type' => 'string'],
                                    'password' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'token' => 'token',
                                'password' => 'password'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Operation is successful ?',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => ['type' => 'boolean']
                                    ]
                                ],
                                'example' => [
                                    'success' => true
                                ]
                            ]
                        ])
                    )
                ]
            )
        ),
        new Post(
            description: 'Confirm registration',
            name: 'confirm-registration',
            uriTemplate: '/security/confirm-registration',
            controller: ConfirmRegistration::class,
            openapi: new Model\Operation(
                summary: 'Confirm registration',
                description: 'Confirm registration from token',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'token' => ['type' => 'string'],
                                    'password' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'token' => 'token',
                                'password' => 'password'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Operation is successful ?',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => ['type' => 'boolean']
                                    ]
                                ],
                                'example' => [
                                    'success' => true
                                ]
                            ]
                        ])
                    )
                ]
            )
        ),
    ],
)]

class Security
{
}
