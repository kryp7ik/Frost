
<div class="raven">
    <ul class="user-list">
        @foreach($users as $userData)
            <li>
                {{ $userData['user']['id'] . ' - ' . $userData['user']['name'] }} - {{ ($userData['user']['online']) ? 'online' : 'offline' }}<br/>
                {{ $userData['conversation']['id'] }} {{ ($userData['conversation']['new']) ? 'New' : 'No New' }}
            </li>
        @endforeach
    </ul>
</div>