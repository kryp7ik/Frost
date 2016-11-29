@foreach($announcements['sticky'] as $announcement)
    <div class="well">
        <div class="media">
            <div class="pull-left" href="#">
                <i class="fa fa-thumb-tack fa-2x" aria-hidden="true"></i>
            </div>
            <div class="media-body">
                <h3 class="media-heading">
                    {{ $announcement->title }}
                    <small class="text-right">
                        By {{ $announcement->user->name }}
                        @if(Auth::user()->id == $announcement->user_id || Auth::user()->hasRole('admin'))
                            <a href="/announcements/{{ $announcement->id }}/edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                        @endif
                    </small>
                    <small class="pull-right">
                        <span><i class="fa fa-calendar"></i>
                            {{ DateHelper::timeElapsed($announcement->created_at) }}
                        </span>
                    </small>
                </h3>
                <p>{!! str_limit(strip_tags($announcement->content), 600) !!}</p>
                <ul class="list-inline list-unstyled">
                    <li><span><i class="fa fa-calendar"></i> {{ DateHelper::timeElapsed($announcement->created_at) }} </span></li>
                    <li>|</li>
                    <span>
                        <button data-id="{{ $announcement->id }}" class="read-more">
                            <i class="glyphicon glyphicon-comment"></i> {{ $announcement->comments->count() }} comments
                        </button>
                    </span>
                    <li>|</li>
                    <li>
                        <button data-id="{{ $announcement->id }}" class="read-more">Read More...</button>
                    </li>

                </ul>
            </div>
        </div>
    </div>
@endforeach

@foreach($announcements['standard'] as $announcement)
    <div class="well">
        <div class="media">
            <div class="pull-left" href="#">
                <i class="{{ config('store.announcement_types')[$announcement->type]['icon'] }}" aria-hidden="true"></i>
            </div>
            <div class="media-body">
                <h3 class="media-heading">
                    {{ $announcement->title }}
                    <small class="text-right">
                        By {{ $announcement->user->name }}
                        @if(Auth::user()->id == $announcement->user_id || Auth::user()->hasRole('admin'))
                            <a href="/announcements/{{ $announcement->id }}/edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                        @endif
                    </small>

                </h3>
                <p>{!! str_limit(strip_tags($announcement->content), 600) !!}</p>
                <ul class="list-inline list-unstyled">
                    <li><span><i class="fa fa-calendar"></i> {{ DateHelper::timeElapsed($announcement->created_at) }} </span></li>
                    <li>|</li>
                    <span>
                        <button data-id="{{ $announcement->id }}" class="read-more">
                            <i class="glyphicon glyphicon-comment"></i> {{ $announcement->comments->count() }} comments
                        </button>
                    </span>
                    <li>|</li>
                    <li>
                        <button data-id="{{ $announcement->id }}" class="read-more">Read More...</button>
                    </li>

                </ul>
            </div>
        </div>
    </div>
@endforeach
<div class="well">
    {{ $announcements['standard']->links() }}
</div>