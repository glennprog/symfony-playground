<?php

namespace GM\QuestionAnswersBundle\Service;

class AppAdminEmail
{
    // ...

    private $adminEmail;

    public function __construct($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }
}
