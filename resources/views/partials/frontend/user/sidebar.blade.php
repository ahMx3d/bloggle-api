<div class="wn__address">
    @if (!(in_array(Route::currentRouteName(),['frontend.profile.password.edit','user.comment.edit'])))
    <div class="mb-5 border border-dark rounded">
        <img
        class="img-fluid img-thumbnail"
        src="{{ (auth()->user()->user_image)? asset('assets/users/'.auth()->user()->user_image): asset('assets/users/user.jpeg') }}"
        alt="{{ Str::limit(auth()->user()->name, 10, '...') }}" />
        <h2 class="contact__title mb-0 text-center">{{ auth()->user()->name }}</h2>
        <p class="mb-2 text-center">{{ Str::limit(auth()->user()->bio, 25, '...') }}</p>
    </div>
    @endif

    <div class="wn__addres__wreapper">
        <h3 class="wedget__title text-center">Management Links</h3>
        @if (!(in_array(Route::currentRouteName(),['frontend.profile.password.edit','user.comment.edit'])))
        <div class="single__address">
            <i class="icon-trash icons"></i>
            <div class="content">
                <span>
                    <a
                        onclick="
                            event.preventDefault();
                            document.getElementById('profile-image-delete').submit();"
                        href="javascript:void(0);">
                        Remove Profile Image</a>
                    <form
                        action="{{ route('user.image.delete', auth()->user()->username) }}"
                        method="POST"
                        id="profile-image-delete"
                        class="d-none">
                        @csrf
                        @method('PATCH')
                    </form>
                </span>
                <p>Leave it for better appearance</p>
            </div>
        </div>
        @endif

        <div class="single__address">
            <i class="icon-pencil icons"></i>
            <div class="content">
                <span>
                    <a
                    href="{{ route('user.posts.create') }}">
                    Create Post</a>
                </span>
                <p>Show more about your thoughts</p>
            </div>
        </div>

        <div class="single__address">
            <i class="icon-paper-clip icons"></i>
            <div class="content">
                <span>
                    <a
                    href="{{ route('frontend.profile', auth()->user()->username) }}">
                    Posts Board</a>
                </span>
                <p>Manage your posts</p>
            </div>
        </div>

        <div class="single__address">
            <i class="icon-tag icons"></i>
            <div class="content">
                <span>
                    <a
                    href="{{ route('user.comments.index') }}">
                    Comments Board</a>
                </span>
                <p>Manage your comments</p>
            </div>
        </div>

        <div class="single__address">
            <i class="icon-lock icons"></i>
            <div class="content">
                <span>
                    <a
                    href="{{ route('frontend.profile.password.update', auth()->user()->username) }}">
                    Change Password</a>
                </span>
                <p>Secure yourself</p>
            </div>
        </div>

        <div class="single__address">
            <i class="icon-info icons"></i>
            <div class="content">
                <span>
                    <a
                    href="{{ route('frontend.profile.info.edit', auth()->user()->username) }}">
                    Update Information</a>
                </span>
                <p>Let people know you the right way</p>
            </div>
        </div>

        <div class="single__address">
            <i class="icon-power icons"></i>
            <div class="content">
                <span>
                    <a
                        href="{{ route('frontend.logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                Logout
                    </a>
                </span>
                <p>Güle güle, Waiting you again</p>
            </div>
        </div>

    </div>
</div>


{{-- <div class="wn__sidebar">
    <!-- Start Single Widget -->
    <aside class="widget recent_widget">
        <ul>
            <li class="list-group-item">
                <img
                    src="{{ (auth()->user()->user_image)? asset('assets/users/'.auth()->user()->user_image): asset('assets/users/user.jpeg') }}"
                    alt="{{ Str::limit(auth()->user()->name, 10, '...') }}" />
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('user.posts.create') }}">
                    Create Post</a>
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('frontend.profile', auth()->user()->username) }}">
                    Posts Board</a>
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('user.comments.index') }}">
                    Comments Board</a>
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('frontend.profile.password.update', auth()->user()->username) }}">
                    Change Password</a>
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('frontend.profile.info.edit', auth()->user()->username) }}">
                    Update Information</a>
            </li>
            <li class="list-group-item">
                <a
                    href="{{ route('frontend.logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                </a>
            </li>
        </ul>
    </aside>
</div> --}}
