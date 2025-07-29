@if(count($customers) > 0)
    @foreach($customers as $customer)
        <div class="dropdown-item customer-item" data-id="{{ $customer->id }}">
            <div class="d-flex align-items-center">
                <span class="avatar avatar-sm me-2" style="background-image: url('{{ $customer->avatar_url }}')">
                    @if(!$customer->avatar)
                        {{ substr($customer->name, 0, 2) }}
                    @endif
                </span>
                <div>
                    <div class="font-weight-medium">{{ $customer->name }}</div>
                    <div class="text-secondary text-truncate" style="max-width: 200px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                            <path d="M3 7l9 6l9 -6"></path>
                        </svg>
                        {{ $customer->email }}
                    </div>
                    <div class="text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                        </svg>
                        {{ $customer->phone }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="dropdown-item">
        <div class="d-flex align-items-center text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-sad me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                <path d="M9 10l.01 0"></path>
                <path d="M15 10l.01 0"></path>
                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
            </svg>
            {{ __('No customers found') }}
        </div>
    </div>
@endif

<style>
    .dropdown-item.customer-item {
        padding: 0.5rem 1rem;
    }
    .dropdown-item.customer-item:hover {
        background-color: rgba(var(--tblr-primary-rgb), 0.1);
    }
    .dropdown-item .icon {
        width: 16px;
        height: 16px;
        vertical-align: text-bottom;
    }
</style>
