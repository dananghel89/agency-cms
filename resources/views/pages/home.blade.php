@section('title', __('Home'))

<x-app-layout>

<!-- Services -->
@if ($services->count())
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">{{ __('Services') }}</h2>
                <h3 class="section-subheading text-muted">{{ __('Lorem ipsum dolor sit amet consectetur.') }}</h3>
            </div>
            <div class="row text-center">
                @foreach ($services as $serviceItem)
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-{{ $serviceItem->icon }} fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">{{ $serviceItem->title }}</h4>
                    <p class="text-muted">{{ $serviceItem->content }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Portfolio Grid -->
@if ($portfolio->count())
    <section class="page-section bg-light" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">{{ __('Portfolio') }}</h2>
                <h3 class="section-subheading text-muted">{{ __('Lorem ipsum dolor sit amet consectetur.') }}</h3>
            </div>
            <div class="row">
                @foreach ($portfolio as $portfolioIndex => $portfolioItem)
                @php
                    ++$portfolioIndex;
                @endphp
                <div class="col-lg-4 col-sm-6 mb-4">
                    <div class="portfolio-item">
                        <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal{{ $portfolioIndex }}">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            @if ($portfolioItem->image)
                                <img class="img-fluid" src="{{ $portfolioItem->image }}" alt="">
                            @else
                                <img class="img-fluid" src="{{ asset('img/agency/placeholder.svg') }}" alt="">  
                            @endif
                        </a>
                        <div class="portfolio-caption">
                            <div class="portfolio-caption-heading">{{ $portfolioItem->client }}</div>
                            <div class="portfolio-caption-subheading text-muted">{{ $portfolioItem->category }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section> 
@endif

<!-- About -->
@if ($timeline->count())
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">{{ __('About') }}</h2>
                <h3 class="section-subheading text-muted">{{ __('Lorem ipsum dolor sit amet consectetur.') }}</h3>
            </div>
            <ul class="timeline">
                @foreach ($timeline as $timelineIndex => $timelineItem)
                @php
                    ++$timelineIndex;
                    $itemClass = ($timelineIndex % 2 == 0) ? 'timeline-inverted' : '';
                @endphp
                <li class="{{ $itemClass }}">
                    <div class="timeline-image">
                        @if ($timelineItem->image)
                            <img class="rounded-circle img-fluid" src="{{ $timelineItem->image }}" alt="">
                        @else
                            <img class="rounded-circle img-fluid square" src="{{ asset('img/agency/placeholder-square.svg') }}" alt="">  
                        @endif
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>{{ $timelineItem->title }}</h4>
                            <h4 class="subheading">{{ $timelineItem->subtitle }}</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">{{ $timelineItem->content }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
                <li class="timeline-inverted">
                    <div class="timeline-image">
                        <h4>{!! __('Be Part <br> Of Our <br> Story!') !!}</h4>
                    </div>
                </li>
            </ul>
        </div>
    </section>
@endif

<!-- Team -->
@if ($team->count())
    <section class="page-section bg-light" id="team">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">{{ __('Our Amazing Team') }}</h2>
                <h3 class="section-subheading text-muted">{{ __('Lorem ipsum dolor sit amet consectetur.') }}</h3>
            </div>
            <div class="row">
                @foreach ($team as $teamItem)
                <div class="col-lg-4">
                    <div class="team-member">
                        @if ($teamItem->image)
                            <img class="mx-auto rounded-circle" src="{{ $teamItem->image }}" alt="">
                        @else
                            <img class="mx-auto rounded-circle" src="{{ asset('img/agency/placeholder.svg') }}" alt="">  
                        @endif
                        <h4>{{ $teamItem->name }}</h4>
                        <p class="text-muted">{{ $teamItem->profession }}</p>
                        <x-social-links :item="$teamItem->social_links" />
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="large text-muted">{{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.') }}</p>
                </div>
            </div>
        </div>
    </section>
@endif

<!-- Clients -->
<div class="py-5" id="clients">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 col-sm-6 my-3">
                <a href="javascript:void(0)"><img class="img-fluid img-brand d-block mx-auto" src="{{ asset('img/agency/logos/microsoft.svg') }}" alt="Microsoft Logo"></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="javascript:void(0)"><img class="img-fluid img-brand d-block mx-auto" src="{{ asset('img/agency/logos/google.svg') }}" alt="Google Logo"></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="javascript:void(0)"><img class="img-fluid img-brand d-block mx-auto" src="{{ asset('img/agency/logos/facebook.svg') }}" alt="Facebook Logo"></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="javascript:void(0)"><img class="img-fluid img-brand d-block mx-auto" src="{{ asset('img/agency/logos/ibm.svg') }}" alt="IBM Logo"></a>
            </div>
        </div>
    </div>
</div>

<livewire:pages.contact />

<!-- Portfolio Modals -->
@if ($portfolio->count())
    @foreach ($portfolio as $portfolioIndex => $portfolioItem)
        @php
            ++$portfolioIndex;
        @endphp
        <div class="portfolio-modal modal fade" id="portfolioModal{{ $portfolioIndex }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal">
                        <img src="{{ asset('img/agency/close-icon.svg') }}" alt="{{ __('Close modal') }}">
                    </div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <h2 class="text-uppercase">{{ $portfolioItem->title }}</h2>
                                    <p class="item-intro text-muted">{{ $portfolioItem->subtitle }}</p>
                                    @if ($portfolioItem->image)
                                        <img class="img-fluid d-block mx-auto" src="{{ $portfolioItem->image }}" alt="">
                                    @else
                                        <img class="img-fluid d-block mx-auto" src="{{ asset('img/agency/placeholder.svg') }}" alt="">  
                                    @endif
                                    <p>{{ $portfolioItem->content }}</p>
                                    <ul class="list-inline">
                                        <li><strong>{{ __('Client:') }}</strong> {{ $portfolioItem->client }}</li>
                                        <li><strong>{{ __('Category:') }}</strong> {{ $portfolioItem->category }}</li>
                                    </ul>
                                    <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i> {{ __('Close Project') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

</x-app-layout>
