<?php

namespace ProjetNormandie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvatarController extends Controller
{
    private $extensions = array(
        'image/png' => '.png',
        'image/jpeg' => '.jpg',
    );


    /**
     * @param Request     $request
     * @return Response
     * @throws \Exception
     */
    public function upload(Request $request)
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $file = $data['file'];
        $fp1 = fopen($file, 'r');
        $meta = stream_get_meta_data($fp1);

        $data = explode( ',', $file);

        if (!array_key_exists($meta['mediatype'], $this->extensions)) {
            return $this->getResponse(false, $this->get('translator')->trans('avatar.extension_not_allowed'));
        }

        $directory = $this->getParameter('projetnormandie_user.directory.avatar');
        $filename = $user->getId() . '#' . uniqid() . $this->extensions[$meta['mediatype']];

        $fp2 = fopen($directory . $filename, 'w');
        fwrite($fp2, base64_decode($data[1]));
        fclose($fp2);
        // Save avatar

        $user->setAvatar($filename);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->getResponse(true, $this->get('translator')->trans('avatar.success'));
    }

    /**
     * @param bool $success
     * @param null    $message
     * @return Response
     */
    private function getResponse(bool $success, $message = null) {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
        ]));
        return $response;
    }
}
