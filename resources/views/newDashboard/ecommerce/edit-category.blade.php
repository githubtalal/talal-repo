 @extends('newDashboard.layouts.master')
 @section('content')
     <x-add_edit_item :name="__('app.categories.edit_category')">
         <!--begin::Form-->
         <x-category_content_form :category="$category"></x-category_content_form>
         <!--end::Form-->
     </x-add_edit_item>
 @endsection
