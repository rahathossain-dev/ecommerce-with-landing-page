@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Landing Pages'))

@push('css_or_js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.umd.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

@endpush


@section('content')


<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Landing Page Setup</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ \App\CPU\translate('Page Setup Form')}}
                </div>
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{route('admin.landingPage.setupStore')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php($language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')
                        @php($default_lang = json_decode($language)[0])
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                    <input type="text" name="title" value="{{ $data->title ?? '' }}"
                                        class="form-control" placeholder="Title">
                                    <input type="hidden" name="landing_page_id" class="form-control"
                                        value="{{ $landing_page_id }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                    <textarea id="editor" name="description" class="form-control"
                                        placeholder="Description">{{ $data->description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('Offer Time')}}</label>
                                    <input type="datetime-local" value="{{ $data->offer_time ?? '' }}" name="offer_time"
                                        class="form-control" />
                                </div>
                            </div>
                            <!-- <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('Benifites')}}</label>
                                    <textarea id="benifites" name="benifites" class="form-control"
                                        placeholder="Benifites"></textarea>
                                </div>
                            </div> -->
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{\App\CPU\translate('Customer Review')}}</label>
                                    <input type="file" name="customer_review[]" multiple class="form-control" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <div class="accordion-container">
                                        <h2>Section</h2>
                                        <div id="accordion">
                                            @if ($data->sections->count() > 0)
                                                @foreach ($data->sections as $key => $section)
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" onclick="expandAccordion(event)">
                                                            Section {{ ++$key }}
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                        <div class="accordion-content">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                                                        <input type="text" value="{{ $section->title }}"
                                                                            name="section[{{$key}}][title]" class="form-control"
                                                                            placeholder="Title">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                                                        <textarea class="sectionEditor"
                                                                            name="section[{{ $key }}][description]"
                                                                            class="form-control"
                                                                            placeholder="Description">{{ $section->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Banner')}}</label>
                                                                        <input type="file" name="section[{{ $key }}][banner]"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Button Text')}}</label>
                                                                        <input type="text" value="{{ $section->button_text }}"
                                                                            name="section[{{ $key }}][button_text]"
                                                                            class="form-control" placeholder="Button Text">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Button Link')}}</label>
                                                                        <input type="text" value="{{ $section->button_link }}"
                                                                            name="section[{{ $key }}][button_link]"
                                                                            class="form-control" placeholder="Button Link">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="accordion-item">
                                                    <div class="accordion-header" onclick="expandAccordion(event)">
                                                        Section 1
                                                        <i class="fas fa-chevron-down"></i>
                                                    </div>
                                                    <div class="accordion-content">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                                                    <input type="text" name="section[1][title]"
                                                                        class="form-control" placeholder="Title">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                                                    <textarea class="sectionEditor"
                                                                        name="section[1][description]" class="form-control"
                                                                        placeholder="Description"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Banner')}}</label>
                                                                    <input type="file" name="section[1][banner]"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Button Text')}}</label>
                                                                    <input type="text" name="section[1][button_text]"
                                                                        class="form-control" placeholder="Button Text">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Button Link')}}</label>
                                                                    <input type="text" name="section[1][button_link]"
                                                                        class="form-control" placeholder="Button Link">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif


                                        </div>

                                    </div>
                                    <button class="btn btn-primary mt-2" id="add_section">Add Section</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group lang_form">
                                    <div class="faq-container">
                                        <h2>FAQ</h2>
                                        <div id="faq">
                                            @if ($data->faqs->count() > 0)
                                                @foreach ($data->faqs as $key => $faqItem)
                                                    <div class="faq-item">
                                                        <div class="faq-header" onclick="expandFaq(event)">
                                                            FAQ {{ ++$key }}
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                        <div class="faq-content">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                                                        <input value="{{ $faqItem->question }}" type="text"
                                                                            name="faq[{{ $key }}][question]" class="form-control"
                                                                            placeholder="Question">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group lang_form">
                                                                        <label class="input-label"
                                                                            for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                                                        <textarea class="faqEditor" name="faq[{{ $key }}][answare]"
                                                                            class="form-control"
                                                                            placeholder="Answare">{{ $faqItem->answare }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="faq-item">
                                                    <div class="faq-header" onclick="expandFaq(event)">
                                                        FAQ 1
                                                        <i class="fas fa-chevron-down"></i>
                                                    </div>
                                                    <div class="faq-content">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                                                    <input type="text" name="faq[1][question]"
                                                                        class="form-control" placeholder="Question">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group lang_form">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                                                    <textarea class="faqEditor" name="faq[1][answare]"
                                                                        class="form-control"
                                                                        placeholder="Answare"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <button class="btn btn-primary mt-2" id="add_faq">Add Faq</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="name">{{ \App\CPU\translate('Add product')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="product_id" id="product_id">
                                    @foreach (\App\Model\Product::orderBy('name', 'asc')->get() as $key => $product)
                                        <option value="{{ $product->id }}">
                                            {{$product['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="name">{{ \App\CPU\translate('Delevery Option')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="delevery_option" id="delevery_option">
                                    @foreach ($deleveryOptions as $key => $deleveryOption)
                                        <option value="{{ $deleveryOption->id }}">
                                            {{$deleveryOption->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
    .accordion-container h2,
    .faq-container h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 30px;
        text-align: center;
    }

    .accordion-item,
    .faq-item {
        background: #ffffff;
        border: none;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .accordion-header,
    .faq-header {
        background: #0073aa;
        color: white;
        padding: 5px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s ease;
    }

    .accordion-header:hover {
        background: #005f8d;
    }

    .faq-header:hover {
        background: #005f8d;
    }

    .accordion-header i,
    .faq-header i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .accordion-header.active i {
        transform: rotate(180deg);
    }

    .faq-header.active i {
        transform: rotate(180deg);
    }

    .accordion-content,
    .faq-content {
        display: none;
        padding: 15px;
        background: #fff;
        font-size: 1rem;
        color: #495057;
    }

    .accordion-header.active+.accordion-content {
        display: block;
    }

    .faq-header.active+.faq-content {
        display: block;
    }
</style>

<script>
    document.getElementById('product_id').value="{{ $data->product_id }}"
    document.getElementById('delevery_option').value="{{ $data->delevery_id ?? '' }}"
    function expandFaq(e, elementData = null) {
        e.preventDefault();
        e.target.classList.toggle("active");
        document.querySelectorAll(".faq-header").forEach((item) => {
            if (item !== e.target) {
                item.classList.remove("active");
                item.nextElementSibling.style.display = "none";
            }
        });
        const content = e.target.nextElementSibling;
        if (e.target.classList.contains("active")) {
            content.style.display = "block";
            if (elementData != null) {
                if (window.document.getElementById(`faqEditor${elementData}`).style.display != 'none') {
                    ClassicEditor
                        .create(window.document.getElementById(`faqEditor${elementData}`), {
                            licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzM5NjQ3OTksImp0aSI6IjViMTMxYTZkLTJmZDQtNDAxMS1hNDc2LWFlYmNmMTZiZDI0NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMjFkYThhZmYifQ.o_bE99T6xXDu-4gt08RCxo4gJOG_-9AZ8wsoNQ1OH1C0MOfAV5dpK3X4Lzhf43m7leYZxXSeUJh16Hg0_npCcQ',
                            plugins: [Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, Link, Underline, SourceEditing, ListItem],
                            toolbar: [
                                'undo', 'redo', '|', 'bold', 'italic', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                                'heading', 'imageUpload', 'link', 'insertTable', '|',
                                'bulletedList', 'numberedList', 'alignment', 'underline', 'sourceEditing'
                            ]
                        })
                        .then(editor => {
                            // Set the height of the editor after initialization
                            const editorContainer = editor.ui.view.editable.element;
                            editorContainer.style.height = '400px'; // Set the height here
                        })
                        .catch( /* ... */);
                }

            }

        } else {
            content.style.display = "none";
        }

    }
    function expandAccordion(e, elementData = null) {
        e.preventDefault();
        e.target.classList.toggle("active");
        document.querySelectorAll(".accordion-header").forEach((item) => {
            if (item !== e.target) {
                item.classList.remove("active");
                item.nextElementSibling.style.display = "none";
            }
        });
        const content = e.target.nextElementSibling;
        if (e.target.classList.contains("active")) {
            content.style.display = "block";
            if (elementData != null) {
                if (window.document.getElementById(`dModalEditor${elementData}`).style.display != 'none') {
                    ClassicEditor
                        .create(window.document.getElementById(`dModalEditor${elementData}`), {
                            licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzM5NjQ3OTksImp0aSI6IjViMTMxYTZkLTJmZDQtNDAxMS1hNDc2LWFlYmNmMTZiZDI0NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMjFkYThhZmYifQ.o_bE99T6xXDu-4gt08RCxo4gJOG_-9AZ8wsoNQ1OH1C0MOfAV5dpK3X4Lzhf43m7leYZxXSeUJh16Hg0_npCcQ',
                            plugins: [Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, Link, Underline, SourceEditing, ListItem],
                            toolbar: [
                                'undo', 'redo', '|', 'bold', 'italic', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                                'heading', 'imageUpload', 'link', 'insertTable', '|',
                                'bulletedList', 'numberedList', 'alignment', 'underline', 'sourceEditing'
                            ]
                        })
                        .then(editor => {
                            // Set the height of the editor after initialization
                            const editorContainer = editor.ui.view.editable.element;
                            editorContainer.style.height = '400px'; // Set the height here
                        })
                        .catch( /* ... */);
                }

            }

        } else {
            content.style.display = "none";
        }



    }

    function handleRemove(e) {
        e.target.parentElement.parentElement.parentElement.remove();
    }

    const add_faq = document.getElementById('add_faq')
    const add_section = document.getElementById('add_section')
    const accordion = document.getElementById('accordion');
    const faq = document.getElementById('faq');
    add_section.addEventListener('click', function (e) {
        e.preventDefault();
        const countElement = document.querySelectorAll(".accordion-header").length + 1;
        accordion.innerHTML += `
            <div class="accordion-item">
                <div class="accordion-header" onclick="expandAccordion(event, ${countElement})">
                    Section ${countElement}
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="accordion-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                <input type="text" name="section[${countElement}][title]" class="form-control"
                                    placeholder="Title">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                <textarea id="dModalEditor${countElement}" name="section[${countElement}][description]"
                                    class="form-control"
                                    placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Banner')}}</label>
                                <input type="file" name="section[${countElement}][banner]" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Button Text')}}</label>
                                <input type="text" name="section[${countElement}][button_text]" class="form-control" placeholder="Button Text">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Button Link')}}</label>
                                <input type="text" name="section[${countElement}][button_link]" class="form-control" placeholder="Button Link">
                            </div>
                        </div>
                        
                        <button class="btn btn-danger" onclick="handleRemove(event)">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        `
    })
    add_faq.addEventListener('click', function (e) {
        e.preventDefault();
        const countElement = document.querySelectorAll(".faq-header").length + 1;
        faq.innerHTML += `

            <div class="faq-item">
                <div class="faq-header" onclick="expandFaq(event, ${countElement})">
                    FAQ ${countElement}
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                <input type="text" name="faq[${countElement}][question]"
                                    class="form-control" placeholder="Question">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group lang_form">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                <textarea id="faqEditor${countElement}" name="faq[${countElement}][answare]"
                                    class="form-control"
                                    placeholder="Answare"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-danger" onclick="handleRemove(event)">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        `
    })



    const {
        ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, TableToolbar, Link, Underline, SourceEditing, List: ListItem
    } = CKEDITOR;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzM5NjQ3OTksImp0aSI6IjViMTMxYTZkLTJmZDQtNDAxMS1hNDc2LWFlYmNmMTZiZDI0NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMjFkYThhZmYifQ.o_bE99T6xXDu-4gt08RCxo4gJOG_-9AZ8wsoNQ1OH1C0MOfAV5dpK3X4Lzhf43m7leYZxXSeUJh16Hg0_npCcQ',
            plugins: [Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, TableToolbar, Link, Underline, SourceEditing, ListItem],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                'heading', 'imageUpload', 'link', 'insertTable', ' | ',
                'bulletedList', 'numberedList', 'alignment', 'underline', 'sourceEditing'
            ]
        })
        .then()
        .catch( /* ... */);

    window.document.querySelectorAll('.sectionEditor').forEach((elementItem) => {
        ClassicEditor
            .create(elementItem, {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzM5NjQ3OTksImp0aSI6IjViMTMxYTZkLTJmZDQtNDAxMS1hNDc2LWFlYmNmMTZiZDI0NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMjFkYThhZmYifQ.o_bE99T6xXDu-4gt08RCxo4gJOG_-9AZ8wsoNQ1OH1C0MOfAV5dpK3X4Lzhf43m7leYZxXSeUJh16Hg0_npCcQ',
                plugins: [Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, TableToolbar, Link, Underline, SourceEditing, ListItem],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'heading', 'imageUpload', 'link', 'insertTable', ' | ',
                    'bulletedList', 'numberedList', 'alignment', 'underline', 'sourceEditing'
                ]
            })
            .then()
            .catch( /* ... */);
    })
    window.document.querySelectorAll('.faqEditor').forEach((elementItem) => {

        ClassicEditor
            .create(elementItem, {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzM5NjQ3OTksImp0aSI6IjViMTMxYTZkLTJmZDQtNDAxMS1hNDc2LWFlYmNmMTZiZDI0NyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiMjFkYThhZmYifQ.o_bE99T6xXDu-4gt08RCxo4gJOG_-9AZ8wsoNQ1OH1C0MOfAV5dpK3X4Lzhf43m7leYZxXSeUJh16Hg0_npCcQ',
                plugins: [Essentials, Bold, Italic, Font, Paragraph, Alignment, Heading, Image, ImageUpload, Table, TableToolbar, Link, Underline, SourceEditing, ListItem],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'heading', 'imageUpload', 'link', 'insertTable', '|',
                    'bulletedList', 'numberedList', 'alignment', 'underline', 'sourceEditing'
                ]
            })
            .then(editor => {
                // Set the height of the editor after initialization
                const editorContainer = editor.ui.view.editable.element;
                editorContainer.style.height = '400px'; // Set the height here
            })
            .catch( /* ... */);
    })



</script>
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