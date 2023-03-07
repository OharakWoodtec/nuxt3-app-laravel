<html>
<table border="1" >
    <tr style="text-align: center;">
        <th style="width: 250px;">項目名</th>
        <th style="width: 110px;">サイズ</th>
    </tr>
    @foreach ($datas as $data)
        <tr style="text-align: center;">
        <td >{{$data['項目名']}}</td>
        <td >{{$data['サイズ']}}</td>
    </tr>
    @endforeach
</table>
</html>