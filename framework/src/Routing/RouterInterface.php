<?php

namespace Gmo\Framework\Routing;

use Gmo\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
