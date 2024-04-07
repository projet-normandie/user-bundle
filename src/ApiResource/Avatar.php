<?php

namespace ProjetNormandie\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ProjetNormandie\UserBundle\Controller\Avatar\Upload;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/users/upload-avatar',
            controller: Upload::class,
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                summary: 'Update avatar',
                description: 'Update avatar',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'object',
                                        'required' => true,
                                        'description' => ' Picture encoded in base64'
                                    ],
                                ]
                            ],
                            'example' => [
                                'file' => 'base64file',
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
                                        'message' => ['type' => 'string']
                                    ]
                                ],
                                'example' => [
                                    'message' => 'success'
                                ]
                            ]
                        ])
                    )
                ],
            )
        ),
    ],
)]

class Avatar
{
}
