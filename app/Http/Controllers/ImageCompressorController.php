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
    public function showResizerForm()
    {
        $files = [];
        return view('compressor.resizer', compact('files'));
    }
    public function showConverterForm()
    {
        $files = [];
        return view('compressor.converter', compact('files'));
    }

    public function resize(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
        ]);

        $manager = new ImageManager(new Driver());
        $resizedUrls = [];

        $dir = storage_path('app/public/resized');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($request->file('images') as $file) {

            $originalName = $file->getClientOriginalName();   // full original name
            $extension = strtolower($file->getClientOriginalExtension());
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

            $finalName = $nameWithoutExt . '.' . $extension;
            $fullPath = $dir . '/' . $finalName;

            // read + resize
            $image = $manager->read($file->getPathname());
            $image->resize($request->width, $request->height);

            // encode based on original extension
            switch ($extension) {
                case 'png':
                    $image->toPng()->save($fullPath);
                    break;

                case 'jpg':
                case 'jpeg':
                default:
                    $image->toJpeg(90)->save($fullPath);
                    break;
            }

            $resizedUrls[] = [
                'url' => asset('/public/storage/resized/' . $finalName),
                'original' => $originalName
            ];
        }

        return back()
            ->with('success', 'Images resized successfully!')
            ->with('urls', $resizedUrls);
    }

    public function compress(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'mimes:jpeg,jpg,png,webp',
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

    public function convert(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|max:5120',
            'target_format' => 'required|in:webp,png,jpg',
        ]);

        try {
            // Try using Imagick first for better compatibility
            $manager = new ImageManager(new Driver());
            $convertedUrls = [];
            $format = $request->target_format;

            foreach ($request->file('images') as $file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = time() . '_' . $filename . '.' . $format;
                $path = 'converted/' . $newName;

                $image = $manager->read($file);

                $encoded = match ($format) {
                    'webp' => $image->toWebp(quality: 80),
                    'png'  => $image->toPng(),
                    'jpg'  => $image->toJpeg(quality: 85),
                };

                Storage::disk('public')->put($path, (string) $encoded);

                // Return just the URL string to match your existing Blade @foreach
                $convertedUrls[] = Storage::url($path);
            }

            return back()->with('success', 'Converted successfully!')->with('urls', $convertedUrls);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Server Error: ' . $e->getMessage()]);
        }
    }
}
