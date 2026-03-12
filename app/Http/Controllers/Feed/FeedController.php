<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Factories\FeedFactory;

class FeedController extends Controller
{
    public function products($type)
    {
        $generator = FeedFactory::make($type);

        $data = $generator->generate();

        if ($type === 'xml')

            return response(
                $data,
                200
            )->header(
                'Content-Type',
                'application/xml'
            );

        return response()->json($data);

    }
}
