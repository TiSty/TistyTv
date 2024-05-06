<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        $rit = array();
        if ($request->hasFile('imgCat')) {
            foreach ($request->file('imgCat') as $file) {
                // foreach ($request->file('img') as $file) {
                $name = time() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/files/', $name);
                // $data[] = $name;
            }
            $rit['data'] = true;
        } else {
            $rit['data'] = false;
        }
        return json_encode($rit);
    }

    public function uploadFiles(Request $request)
    {
        $rit = array();
        // Controllo se il file supera la dimensione massima
        foreach ($request->file('filesDaCaricare') as $file) {
            if ($file->getSize() > 2 * 1024 * 1024 * 1024) { // 2048 KB convertiti in byte
                $rit['data'] = false;
                $rit['message'] = 'Dimensione del file troppo grande. La dimensione massima consentita è 2048 KB.';
                return json_encode($rit);
            }
        }


        if ($request->hasFile('filesDaCaricare')) {
            foreach ($request->file('filesDaCaricare') as $file) {
                $name = time() . '.' . $file->getClientOriginalName();
                $file->move(public_path() . '/files/', $name);
            }
            $rit['data'] = true;
        } else {
            $rit['data'] = false;
        }
        return json_encode($rit);
    }
}
