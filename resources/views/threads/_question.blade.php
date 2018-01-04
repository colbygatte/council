{{-- Editing the question. --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input class="form-control" type="text" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            {{----------------------
            Here is where I had a question about the wysiwyg component's "value" prop. Since this is v-model,
            the value of the component will be equal to form.body, and in order to get that value on the component, you must accept the "value" prop.
            In this case, it is being accepted on the wysiwyg component and then being passed to the <input>, where the Trix editor will read it.
            ------------------------}}
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>

    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary level-item" @click="update">Update</button>
            <button class="btn btn-xs" @click="resetForm">Cancel</button>

            @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

<div class="panel panel-default" v-if="! editing">
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}"
                 alt="{{ $thread->creator->name }}"
                 width="25"
                 height="25"
                 class="mr-1">

            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                posted:
                <span v-text="title"></span>
            </span>
        </div>
    </div>

    <div class="panel-body" v-html="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>
