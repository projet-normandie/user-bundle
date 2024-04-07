<?php

namespace ProjetNormandie\UserBundle\Controller\Avatar;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Flysystem\FilesystemException;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Service\AvatarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class Upload extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $em,
        private readonly AvatarManager $avatarManager
    ) {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws FilesystemException
     */
    public function __invoke(Request $request): Response
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
            return new JsonResponse(
                [
                    'message' => $this->translator->trans('avatar.extension_not_allowed', [], 'PnUser'),
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

        return new JsonResponse(['success' => true]);
    }
}
