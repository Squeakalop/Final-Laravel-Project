<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use Illuminate\Http\Request;
use Session;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = \App\Models\ItemModel::all()->sortBy('title');
        return view('items.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|max:100|unique:items,title',
            'description' => 'required|max:500',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'sku' => 'required|max:50|unique:items,sku',
            'picture' => 'required|file'
        ];

        $this->validate($request, $rules);

        $item = new ItemModel;
        $item->category_id = $request->category_id;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Save original
            $file->move(public_path('images'), $filename);
            $item->picture = $filename;

            // Create tn_ and lrg_
            $this->makeThumbnails(public_path("images/$filename"), $filename);
        }

        $item->save();

        Session::flash('success', 'A new item has been created');
        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = ItemModel::find($id);

        if(!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }

        return view('items.show')->with('item', $item);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = ItemModel::find($id);

        if(!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }

        return view('items.edit')->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = ItemModel::find($id);

        if(!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }

        $rules = [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|max:100|unique:items,title,' . $id,
            'description' => 'required|max:500',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'sku' => 'required|max:50|unique:items,sku,' . $id,
            'picture' => 'nullable|file'
        ];

        $this->validate($request, $rules);

        $item->category_id = $request->category_id;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        if ($request->hasFile('picture')) {

            // Delete old versions
            if (!empty($item->picture)) {
                $imgDir = public_path('images');
                @unlink($imgDir . '/'.$item->picture);
                @unlink($imgDir . '/tn_'.$item->picture);
                @unlink($imgDir . '/lrg_'.$item->picture);
            }

            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Save original
            $file->move(public_path('images'), $filename);
            $item->picture = $filename;

            // Create tn_ and lrg_
            $this->makeThumbnails(public_path("images/$filename"), $filename);
        }

        $item->save();

        Session::flash('success', 'The item has been updated');
        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = ItemModel::find($id);

        if (!$item) {
            Session::flash('error', 'No item found');
        } else {
            if (!empty($item->picture)) {
                $imgDir = public_path('images');
                @unlink($imgDir . '/'.$item->picture);
                @unlink($imgDir . '/tn_'.$item->picture);
                @unlink($imgDir . '/lrg_'.$item->picture);
            }

            $item->delete();
            Session::flash('success', 'Item deleted');
        }

        return redirect()->route('items.index');
    }

    /**
     * Create tn_ and lrg_ thumbnails using Intervention Image if available,
     * otherwise fall back to native GD functions.
     */
    private function makeThumbnails(string $srcPath, string $filename)
    {
        // Prefer Intervention if installed
        if (class_exists(\Intervention\Image\Facades\Image::class)) {
            \Intervention\Image\Facades\Image::make($srcPath)
                ->fit(100, 100)
                ->save(public_path('images/tn_' . $filename));

            \Intervention\Image\Facades\Image::make($srcPath)
                ->fit(300, 300)
                ->save(public_path('images/lrg_' . $filename));

            return;
        }

        // Fallback: use GD (ensure functions exist)
        if (!function_exists('getimagesize')) {
            // Can't proceed with resizing — neither Intervention nor GD available.
            // Log or set a session message if you prefer.
            return;
        }

        $info = @getimagesize($srcPath);
        if (!$info) {
            return;
        }

        [$width, $height, $type] = $info;

        switch ($type) {
            case IMAGETYPE_JPEG:
                if (!function_exists('imagecreatefromjpeg')) {
                    return; // GD jpeg support not available
                }
                $srcImg = @imagecreatefromjpeg($srcPath);
                break;
            case IMAGETYPE_PNG:
                if (!function_exists('imagecreatefrompng')) {
                    return; // GD png support not available
                }
                $srcImg = @imagecreatefrompng($srcPath);
                break;
            case IMAGETYPE_GIF:
                if (!function_exists('imagecreatefromgif')) {
                    return; // GD gif support not available
                }
                $srcImg = @imagecreatefromgif($srcPath);
                break;
            case IMAGETYPE_WEBP:
                if (!function_exists('imagecreatefromwebp')) {
                    return; // GD webp support not available
                }
                $srcImg = @imagecreatefromwebp($srcPath);
                break;
            default:
                // Unsupported type
                return;
        }

        if (!$srcImg) {
            return;
        }

        $this->gdFitAndSave($srcImg, $width, $height, 100, 100, public_path('images/tn_' . $filename), $type);
        $this->gdFitAndSave($srcImg, $width, $height, 300, 300, public_path('images/lrg_' . $filename), $type);

        imagedestroy($srcImg);
    }

    /**
     * Resize + center-crop (fit) using GD and save to $destPath.
     */
    private function gdFitAndSave($srcImg, int $srcW, int $srcH, int $dstW, int $dstH, string $destPath, int $imgType)
    {
        // determine crop area on source to maintain aspect ratio then scale to destination
        $srcRatio = $srcW / $srcH;
        $dstRatio = $dstW / $dstH;

        if ($srcRatio > $dstRatio) {
            // source is wider — crop left/right
            $cropH = $srcH;
            $cropW = (int) round($cropH * $dstRatio);
        } else {
            // source is taller — crop top/bottom
            $cropW = $srcW;
            $cropH = (int) round($cropW / $dstRatio);
        }

        $srcX = (int) round(($srcW - $cropW) / 2);
        $srcY = (int) round(($srcH - $cropH) / 2);

        $dstImg = imagecreatetruecolor($dstW, $dstH);

        // preserve transparency for PNG and GIF
        if ($imgType === IMAGETYPE_PNG) {
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
            $transparent = imagecolorallocatealpha($dstImg, 0, 0, 0, 127);
            imagefilledrectangle($dstImg, 0, 0, $dstW, $dstH, $transparent);
        } elseif ($imgType === IMAGETYPE_GIF) {
            $trIndex = imagecolortransparent($srcImg);
            if ($trIndex >= 0) {
                $trColor = imagecolorsforindex($srcImg, $trIndex);
                $trIndexNew = imagecolorallocate($dstImg, $trColor['red'], $trColor['green'], $trColor['blue']);
                imagefill($dstImg, 0, 0, $trIndexNew);
                imagecolortransparent($dstImg, $trIndexNew);
            }
        }

        imagecopyresampled(
            $dstImg,
            $srcImg,
            0, 0,
            $srcX, $srcY,
            $dstW, $dstH,
            $cropW, $cropH
        );

        // Save according to original type (use appropriate quality)
        if ($imgType === IMAGETYPE_JPEG) {
            // ensure destination dir exists
            @mkdir(dirname($destPath), 0755, true);
            imagejpeg($dstImg, $destPath, 90);
        } elseif ($imgType === IMAGETYPE_PNG) {
            @mkdir(dirname($destPath), 0755, true);
            // PNG quality: 0 (no compression) to 9
            imagepng($dstImg, $destPath, 6);
        } elseif ($imgType === IMAGETYPE_GIF) {
            @mkdir(dirname($destPath), 0755, true);
            imagegif($dstImg, $destPath);
        } elseif ($imgType === IMAGETYPE_WEBP) {
            @mkdir(dirname($destPath), 0755, true);
            if (function_exists('imagewebp')) {
                imagewebp($dstImg, $destPath, 80);
            } else {
                // fallback to png if webp not supported for saving
                imagepng($dstImg, $destPath, 6);
            }
        }

        imagedestroy($dstImg);
    }
}
