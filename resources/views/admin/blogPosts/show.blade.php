@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.blogPost.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.blog-posts.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.id') }}
                        </th>
                        <td>
                            {{ $blogPost->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.title') }}
                        </th>
                        <td>
                            {{ $blogPost->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.category') }}
                        </th>
                        <td>
                            @foreach($blogPost->categories as $key => $category)
                                <span class="label label-info">{{ $category->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.tag') }}
                        </th>
                        <td>
                            @foreach($blogPost->tags as $key => $tag)
                                <span class="label label-info">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.page_text') }}
                        </th>
                        <td>
                            {!! $blogPost->page_text !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.excerpt') }}
                        </th>
                        <td>
                            {{ $blogPost->excerpt }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.featured_image') }}
                        </th>
                        <td>
                            @if($blogPost->featured_image)
                                <a href="{{ $blogPost->featured_image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $blogPost->featured_image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $blogPost->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $blogPost->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.blogPost.fields.meta_keywords') }}
                        </th>
                        <td>
                            {{ $blogPost->meta_keywords }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.blog-posts.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>



@endsection
