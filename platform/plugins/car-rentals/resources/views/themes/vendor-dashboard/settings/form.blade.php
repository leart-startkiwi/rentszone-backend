@extends(CarRentalsHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="row">
        <div class="col-md-12">
                    <div class="tabbable-custom">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab_general" class="nav-link active" data-bs-toggle="tab">{{ __('General') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab_payout_info" class="nav-link" data-bs-toggle="tab">{{ __('Payout info') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_general">
                                {!! $form !!}
                            </div>
                            <div class="tab-pane" id="tab_payout_info">
                                {!! $payoutInformationForm !!}
                            </div>
                        </div>
                    </div>
        </div>
    </div>
@stop
