@extends('layouts.layout')
@section('main')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('products')}}">Products</a></li>
                        </li>
                        <li class="breadcrumb-item active">Edit
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- Product Details starts -->
                        <div class="card-body">
                            <div class="row my-2">
                                <div class="col-12 col-md-5 d-flex align-items- justify-content-center mb-2 mb-md-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/app/public') }}/{{ $product->image }}" class="img-fluid rounded product-img" alt="product image" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <h4>{{$product->name}}</h4>
                                    <span class="card-text item-company">By <a href="javascript:void(0)" class="company-name">{{$product->brand}}</a></span>
                                    <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                        <h4 class="item-price text-primary mr-1">${{$product->sale_price}}</h4>
                                    </div>
                                    <p class="card-text">Available - <span class="text-success">In stock</span></p>
                                    <p class="card-text"><b>Type:</b> {{$product->type}}</p>
                                    <p class="card-text"><b>Sku:</b> {{$product->sku}}</p>
                                    <p class="card-text"><b>Manufacturer:</b> {{$product->manufacturer}}</p>
                                    <p class="card-text"><b>Weight:</b> {{$product->weight}}</p>
                                    <p class="card-text"><b>Dimensions:</b> {{$product->length}} x {{$product->width}} x {{$product->height}}</p>
                                    <p class="card-text">
                                      {{$product->description}}
                                    </p>
                                    <ul class="product-features list-unstyled">
                                        <li><i data-feather="shopping-cart"></i> <span>Free Shipping</span></li>
                                    </ul>
                                    <hr />
                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->


</div>
@endsection