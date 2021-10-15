<?php
$chunks = $data_provider->getDataChunks($page_size);
$move_routes = $data_provider->constructNavigationRoutes($page_size);
?>

<div class="simple-grid-view-container">
    <table class="{{ $table_class ?? '' }}">
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column["label"] }}</th>
            @endforeach
        </tr>
        @if (count( $chunks[ $data_provider->getCurrentPage() ] ?? []) )
            @foreach ($chunks[ $data_provider->getCurrentPage() ] as $row)
                <tr>
                    @foreach ($columns as $column)
                        <td class='{{ $column["column_class"] ?? '' }}'>

                            @if ($column["type"]==="value")
                                {{ $row[$column["name"]] }}
                            @elseif ($column["type"]==="link")
                                <a href="/">Перейти</a>
                            @elseif ($column["type"]==="code")
                                <?php echo $column["code"]($row[$column["name"]]) ?>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{count($columns)}}">
                    <h3>Данные отсутствуют</h3>
                </td>
            </tr>
        @endif
    </table>
    <div class="{{ $pagination_class ?? "" }}">
        @if ($move_routes["previous"])
            <a href="{{ route($name_basic_route) . $move_routes["previous"] }}" class="simple-grid-view-previous-button">&laquo</a>
        @endif
        @if ($move_routes["next"])
            <a href="{{ route($name_basic_route) . $move_routes["next"] }}" class="simple-grid-view-next-button">&raquo</a>
        @endif
    </div>
</div>
