@extends('layouts.layout')

@section('main')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('products')}}">Products</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Products</h4>
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success p-1 m-1">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                    <th>Sale Price</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td class="p-1">
                                        <img src="{{ asset('storage/app/public') }}/{{ $product->image }}" alt="img" class="rounded" style="width: 100%; height: 80px; object-fit: cover;">
                                    </td>
                                    
                                    <td><a href="{{ route('products.show', ['id' => $product->id]) }}">{{ $product->name }}</a></td>
                                    <td @if($product->qty < 11) class="text-danger font-weight-bold" @endif>{{ $product->qty }}</td>
                                    <td>{{ $product->purchase_price }}</td>
                                    <td>{{ $product->sale_price }}</td>
                                    <td class="p-0 text-center">{{ date('d M, Y', strtotime($product->created_at)) }}</td>
                                    <td class="d-flex pt-3">
                                        <a href="{{url('products/edit/')}}/{{$product->id}}" class="btn btn-sm btn-primary mr-1"><i data-feather='edit'></i></a>
                                        <a href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete?')) window.location.href='{{url('products/delete/')}}/{{$product->id}}';" class="btn btn-sm btn-danger"><i data-feather='trash'></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
