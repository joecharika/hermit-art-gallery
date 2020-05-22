<?php

namespace Controllers {

    use Exception;
    use Hyper\Annotations\action;
    use Hyper\Application\HyperApp;
    use Hyper\Controllers\Controller;
    use Hyper\Exception\HyperHttpException;
    use Hyper\Functions\Validator;
    use Hyper\Http\Request;
    use Models\{MailMessage, Project, ProjectImage};
    use PHPMailer\{PHPMailer\PHPMailer, PHPMailer\SMTP};
    use function Hyper\Database\db;

    /**
     * Class HomeController
     * @package Controllers
     */
    class HomeController extends Controller
    {
        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function index(Request $request): string
        {
            return $this->view(
                'home.index',
                null,
                null,
                [
                    'projects' => db(Project::class)->all()->lists([ProjectImage::class])->toList()
                ]);
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function aboutUs(Request $request): string
        {
            return $this->view('home.about',
                null,
                null,
                [
                    'projects' => count(db(Project::class)->all()->toList())
                ]
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         */
        public function contactUs(Request $request)
        {
            return $this->view(
                'home.contact',
                null,
                null,
                ['quote' => @$request->query->quote]
            );
        }

        /**
         * @action
         * @param Request $request
         * @return string
         * @throws HyperHttpException
         */
        public function postContactUs(Request $request)
        {
            $config = HyperApp::instance()->config->mail->smtp;

            if (!$config->configured)
                return $request->redirect($request->previousUrl, 'Message sent skipped');

            $data = $request->bind(new MailMessage);
            $validate = Validator::validate($data);

            if ($validate->valid) {
                $mail = new PHPMailer(true);

                try {
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;

                    $mail->isSMTP();
                    $mail->Host = $config->host;                    // Set the SMTP server to send through
                    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                    $mail->Username = $config->username;
                    $mail->Password = $config->password;                               // SMTP password
                    $mail->SMTPSecure = $config->secure;
                    $mail->Port = $config->port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    $mail->setFrom($data->email, $data->name);
                    $mail->addReplyTo($data->email, $data->name);
                    foreach (@$config->recipients ?? [] as $r){
                        $mail->addAddress($r, $r);
                    }

                    $mail->Subject = "Contact from Website - $data->subject";
                    $mail->Body = $data->message;

                    $mail->send();

                    return $request->redirect(
                        '/home/contact-us',
                        'Message sent'
                    );

                } catch (Exception $e) {
                    throw new HyperHttpException("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }
            } else return $request->redirect(
                $request->previousUrl,
                implode('<br>', $validate->errors)
            );
        }

    }
}
