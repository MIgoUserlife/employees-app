<a href="{{ route("$model.show", $row->id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
<x-adminlte-button
    icon="fas fa-trash-alt"
    data-toggle="modal"
    data-target="#modalAcceptDelete{{$row->id}}"
    theme="danger" />

<x-adminlte-modal
    id="modalAcceptDelete{{$row->id}}"
    title="Remove {{ $modal_text }}"
    v-centered
>
    Are you sure you want to remove {{ $modal_text }} {{ $row->name }}?

    <x-slot name="footerSlot">
        <x-adminlte-button theme="default" label="Cancel" data-dismiss="modal" />
        <form action="{{ route("$model.destroy", $row->id) }}" method="post">
            @csrf
            @method('delete')
            <x-adminlte-button type="submit" theme="danger" label="Remove" />
        </form>
    </x-slot>
</x-adminlte-modal>

