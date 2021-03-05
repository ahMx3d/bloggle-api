@extends('layouts.admin')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">{{ $page->title }}</h6>
        <div class="ml-auto">
            <a href="{{ route('admin.pages.index') }}" class="text-decoration-none">
                <span class="text">Pages Table</span>
                <span class="icon primary">
                    <i class="fa fa-home"></i>
                </span>
            </a>
        </div>
    </div>
    <div class="{{ ($page->media->count())? 'card-body': '' }}">
        @if ($page->media->count()>0)
            <div id="carouselIndicators" class="carousel slide post-thumbnail" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($page->media as $medium)
                        <li data-target="#carouselIndicators" data-slide-to="{{ $loop->index }}" class="{{ ($loop->index == 0)? 'active': ''}}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($page->media as $medium)
                        <div class="carousel-item {{ ($loop->index == 0)? 'active': ''}}">
                            <img
                                class="d-block w-100 img-fluid img-thumbnail"
                                style="height: 400px"
                                src="{{ asset("assets/posts/{$medium->file_name}") }}"
                                alt="{{ Str::limit($page->title, 10, '...') }}" />
                        </div>
                    @endforeach
                </div>
                @if ($page->media->count()>1)
                    <a class="carousel-control-prev" href="#carouselIndicators" role="button"
                        data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselIndicators" role="button"
                        data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                @endif
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <tbody>
                    <tr>
                        <th>Category</th>
                        <td>{{ $page->category->name }}</td>
                        <th>Status</th>
                        <td>{{ $page->status() }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td class="w-50">{!! $page->description !!}</td>
                        <th>Author</th>
                        <td>{{ $page->user->username }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $page->created_at->format('d-m-Y h:i A') }}</td>
                        <th>Updated</th>
                        <td>{{ $page->updated_at->diffForHumans() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
