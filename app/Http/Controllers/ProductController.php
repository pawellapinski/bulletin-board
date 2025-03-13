<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of fzthe resource.
     */
    private const PER_PAGE = 5;
    private const IMAGE_DISK = 'public';
    private const IMAGE_PATH = 'images';

    public function index(): View
    {
        $products = Product::latest()->paginate(self::PER_PAGE);
        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * self::PER_PAGE);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::create($request->validated());

                if ($request->hasFile('images')) {
                    $this->handleImages($product, $request->file('images'));
                }
            });

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($request->validated());

            if ($request->hasFile('images')) {
                foreach($product->images as $image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                }

                $this->handleImages($product, $request->file('images'));
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            $this->deleteProductImages($product);
            $product->delete();
        });

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
    private function handleImages(Product $product, array $images): void
    {
        foreach($images as $image) {
            $path = $image->store(self::IMAGE_PATH, self::IMAGE_DISK);
            $product->images()->create(['image' => $path]);
        }
    }
    private function deleteProductImages(Product $product): void
    {
        foreach($product->images as $image) {
            Storage::disk(self::IMAGE_DISK)->delete($image->image);
            $image->delete();
        }
    }

}
