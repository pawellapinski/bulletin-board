@extends('products.layout')

@section('content')

    <div class="card mt-5">
        <h2 class="card-header">Edit Offer</h2>
        <div class="card-body">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
            </div>

            <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="images" class="form-label"><strong>Zdjęcia (max 5):</strong></label>
                    <input type="file" name="images[]" multiple class="form-control @error('images') is-invalid @enderror" accept="image/*">
                    @error('images')
                    <div class="form-text text-danger">{{ $message }}</div>
                    @enderror

                    <div class="row mt-3">
                        @foreach($product->images as $image)
                            <div class="col-md-4 mt-2">
                                <a href="{{ Storage::url($image->image) }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                                    <img src="{{ Storage::url($image->image) }}" class="img-fluid rounded">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="inputName" class="form-label"><strong>Name:</strong></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ $product->name }}"
                        class="form-control @error('name') is-invalid @enderror"
                        id="inputName"
                        placeholder="Name">
                    @error('name')
                    <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inputDetail" class="form-label"><strong>Detail:</strong></label>
                    <textarea
                        class="form-control @error('detail') is-invalid @enderror"
                        style="height:150px"
                        name="detail"
                        id="inputDetail"
                        placeholder="Detail">{{ $product->detail }}</textarea>
                    @error('detail')
                    <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="inputPrice" class="form-label"><strong>Price:</strong></label>
                    <input
                        type="text"
                        name="price"
                        value="{{ $product->price }}"
                        class="form-control @error('price') is-invalid @enderror"
                        id="inputPrice"
                        placeholder="Price">
                    @error('price')
                    <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
            </form>

        </div>
    </div>
@endsection
