<?php


namespace Models;


class MailMessage
{
    public
        /**
         * @var string
         * @validator required
         */
        $name,
        /**
         * @var string
         * @validator email|required
         */
        $email,
        /**
         * @var string
         * @validator required
         */
        $subject,
        /**
         * @var string
         * @validator required
         */
        $message;


}