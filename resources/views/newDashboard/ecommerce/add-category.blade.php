 @extends('newDashboard.layouts.master')
 @section('content')
     <x-add_edit_item :name="__('app.categories.add_new_category')">
         <!--Begin::Form-->
         <x-category_content_form :category="null"></x-category_content_form>
         <!--End::Form-->
     </x-add_edit_item>
 @endsection
