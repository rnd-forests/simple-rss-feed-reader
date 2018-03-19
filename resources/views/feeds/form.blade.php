<div class="card mb-3">
    <form method="POST" action="{{ $form->url }}">
        @csrf
        @method($form->method)

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">RSS Title</label>
                    <input
                        type="text"
                        class="form-control {{ count($errors) ? 'is-invalid' : '' }}"
                        id="title"
                        name="title"
                        value="{{ $form->value('title') }}"
                        required
                        autofocus>
                    @include('layouts.form.input_error', ['key' => 'title'])
                </div>

                <div class="form-group col-md-6">
                    <label for="url">RSS URL</label>
                    <input
                        type="text"
                        class="form-control {{ count($errors) ? 'is-invalid' : '' }}"
                        id="url"
                        name="url"
                        value="{{ $form->value('url') }}"
                        placeholder="https://example.com/rss"
                        required>
                    @include('layouts.form.input_error', ['key' => 'url'])
                </div>
            </div>
            <div class="d-flex flex-row justify-content-end">
                <button type="submit" class="btn btn-primary mr-1">{{ $submitBtn }}</button>
                <a href="{{ $refreshUrl }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
