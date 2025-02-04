@extends('layouts.main')
@section('page-title')
    {{ __('Zoom Meeting') }}
@endsection
@section('page-breadcrumb')
    {{ __('Zoom Meeting') }}
@endsection

@section('page-action')
    <div class="float-end">
        <a href="{{ route('zoom-meeting.index') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            title="{{ __('List View') }}" data-original-title="{{ __('List View') }}">
            <i class="ti ti-list"></i>
        </a>
        @permission('zoommeeting create')
            <a href="#" data-size="lg" data-url="{{ route('zoom-meeting.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Meeting') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/ZoomMeeting/Resources/assets/css/main.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>{{ __('Calendar') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar' data-toggle="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ __('Mettings') }}</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        @foreach ($calandar as $event)
                            @php
                                $month = date('m', strtotime($event['start']));
                            @endphp
                            @if ($month == date('m'))
                                <li class="list-group-item card mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-video"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="m-0">
                                                        <a href="{{ $event['url'] }}" class="fc-daygrid-event"
                                                            style="white-space: inherit;">
                                                            <div class="fc-event-title-container">
                                                                <div class="fc-event-title text-dark">{{ $event['title'] }}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">{{ $event['start'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('Modules/ZoomMeeting/Resources/assets/js/main.min.js') }}"></script>

<script type="text/javascript">
    (function() {
        var etitle;
        var etype;
        var etypeclass;
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                timeGridDay: "{{ __('Day') }}",
                timeGridWeek: "{{ __('Week') }}",
                dayGridMonth: "{{ __('Month') }}"
            },
            themeSystem: 'bootstrap',
            slotDuration: '00:10:00',
            navLinks: true,
            droppable: true,
            selectable: true,
            selectMirror: true,
            editable: true,
            dayMaxEvents: true,
            handleWindowResize: true,
            events: {!! json_encode($calandar) !!},
        });
        calendar.render();
    })();
</script>
<script>
    $(document).on('click', '.fc-daygrid-event', function(e) {
        if ($(this).attr('href') != undefined) {
            if (!$(this).hasClass('deal')) {

                e.preventDefault();
                var event = $(this);
                var title = $(this).find('.fc-event-title-container .fc-event-title').html();

                var size = 'md';
                var url = $(this).attr('href');
                var parts = url.split("/");

                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $.ajax({
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#commonModal .body').html(data);
                        $("#commonModal").modal('show');
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        toastr('Error', data.error, 'error')
                    }
                });
            }
        }
    });
</script>
@endpush
