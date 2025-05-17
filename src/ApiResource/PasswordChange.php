<?php

namespace ProjetNormandie\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ProjetNormandie\UserBundle\Controller\User\ChangePassword;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Post(
            uriTemplate: '/users/change-password',
            controller: ChangePassword::class,
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                summary: 'Change user password',
                description: 'Change the password after verifying the current one',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'currentPassword' => ['type' => 'string'],
                                    'newPassword' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'currentPassword' => 'current-password',
                                'newPassword' => 'new-password'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Password changed successfully',
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
                    ),
                    '400' => new Model\Response(
                        description: 'Invalid current password',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string']
                                    ]
                                ],
                                'example' => [
                                    'message' => 'Current password is invalid'
                                ]
                            ]
                        ])
                    )
                ]
            )
        ),
    ],
)]

class PasswordChange
{
}
