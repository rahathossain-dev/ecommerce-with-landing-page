@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Landing Pages'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Landing Pages</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ \App\CPU\translate('Create Page Form')}}
                </div>
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{route('admin.landingPage.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @php($language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')
                        @php($default_lang = json_decode($language)[0])
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="Page Name">
                                </div>
                                <input name="position" value="0" style="display: none">
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Create Page')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 20px" id="cate-table">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex-between justify-content-between align-items-center flex-grow-1">
                        <div>
                            <h5>{{ \App\CPU\translate('category_table')}} <span
                                    style="color: red;">({{ $pages->count() }})</span></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 100px">{{ \App\CPU\translate('category')}}
                                        {{ \App\CPU\translate('ID')}}
                                    </th>
                                    <th>{{ \App\CPU\translate('name')}}</th>
                                    <th>{{ \App\CPU\translate('slug')}}</th>
                                    <th class="text-center" style="width:15%;">{{ \App\CPU\translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $key => $page)
                                    <tr>
                                        <td class="text-center">{{$page->id}}</td>
                                        <td>{{$page->name}}</td>
                                        <td>{{$page->slug}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm edit" style="cursor: pointer;"
                                                href="{{route('landingPage', [$page->slug])}}">
                                                <i class="tio-eye"></i> View
                                            </a>
                                            <a class="btn btn-primary btn-sm edit" style="cursor: pointer;"
                                                href="{{route('admin.landingPage.setup', [$page->id])}}">
                                                <i class="tio-edit"></i> Setup
                                            </a>
                                            <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                                id="{{$page->id}}">
                                                <i class="tio-add-to-trash"></i>{{ \App\CPU\translate('Delete')}}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(count($pages) == 0)
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                            alt="Image Description" style="width: 7rem;">
                        <p class="mb-0">{{\App\CPU\translate('no_data_found')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are_you_sure')}}?',
                text: "{{\App\CPU\translate('You_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete_it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.category.delete')}}",
                        method: 'POST',
                        data: { id: id },
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Category_deleted_Successfully.')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush