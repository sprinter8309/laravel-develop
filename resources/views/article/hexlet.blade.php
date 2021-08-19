<!-- 1 Hexlet Код -->
<table>
    @foreach ($articles as $article)
        <tr>
            <td>{{ $article->name }}</td>
            <td>{{ $article->body }}</td>
            <td><a href="{{ route('articles.destroy', $article) }}" data-method="delete" rel="nofollow">Delete</a></td>
        </tr>
    @endforeach
</table>
