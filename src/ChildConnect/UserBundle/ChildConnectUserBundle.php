<?php

namespace ChildConnect\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChildConnectUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
?>