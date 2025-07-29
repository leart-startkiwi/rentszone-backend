@if(is_plugin_active('faq') && ($faqItems = $car->faq_items))
    <div class="group-collapse-expand">
        <button class="btn btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuestion" aria-expanded="false" aria-controls="collapseQuestion">
            <strong class="heading-6">{{ __('Question & Answers') }}</strong>
            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L6 6L11 1" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <div class="collapse show" id="collapseQuestion">
            <div class="card card-body">
                <div class="list-questions">
                    @foreach($faqItems as $faq)
                        @php
                            $faqItem = (collect($faq)->pluck('value', 'key'));
                        @endphp

                        @continue(! ($question = $faqItem->get('question')) || ! ($answer = $faqItem->get('answer')))

                        <div class="item-question">
                            <div class="head-question">
                                <p class="text-md-bold neutral-1000">{!! BaseHelper::clean($question) !!}</p>
                            </div>
                            <div class="content-question">
                                <p class="text-sm-medium neutral-800">{!! BaseHelper::clean($answer) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
