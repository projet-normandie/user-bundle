<?php

namespace ProjetNormandie\UserBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AvatarController extends AbstractController
{
    private TranslatorInterface $translator;
    private FilesystemOperator $pnUserStorage;
    private EntityManagerInterface $em;

    private array $extensions = array(
        'image/png' => '.png',
        'image/jpeg' => '.jpg',
    );

    private array $extensions2 = array(
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

        if (!array_key_exists($meta['mediatype'], $this->extensions)) {
            return $this->getResponse(false, $this->translator->trans('avatar.extension_not_allowed'));
        }

        $filename = 'user' . DIRECTORY_SEPARATOR . $user->getId() . '_' . uniqid() . $this->extensions[$meta['mediatype']];

        $this->pnUserStorage->write($filename, base64_decode($data[1]));

        // Save avatar
        $user->setAvatar($filename);

        $this->em->flush();

        return $this->getResponse(true, $this->translator->trans('avatar.success'));
    }

    /**
     * @param User $user
     * @return void
     * @throws FilesystemException
     */
    public function download(User $user): Response
    {
        $stream = $this->pnUserStorage->readStream($user->getAvatar());
        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            exit();
        }, 200, ['Content-Type' => $this->getContentType($user->getAvatar())]);

    }

    /**
     * @param bool        $success
     * @param string|null $message
     * @return Response
     */
    private function getResponse(bool $success, string $message = null): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
        ]));
        return $response;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getContentType(string $file): string
    {
        $infos = pathinfo($file);
        return $this->extensions2[$infos['extension']] ?? 'image/png';
    }
}
