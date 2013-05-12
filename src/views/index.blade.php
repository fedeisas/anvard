
<div class="anvard">
    <h2>Login</h2>
    <ul>
    @foreach ($providers as $provider)
        <li>
            {{ link_to_route('anvard.routes.login', $provider, $provider) }}
        </li>
    @endforeach
    </ul>
</div>
