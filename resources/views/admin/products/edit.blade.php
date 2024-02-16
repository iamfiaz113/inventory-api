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
    <!-- Ajax Sourced Server-side -->
                    <!-- Bootstrap Validation -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit product</h4>
                            </div>
                            <div class="card-body">
                                <img id="selectedImage" src="{{asset('storage/app/public')}}/{{$product->image}}" class="img-fluid rounded mb-1" alt="Selected Image" style="max-height:200px">

                                <form id="jquery-val-form" action="{{ route('products.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                    <!-- Product Type -->
                                    <label class="form-label">Type</label>
                                    <div class="form-group d-flex">
                                        <div class="custom-control custom-radio mr-2">
                                            <input type="radio" id="validationRadio3" name="type" value="goods" class="custom-control-input" {{ old('type', $product->type) === 'goods' ? 'checked' : '' }} required />
                                            <label class="custom-control-label" for="validationRadio3">Goods</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="validationRadio4" name="type" value="service" class="custom-control-input" {{ old('type', $product->type) === 'service' ? 'checked' : '' }} required />
                                            <label class="custom-control-label" for="validationRadio4">Service</label>
                                        </div>
                                    </div>
                                    <!-- General Information -->
                                    <div class="form-group">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" value="{{ old('name', $product->name) }}" required />
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="purchase_price">Purchase price</label>
                                                <input type="number" id="purchase_price" name="purchase_price" class="form-control" placeholder="Purchase price" value="{{ old('purchase_price', $product->purchase_price) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="sale_price">Sale price</label>
                                                <input type="text" id="sale_price" name="sale_price" class="form-control" placeholder="Sale price" value="{{ old('sale_price', $product->sale_price) }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="sku">SKU</label>
                                                <input type="text" id="sku" name="sku" class="form-control" placeholder="Product SKU" value="{{ old('sku', $product->sku) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="manufacturer">Manufacturer</label>
                                                <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="Manufacturer" value="{{ old('manufacturer', $product->manufacturer) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="upc">UPC</label>
                                                <input type="text" id="upc" name="upc" class="form-control" placeholder="UPC" value="{{ old('upc', $product->upc) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="ean">EAN</label>
                                                <input type="text" id="ean" name="ean" class="form-control" placeholder="EAN" value="{{ old('ean', $product->ean) }}" required />
                                            </div>
                                        </div>
                                    </div>
                                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="weight">Weight</label>
                                                <input type="text" id="weight" name="weight" class="form-control" placeholder="Product Weight" value="{{ old('weight', $product->weight) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="brand">Brand</label>
                                                <input type="text" id="brand" name="brand" class="form-control" placeholder="Product Brand" value="{{ old('brand', $product->brand) }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="qty">Quantity</label>
                                                <input type="number" id="qty" name="qty" class="form-control" placeholder="Quantity" value="{{ old('qty', $product->qty) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="isbn">ISBN</label>
                                                <input type="text" id="isbn" name="isbn" class="form-control" placeholder="ISBN" value="{{ old('isbn', $product->isbn) }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="unit">Unit</label>
                                                <input type="text" id="unit" name="unit" class="form-control" placeholder="Product Unit" value="{{ old('unit', $product->unit) }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="returnable">Returnable Item</label>
                                                <select id="returnable" name="returnable" class="form-control">
                                                    <option value="yes" {{ old('returnable', $product->returnable) === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="no" {{ old('returnable', $product->returnable) === 'no' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Dimensions -->
                                    <div class="form-group">
                                        <label class="form-label">Dimensions (Length X Width X Height)</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="number" class="form-control" name="length" placeholder="Length" value="{{ old('length', $product->length) }}" required />
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" name="width" placeholder="Width" value="{{ old('width', $product->width) }}" required />
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" name="height" placeholder="Height" value="{{ old('height', $product->height) }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="customFile1">Image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" id="customFile1" multiple onchange="displayImage(this)" />
                                            <label class="custom-file-label" for="customFile1">Choose product pic</label>
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please choose product pictures.</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                    <!-- /Bootstrap Validation -->


</div>

<script>
    var imgsrc="{{asset('storage/app/public')}}/{{$product->image}}";
function displayImage(input) {
    const selectedImage = document.getElementById('selectedImage');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            selectedImage.src = e.target.result;
            selectedImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        selectedImage.src = imgsrc;
    }
}

</script>
@endsection