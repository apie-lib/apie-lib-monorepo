@inject('selection', 'Apie\Common\Interfaces\BoundedContextSelection')
<p>
You are now viewing the CMS dashboard in the bounded context {{ $selection->getBoundedContextFromRequest()->getId()->toNative() }}, but you can also see the <a href="{{ url('apie.' . $selection->getBoundedContextFromRequest()->getId()->toNative() . '.swagger_ui') }}">Rest API generated Swagger UI page.</a>
</p>
<h2>Current settings:</h2>
<div>
  <a href="{{ route('apie.example.call-method-commit-PlaygroundConfiguration-resetConfiguration') }}">Reset config</a>
  <a href="{{ route('apie.example.call-method-commit-PlaygroundConfiguration-applyConfiguration') }}">Change config</a>

</div>
<p>
  {!! Apie\LaravelApie\Blade\ApieRender::renderApieCmsData(App\ApiePlayground\Example\Dtos\ApieConfiguration::createFromConfig()) !!}
</p>