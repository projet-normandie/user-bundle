<?php

namespace ProjetNormandie\UserBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Flysystem\FilesystemException;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Service\AvatarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AvatarController extends AbstractController
{
    private TranslatorInterface $translator;
    private EntityManagerInterface $em;
    private AvatarManager $avatarManager;


    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em, AvatarManager $avatarManager)
    {
        $this->translator = $translator;
        $this->em = $em;
        $this->avatarManager = $avatarManager;
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
        $mimeType = $meta['mediatype'];

        $data = explode(',', $file);

        if (!in_array($mimeType, $this->avatarManager->getAllowedMimeType())) {
            return new JsonResponse([
                'message' => $this->translator->trans('avatar.extension_not_allowed'),
            ],
            400
            );
        }

        // Set filename
        $filename = $user->getId() . '_' . uniqid() . '.' . $this->avatarManager->getExtension($mimeType);

        $this->avatarManager->write($filename, base64_decode($data[1]));

        // Save avatar
        $user->setAvatar($filename);
        $this->em->flush();

        return new JsonResponse([
            'message' => $this->translator->trans('avatar.success'),
        ], 200);
    }

    /**
     * @param User $user
     * @return StreamedResponse
     * @throws FilesystemException
     */
    public function download(User $user): StreamedResponse
    {
        return $this->avatarManager->read($user->getAvatar());
    }
}
