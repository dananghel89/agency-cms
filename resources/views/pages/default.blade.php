@section('title', $page->title)
@section('description', $page->description)

<x-app-layout>

<section class="page-section" id="page">
    <div class="container">
        <h2 class="section-heading text-uppercase">{{ $page->title }}</h2>

        <div class="page-content">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</section>

</x-app-layout>
