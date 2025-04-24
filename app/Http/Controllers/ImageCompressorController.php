<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompressedImage;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressorController extends Controller
{
    public function showForm()
    {
        $files = [];
        return view('compressor.form', compact('files'));
    }

    public function compress(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'mimes:jpeg,jpg,png,webp|max:10240',
            'target_size' => 'required|integer|min:10',
        ]);

        $targetSizeKB = $request->input('target_size');
        $savedImages = [];

        $dir = storage_path('app/public/compressed');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $oldImages = CompressedImage::all();
        foreach ($oldImages as $img) {
            Storage::delete('public/compressed/' . $img->compressed_filename);
        }
        CompressedImage::truncate();

        $manager = new ImageManager(new Driver());

        foreach ($request->file('images') as $image) {
            $originalName = $image->getClientOriginalName();
            $compressedName = uniqid() . '.' . $image->getClientOriginalExtension();
            $compressedPath = storage_path('app/public/compressed/' . $compressedName);

            $img = $manager->read($image->getPathname());

            $quality = 90;
            do {
                $img->toJpeg(quality: $quality)->save($compressedPath);
                $currentSizeKB = filesize($compressedPath) / 1024;
                $quality -= 5;
            } while ($currentSizeKB > $targetSizeKB && $quality > 10);

            $result = CompressedImage::create([
                'original_filename' => $originalName,
                'compressed_filename' => $compressedName,
                'target_size_kb' => $targetSizeKB,
            ]);

            $savedImages[] = [
                'url' => asset('storage/app/public/compressed/' . $compressedName),
                'original' => $originalName
            ];
        }

        return response()->json([
            'files' => $savedImages
        ]);
    }
}
