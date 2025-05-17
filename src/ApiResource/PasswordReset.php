<?php

namespace ProjetNormandie\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ProjetNormandie\UserBundle\Controller\ResetPassword\ConfirmPassword;
use ProjetNormandie\UserBundle\Controller\ResetPassword\SendPasswordResetLink;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Post(
            name: 'send-password-reset-link',
            uriTemplate: '/users/reset-password/send-link',
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
            uriTemplate: '/users/reset-password/confirm',
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
    ],
)]

class PasswordReset
{
}
