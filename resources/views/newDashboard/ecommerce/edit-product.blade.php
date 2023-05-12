 @extends('newDashboard.layouts.master')
 @section('content')
     <x-add_edit_item :name="__('app.products.edit_product')">
         <!--Begin::Form-->
         <x-product_content_form :product="$product" :categories="$categories"></x-product_content_form>
         <!--End::Form-->
     </x-add_edit_item>
 @endsection
