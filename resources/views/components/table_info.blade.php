 <!--begin::Post-->
 <div class="post d-flex flex-column-fluid" style="margin-bottom: 30px">
     <!--begin::Container-->
     <div class="container-xxl">
         <div class="card card-flush">
             <!--begin::Card header-->
             <div class="card-header py-5 gap-2 gap-md-5">
                 {{ $header }}
             </div>
             <!--end::Card header-->
             <!--begin::items info-->
             <div class="card-body pt-0">
                 <!--begin::Table-->
                 <table class="table align-middle table-row-dashed fs-6 gy-5">
                     <!--begin::Table head-->
                     <thead>
                         <!--begin::Table row-->
                         <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                             @foreach ($items as $item)
                                 <th class="min-w-100px">{{ $item }}</th>
                             @endforeach
                         </tr>
                         <!--end::Table row-->
                     </thead>
                     <!--end::Table head-->
                     <!--begin::Table body-->
                     <tbody class="fw-bold text-gray-600">
                         {{ $data }}
                     </tbody>
                     <!--end::Table body-->
                 </table>
                 <!--end::Table-->
                 {{ $links }}
             </div>
             <!--end::items info-->
         </div>
     </div>
     <!--end::Container-->
 </div>
 <!--end::Post-->
