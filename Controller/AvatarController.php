<?php

namespace ProjetNormandie\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Exception;
use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AvatarController extends AbstractController
{
    private TranslatorInterface $translator;
    private string $pnUserAvatarDirectory;
    private EntityManager $em;

    private array $extensions = array(
        'image/png' => '.png',
        'image/jpeg' => '.jpg',
    );

    public function __construct(TranslatorInterface $translator, string $pnUserAvatarDirectory, EntityManager $em)
    {
        $this->translator = $translator;
        $this->pnUserAvatarDirectory = $pnUserAvatarDirectory;
        $this->em = $em;
    }

    /**
     * @param Request     $request
     * @return Response
     * @throws Exception
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

        if (!array_key_exists($meta['mediatype'], $this->extensions)) {
            return $this->getResponse(false, $this->translator->trans('avatar.extension_not_allowed'));
        }

        $filename = $user->getId() . '_' . uniqid() . $this->extensions[$meta['mediatype']];

        $fp2 = fopen($this->pnUserAvatarDirectory . '/' . $filename, 'w');
        fwrite($fp2, base64_decode($data[1]));
        fclose($fp2);

        // Save avatar
        $user->setAvatar($filename);

        $this->em->flush();

        return $this->getResponse(true, $this->translator->trans('avatar.success'));
    }

    /**
     * @param bool $success
     * @param null    $message
     * @return Response
     */
    private function getResponse(bool $success, $message = null): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
        ]));
        return $response;
    }
}
