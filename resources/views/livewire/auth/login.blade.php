@section('page-script')
  @vite(['resources/js/show-password.js'])
@endsection

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center mt-2">
      <h2 class="font-weight-bold">Login Page</h2>
    </div>

    <div class="card-body">
      @if (session()->has('error'))
        <x-alert.danger dismissible="true"><span style="font-size: 13px;">{{ session('error') }}</span></x-alert.danger>
      @endif

      <form wire:submit.prevent="doLogin">
        <x-form.input-group
          prependText="<span class='fas fa-user'></span>"
          name="username"
          placeholder="Username"
          parentClass="mb-3"
          wire:model.defer="username"
        />

        <x-form.input-group
          prependText="<span class='fas fa-lock'></span>"
          name="password"
          type="password"
          placeholder="Password"
          parentClass="mb-3"
          customClass="toggle-password"
          wire:model.defer="password"
        />

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="show-pwd">
              <label for="show-pwd">Lihat Password</label>
            </div>
          </div>

          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-login">Masuk</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
