@extends('layouts.main')
@section('page-title')
    {{ __('Manage Projects') }}
@endsection
@section('page-breadcrumb')
    {{ __('Projects') }}
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/Taskly/src/Resources/assets/css/custom.css') }}" type="text/css" />
@endpush
@section('page-action')
    <div class="d-flex">
        @stack('project_template_button')
        @permission('project import')
            <a href="#" class="btn btn-sm btn-primary me-2" data-ajax-popup="true" data-title="{{ __('Project Import') }}"
                data-url="{{ route('project.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('projects.list') }}" class="btn btn-sm btn-primary me-2"
            data-bs-toggle="tooltip"title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @permission('project create')
            <a class="btn btn-sm btn-primary me-2" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Project') }}"
                data-url="{{ route('projects.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
        @permission('project export')
            <a href="#" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip" title="{{ __('Export Project') }}">
                <i class="ti ti-file-x"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <section class="section">
        <div class="row ">
            <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end">
                <div class="text-sm-right status-filter">
                    <div class="btn-group mb-3">
                        <button type="button" class="btn btn-light  text-white btn_tab  bg-primary active" data-filter="*"
                            data-status="All">{{ __('All') }}</button>
                        <button type="button" class="btn btn-light bg-primary text-white btn_tab"
                            data-filter=".Ongoing">{{ __('Ongoing') }}</button>
                        <button type="button" class="btn btn-light bg-primary text-white btn_tab"
                            data-filter=".Finished">{{ __('Finished') }}</button>
                        <button type="button" class="btn btn-light bg-primary text-white btn_tab"
                            data-filter=".OnHold">{{ __('OnHold') }}</button>
                    </div>
                </div>
            </div><!-- end col-->
        </div>

    <div id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['projects.index'], 'method' => 'GET', 'id' => 'project_submit']) }}
                <div class="row d-flex align-items-center justify-content-end">
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                        <div class="btn-box">
                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                            {{ Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : null, ['class' => 'form-control ','placeholder' => 'Select Date']) }}

                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                        <div class="btn-box">
                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                            {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : null, ['class' => 'form-control ','placeholder' => 'Select Date']) }}

                        </div>
                    </div>
                    <div class="col-auto float-end mt-4 d-flex">

                        <a href="#" class="btn btn-sm btn-primary me-2"
                            onclick="document.getElementById('project_submit').submit(); return false;"
                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                            data-original-title="{{ __('apply') }}">
                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                        </a>
                        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                            data-original-title="{{ __('Reset') }}">
                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                        </a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

        <div class="filters-content">
            <div class="row  d-flex grid">
                @isset($projects)
                    @foreach ($projects as $project)
                        <div class="col-md-6 col-xl-3 All  {{ $project->status }}">
                            <div class="card">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex align-items-center">
                                        @if ($project->is_active)
                                            <a href="@permission('project manage') {{ route('projects.show', [$project->id]) }} @endpermission"
                                                class="">
                                                <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img rounded border-2 border border-primary"
                                                    avatar="{{ $project->name }}">
                                            </a>
                                        @else
                                            <a href="#" class="">
                                                <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img rounded border-2 border border-primary"
                                                    avatar="{{ $project->name }}">
                                            </a>
                                        @endif

                                        <h5 class="mb-0">
                                            <a href="@permission('project manage') {{ route('projects.show', [$project->id]) }} @endpermission"
                                                title="{{ $project->name }}" class="">{{ $project->name }}</a>
                                        </h5>
                                    </div>
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @if ($project->is_active)
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                            @else
                                                <div class="btn">
                                                    <i class="ti ti-lock"></i>
                                                </div>
                                            @endif

                                            <div class="dropdown-menu dropdown-menu-end pointer">
                                                @permission('project invite user')
                                                    <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Invite Users') }}"
                                                        data-url="{{ route('projects.invite.popup', [$project->id]) }}">
                                                        <i class="ti ti-user-plus me-1"></i> <span>{{ __('Invite Users') }}</span>
                                                    </a>
                                                @endpermission
                                                @permission('project edit')
                                                    <a class="dropdown-item" data-ajax-popup="true" data-size="lg"
                                                        data-title="{{ __('Edit Project') }}"
                                                        data-url="{{ route('projects.edit', [$project->id]) }}">
                                                        <i class="ti ti-pencil me-1"></i> <span>{{ __('Edit') }}</span>
                                                    </a>
                                                @endpermission
                                                @permission('project manage')
                                                    <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Share to Clients') }}"
                                                        data-url="{{ route('projects.share.popup', [$project->id]) }}">
                                                        <i class="ti ti-share me-1"></i> <span>{{ __('Share to Clients') }}</span>
                                                    </a>
                                                @endpermission
                                                @permission('project create')
                                                    <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Duplicate Project') }}"
                                                        data-url="{{ route('project.copy', [$project->id]) }}">
                                                        <i class="ti ti-copy me-1"></i> <span>{{ __('Duplicate') }}</span>
                                                    </a>
                                                @endpermission
                                                @if (module_is_active('ProjectTemplate'))
                                                    @permission('project template create')
                                                        <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                            data-title="{{ __('Save As Template') }}"
                                                            data-url="{{ route('project-template.create', ['project_id' => $project->id, 'type' => 'template']) }}">
                                                            <i class="ti ti-bookmark me-1"></i>
                                                            <span>{{ __('Save as template') }}</span>
                                                        </a>
                                                    @endpermission
                                                @endif
                                                @permission('project delete')
                                                    <form id="delete-form-{{ $project->id }}"
                                                        action="{{ route('projects.destroy', [$project->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <a href="#"
                                                            class="dropdown-item text-danger delete-popup bs-pass-para show_confirm"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $project->id }}">
                                                            <i class="ti ti-trash me-1"></i> <span>{{ __('Delete') }}</span>
                                                        </a>
                                                        @method('DELETE')
                                                    </form>
                                                @endpermission
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2 justify-content-between">
                                        @if ($project->status == 'Finished')
                                            <div class="col-auto"><span
                                                    class="badge bg-success">{{ __('Finished') }}</span>
                                            </div>
                                        @elseif($project->status == 'Ongoing')
                                            <div class="col-auto"><span
                                                    class="badge bg-secondary">{{ __('Ongoing') }}</span>
                                            </div>
                                        @else
                                            <div class="col-auto"><span
                                                    class="badge bg-warning">{{ __('OnHold') }}</span>
                                            </div>
                                        @endif

                                        <div class="col-auto">
                                            <p class="mb-0"><b>{{ __('Due Date:') }}</b> {{ $project->end_date }}</p>
                                        </div>
                                    </div>
                                    <p class="text-muted text-sm mt-3">{{ $project->description }}</p>
                                    <h6 class="text-muted">{{ __('MEMBERS') }}</h6>
                                    <div class="row g-2 justify-content-between">
                                        <div class="col-auto">
                                            <div class="user-group mx-2">
                                                @foreach ($project->users as $user)
                                                    @if ($user->pivot->is_active)
                                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $user->name }}"
                                                            @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                            class="rounded-circle " width="25" height="25">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @if ($project->is_active)
                                        <div class="col-sm-auto">
                                            <a class="btn btn-sm btn-warning" data-bs-toggle="tooltip" href="{{ route('projects.show', [$project->id]) }}"
                                                data-bs-original-title="{{ __('View') }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary" data-bs-toggle="tooltip" href="{{ route('projects.task.board', [$project->id]) }}"
                                                data-bs-original-title="{{ __('Task Board') }}">
                                                <i class="ti ti-layout-kanban"></i>
                                            </a>
                                        </div>
                                        @endif

                                    </div>

                                    <div class="card mb-0 mt-3">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h6 class="mb-0">{{ $project->countTask() }}</h6>
                                                    <p class="text-muted text-sm mb-0">{{ __('Tasks') }}</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <h6 class="mb-0">{{ $project->countTaskComments() }}</h6>
                                                    <p class="text-muted text-sm mb-0">{{ __('Comments') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endisset


                @auth('web')
                    @permission('project create')
                        <div class="col-md-3 All Ongoing Finished OnHold">
                            <a href="#" class="btn-addnew-project border-primary" style="padding: 90px 10px;" data-ajax-popup="true"
                                data-size="md" data-title="{{ __('Create New Project') }}"
                                data-url="{{ route('projects.create') }}">
                                <div class="bg-primary proj-add-icon">
                                    <i class="ti ti-plus"></i>
                                </div>
                                <h6 class="mt-4 mb-2">{{ __('Add Project') }}</h6>
                                <p class="text-muted text-center">{{ __('Click here to add New Project') }}</p>
                            </a>
                        </div>
                    @endpermission
                @endauth

            </div>
        </div>

    </section>
@endsection



@push('scripts')
    <script src="{{ asset('packages/workdo/Taskly/src/Resources/assets/js/isotope.pkgd.min.js') }}"></script>

    <script src="{{ asset('js/letter.avatar.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('.status-filter button').click(function() {
                $('.status-filter button').removeClass('active');
                $(this).addClass('active');

                var data = $(this).attr('data-filter');
                $grid.isotope({
                    filter: data
                })
            });

            var $grid = $(".grid").isotope({
                itemSelector: ".All",
                percentPosition: true,
                masonry: {
                    columnWidth: ".All"
                }
            });
            // Check if the direction is RTL, then set right based on a repeating pattern
            if ($('html').attr('dir') === 'rtl') {
                var $allItems = $('.filters-content .All');
                $allItems.each(function(index) {
                    // Set right property based on a repeating pattern
                    $(this).css('right', (index % 4) * 25 + '%');
                });
            }
        });
    </script>
@endpush
