<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('title') </title>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        @vite(['resources/css/app.css'])
        @stack('css')
    </head>
    <body class="bg-gray-100 text-gray-800">
        @include('shared.navbar')

        {{-- Main content area --}}
        <div class="pt-14">
            <div class="mx-auto max-w-7xl p-4 lg:p-6">
                @yield('content')
            </div>
        </div>

        @foreach ($errors->all() as $error)
            <p class="alert alert-danger">{{ $error }}</p>
        @endforeach
        @include('flash::message')
        @stack('scripts')

        {{-- Bootstrap modal JS shim (replaces Bootstrap JS for modal functionality) --}}
        <script>
            (function() {
                function openModal(el) {
                    el.style.display = 'flex';
                    el.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    setTimeout(function() { el.dispatchEvent(new Event('shown.bs.modal')); }, 50);
                }
                function closeModal(el) {
                    el.style.display = 'none';
                    el.classList.remove('show');
                    document.body.style.overflow = '';
                }

                document.addEventListener('click', function(e) {
                    // data-toggle="modal" opens a modal
                    var trigger = e.target.closest('[data-toggle="modal"]');
                    if (trigger) {
                        var target = document.querySelector(trigger.getAttribute('data-target'));
                        if (target) openModal(target);
                    }
                    // data-dismiss="modal" closes the parent modal
                    var dismiss = e.target.closest('[data-dismiss="modal"]');
                    if (dismiss) {
                        var modal = dismiss.closest('.modal');
                        if (modal) closeModal(modal);
                    }
                    // Click on backdrop closes the modal
                    if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
                        closeModal(e.target);
                    }
                });

                // jQuery .modal('show') / .modal('hide') shim
                if (window.jQuery) {
                    jQuery.fn.modal = function(action) {
                        return this.each(function() {
                            if (action === 'show') openModal(this);
                            else if (action === 'hide') closeModal(this);
                        });
                    };
                }
            })();
        </script>
    </body>
</html>
