<x-layout>
    <div class="container container--narrow p-4">
        <h2>Upload a New Avatar</h2>
        <form action="/upload-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="avatar" id="avatar" required>
            @error('avatar')
            <p class="text-danger fs-6">{{$message}}</p>
            @enderror
            <button type="submit" class="btn btn-primary text-sm">Submit</button>
        </form>
    </div>
</x-layout>