<?php

namespace App\Controllers;

use App\Libraries\Slug; // Import library

class Site extends BaseController
{
    public function generateMySlug()
    {
        $slug = new Slug(); // create an instance of Library

        $string = "Online Web Tutor Blog";

        echo $slug->slugify($string); // calling method
    }
}
