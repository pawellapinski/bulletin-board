@extends('products.layout')

@section('content')

    <div class="card mt-5">
        <h2 class="card-header">Bulletin board</h2>
        <div class="card-body">

            @session('success')
            <div class="alert alert-success" role="alert"> {{ $value }} </div>
            @endsession

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn btn-success btn-sm" href="{{ route('products.create') }}"> <i class="fa fa-plus"></i> Create New Product</a>
            </div>

            <table class="table table-bordered table-striped mt-4">
                <thead>
                <tr>
                    <th width="80px">No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Price</th>
                    <th width="250px">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->image) }}"
                                     class="img-thumbnail"
                                     style="max-height: 50px;">
                            @else
                                <span>No image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->detail }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            <form action="{{ route('products.destroy',$product->id) }}" method="POST">

                                <a class="btn btn-info btn-sm" href="{{ route('products.show',$product->id) }}"><i class="fa-solid fa-list"></i> Show</a>

                                <a class="btn btn-primary btn-sm" href="{{ route('products.edit',$product->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">There are no data.</td>
                    </tr>
                @endforelse
                </tbody>

            </table>

            {!! $products->links() !!}

        </div>
    </div>
@endsection
