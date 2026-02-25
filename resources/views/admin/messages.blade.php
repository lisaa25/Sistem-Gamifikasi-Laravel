@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Pesan dari User</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr>
                    <td>{{ $message->id_kontak }}</td>
                    <td>{{ $message->nama }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->pesan }}</td>
                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn-reply" data-id="{{ $message->id_kontak }}" data-email="{{ $message->email }}" title="Balas">
                            <i class="fas fa-reply"></i>
                        </button>
                        <form action="{{ route('admin.messages.delete', $message->id_kontak) }}" method="POST" style="display:inline;" title="Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal untuk form balasan -->
<div id="reply-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="{{ route('admin.messages.reply') }}" method="POST">
            @csrf
            <input type="hidden" id="message_id" name="message_id">
            <label for="reply_message">Balas Pesan</label>
            <textarea id="reply_message" name="reply_message" rows="4" required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/admin/messages.css') }}">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reply-modal');
    const span = document.getElementsByClassName('close')[0];
    const message_id_input = document.getElementById('message_id');
    const reply_buttons = document.getElementsByClassName('btn-reply');

    Array.from(reply_buttons).forEach(button => {
        button.addEventListener('click', function() {
            message_id_input.value = this.getAttribute('data-id');
            modal.style.display = 'block';
        });
    });

    span.onclick = function() {
        modal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});
</script>
@endsection
