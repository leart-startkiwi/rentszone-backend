<div class="sidebar-canvas-wrapper perfect-scrollbar button-bg-2">
    <div class="sidebar-canvas-container">
        <div class="sidebar-canvas-head">
            <div class="sidebar-canvas-logo">
                {!! Theme::partial('logo') !!}
            </div>
            <div class="sidebar-canvas-lang">
                <a class="close-canvas" href="#" title="{{ __('Close') }}"> <img alt="{{ __('Close') }}" src="{{ Theme::asset()->url('images/icons/close.png') }}" /></a>
            </div>
        </div>

        <div class="sidebar-canvas-content">
            {!! dynamic_sidebar('off_canvas_sidebar') !!}
        </div>
    </div>
</div>
