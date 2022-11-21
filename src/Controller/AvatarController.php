<?php

namespace ProjetNormandie\UserBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AvatarController extends AbstractController
{
    private TranslatorInterface $translator;
    private FilesystemOperator $pnUserStorage;
    private EntityManagerInterface $em;

    private string $prefix = 'user/';

    private array $extensions = array(
        'png' => 'image/png',
        'jpg' => 'image/jpeg'
    );

    public function __construct(TranslatorInterface $translator, FilesystemOperator $pnUserStorage, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->pnUserStorage = $pnUserStorage;
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws FilesystemException
     */
    public function upload(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $file = $data['file'];
        $fp1 = fopen($file, 'r');
        $meta = stream_get_meta_data($fp1);

        $data = explode(',', $file);

        if (!in_array($meta['mediatype'], $this->extensions)) {
            return new JsonResponse([
                'message' => $this->translator->trans('avatar.extension_not_allowed'),
            ],
            400
            );
        }

        // Save avatar
        $mimeTypes = array_flip($this->extensions);
        $filename = $user->getId() . '_' . uniqid() . '.' . $mimeTypes[$meta['mediatype']];
        $user->setAvatar($filename);

        $this->pnUserStorage->write($this->prefix . $filename, base64_decode($data[1]));

        // Save avatar
        $user->setAvatar($filename);

        $this->em->flush();

        return new JsonResponse([
            'message' => $this->translator->trans('avatar.success'),
        ], 200);
    }

    /**
     * @param User $user
     * @return void
     * @throws FilesystemException
     */
    public function download(User $user): Response
    {
        $stream = $this->pnUserStorage->readStream($this->prefix . $user->getAvatar());
        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            exit();
        }, 200, ['Content-Type' => $this->getContentType($user->getAvatar())]);

    }

    /**
     * @param string $file
     * @return string
     */
    private function getContentType(string $file): string
    {
        $infos = pathinfo($file);
        return $this->extensions[$infos['extension']] ?? 'image/png';
    }
}
