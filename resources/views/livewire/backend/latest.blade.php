<div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Latest Posts</h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('admin.posts.show', $post->id) }}">
                                            {{ Str::limit($post->title, 30, '...') }}
                                        </a>
                                    </td>
                                    <td>{{ $post->comments_count }}</td>
                                    <td>{{ $post->status }}</td>
                                    <td>{{ $post->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Posts Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Latest Comments</h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    <td>{{ Str::limit($comment->name, 25, '...') }}</td>
                                    <td>{{ Str::limit($comment->comment, 25, '...') }}</td>
                                    <td>{{ $comment->status }}</td>
                                    <td>{{ $comment->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Comments Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
