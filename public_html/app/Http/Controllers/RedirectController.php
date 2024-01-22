<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function helpdesk()
    {
        $url = url('helpdesk');
        echo "<script>window.location.href='$url'</script>";
    }
}
