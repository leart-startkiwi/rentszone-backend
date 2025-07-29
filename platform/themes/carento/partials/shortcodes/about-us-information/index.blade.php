<section class="section-1 py-96 background-body">
    <div class="container">
        <div class="row pb-50">
            <div class="col-lg-4">
                @if($title = $shortcode->title)
                    <h2 class="heading-3 shortcode-title">
                        {!! BaseHelper::clean($title) !!}
                    </h2>
                @endif
            </div>

            @if ($description = $shortcode->description)
                <div class="col-lg-7 offset-lg-1">
                    <p class="text-lg-medium neutral-500">{!! BaseHelper::clean($description) !!}</p>
                </div>
            @endif

        </div>
        @if(count($tabs) > 0)
            @php
                $tabsChunk = array_chunk($tabs, 3);
            @endphp

            @foreach($tabsChunk as $tabList)
                <div class="row g-4 mb-4">
                    @foreach($tabList as $tab)
                        @php
                            $dataTitle = Arr::get($tab, 'data_title');
                            $dataNumber = Arr::get($tab, 'data_number');
                            $image = Arr::get($tab, 'image');
                        @endphp

                        @continue($loop->index > 1 || (! $dataTitle && ! $image))

                        <div class="col-lg-4 col-md-6">
                            <div class="box-image rounded-12 position-relative overflow-hidden">
                                @if($image)
                                    {!! RvMedia::image($image, $dataTitle, attributes: ['class' => 'rounded-12']) !!}
                                @endif

                                @if($dataNumber || $dataTitle)
                                    <div class="box-tag bg-white p-3 d-flex position-absolute bottom-0 end-0 rounded-12 m-3">
                                        @if($dataNumber)
                                            <span class="text-dark fs-72 me-3">{!! BaseHelper::clean($dataNumber) !!}</span>
                                        @endif

                                        @if ($dataTitle)
                                            <h6>
                                                {!! BaseHelper::clean($dataTitle) !!}
                                            </h6>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if(count($tabList) == 3)
                        @php
                            $lastDataTitle = Arr::get($tabList[2], 'data_title');
                            $lastDataNumber = Arr::get($tabList[2], 'data_number');
                            $lastImage = Arr::get($tabList[2], 'image');
                        @endphp
                        <div class="col-lg-4 col-12">
                            <div class="d-flex flex-column gap-4 align-self-stretch h-100">
                                @if ($lastDataTitle || $lastDataNumber)
                                    <div class="box-tag background-brand-2 p-5 d-flex rounded-12">
                                        @if($lastDataNumber)
                                            <span class="text-dark fs-96 me-3">{!! BaseHelper::clean($lastDataNumber) !!}</span>
                                        @endif

                                        @if($lastDataTitle)
                                            <h4>
                                                {!! BaseHelper::clean($lastDataTitle) !!}
                                            </h4>
                                        @endif
                                    </div>
                                @endif

                                @if($lastImage)
                                    {!! RvMedia::image($lastImage, $lastDataTitle, attributes: ['class' => 'rounded-12']) !!}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</section>
