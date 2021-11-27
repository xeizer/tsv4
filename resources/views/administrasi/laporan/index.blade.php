{{-- }}@extends('layouts.theme2')
@section('isi')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                ini
            </div>
        </div>
    </div>
</div>
@endsection
{{ --}}
<table>
    <thead>
        <tr>
            <th>kdptimsmh</th>
            <th>kdpstmsmh</th>
            <th>nimhsmsmh</th>
            <th>nmmhsmsmh</th>
            <th>telpomsmh</th>
            <th>emailmsmh</th>
            <th>tahun_lulus</th>
            <th>f21</th>
            <th>f22</th>
            <th>f23</th>
            <th>f24</th>
            <th>f25</th>
            <th>f26</th>
            <th>f27</th>
            <th>f301</th>
            <th>f302 </th>
            <th>f303 </th>
            <th>f401</th>
            <th>f402</th>
            <th>f403</th>
            <th>f404</th>
            <th>f405</th>
            <th>f406</th>
            <th>f407</th>
            <th>f408</th>
            <th>f409</th>
            <th>f410</th>
            <th>f411</th>
            <th>f412</th>
            <th>f413</th>
            <th>f414</th>
            <th>f415</th>
            <th>f416</th>
            <th>f6 </th>
            <th>f501</th>
            <th>f502 </th>
            <th>f503 </th>
            <th>f7 </th>
            <th>f7a</th>
            <th>f8</th>
            <th>f901</th>
            <th>f902</th>
            <th>f903</th>
            <th>f904</th>
            <th>f905</th>
            <th>f906</th>
            <th>f1001</th>
            <th>f1002</th>
            <th>f1101</th>
            <th>f1102</th>
            <th>f1201</th>
            <th>f1202</th>
            <th>f1301 </th>
            <th>f1302 </th>
            <th>f1303 </th>
            <th>f14</th>
            <th>f15</th>
            <th>f1601</th>
            <th>f1602</th>
            <th>f1603</th>
            <th>f1604</th>
            <th>f1605</th>
            <th>f1606</th>
            <th>f1607</th>
            <th>f1608</th>
            <th>f1609</th>
            <th>f1610</th>
            <th>f1611</th>
            <th>f1612</th>
            <th>f1613</th>
            <th>f1614</th>
            <th>f1701</th>
            <th>f1702b</th>
            <th>f1703</th>
            <th>f1704b</th>
            <th>f1705</th>
            <th>f1705a</th>
            <th>f1706</th>
            <th>f1706ba</th>
            <th>f1707</th>
            <th>f1708b</th>
            <th>f1709</th>
            <th>f1710b</th>
            <th>f1711</th>
            <th>f1711a</th>
            <th>f1712b</th>
            <th>f1712a</th>
            <th>f1713</th>
            <th>f1714b</th>
            <th>f1715</th>
            <th>f1716b</th>
            <th>f1717</th>
            <th>f1718b</th>
            <th>f1719</th>
            <th>f1720b</th>
            <th>f1721</th>
            <th>f1722b</th>
            <th>f1723</th>
            <th>f1724b</th>
            <th>f1725</th>
            <th>f1726b</th>
            <th>f1727</th>
            <th>f1728b</th>
            <th>f1729</th>
            <th>f1730b</th>
            <th>f1731</th>
            <th>f1732b</th>
            <th>f1733</th>
            <th>f1734b</th>
            <th>f1735</th>
            <th>f1736b</th>
            <th>f1737</th>
            <th>f1737a</th>
            <th>f1738</th>
            <th>f1738ba</th>
            <th>f1739</th>
            <th>f1740b</th>
            <th>f1741</th>
            <th>f1742b</th>
            <th>f1743</th>
            <th>f1744b</th>
            <th>f1745</th>
            <th>f1746b</th>
            <th>f1747</th>
            <th>f1748b</th>
            <th>f1749</th>
            <th>f1750b</th>
            <th>f1751</th>
            <th>f1752b</th>
            <th>f1753</th>
            <th>f1754b</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
            <tr>
                <td>112002</td>
                <td>{{ $d->prodi->kd_prodi ?? '0' }}</td>
                <td>{{ $d->user->nim ?? '0' }}</td>
                <td>{{ $d->user->name ?? '0' }}</td>
                <td>{{ $d->user->tlp ?? '0' }}</td>
                <td>{{ $d->user->email ?? '0' }}</td>
                <td>{{ $d->tahun_lulus ?? '0' }}</td>
                <td>{{ $d->f2->f21 ?? '0' }}</td>
                <td>{{ $d->f2->f22 ?? '0' }}</td>
                <td>{{ $d->f2->f23 ?? '0' }}</td>
                <td>{{ $d->f2->f24 ?? '0' }}</td>
                <td>{{ $d->f2->f25 ?? '0' }}</td>
                <td>{{ $d->f2->f26 ?? '0' }}</td>
                <td>{{ $d->f2->f27 ?? '0' }}</td>
                <td>{{ $d->f3->f301 ?? '0' }}</td>
                <td>{{ $d->f3->f302 ?? '0' }}</td>
                <td>{{ $d->f3->f303 ?? '0' }}</td>
                <td>{{ $d->f4->f41 ?? '0' }}</td>
                <td>{{ $d->f4->f42 ?? '0' }}</td>
                <td>{{ $d->f4->f43 ?? '0' }}</td>
                <td>{{ $d->f4->f44 ?? '0' }}</td>
                <td>{{ $d->f4->f45 ?? '0' }}</td>
                <td>{{ $d->f4->f46 ?? '0' }}</td>
                <td>{{ $d->f4->f47 ?? '0' }}</td>
                <td>{{ $d->f4->f48 ?? '0' }}</td>
                <td>{{ $d->f4->f49 ?? '0' }}</td>
                <td>{{ $d->f4->f410 ?? '0' }}</td>
                <td>{{ $d->f4->f411 ?? '0' }}</td>
                <td>{{ $d->f4->f412 ?? '0' }}</td>
                <td>{{ $d->f4->f413 ?? '0' }}</td>
                <td>{{ $d->f4->f414 ?? '0' }}</td>
                <td>{{ $d->f4->f415 ?? '0' }}</td>
                <td>{{ $d->f4->f416 ?? '0' }}</td>
                <td>{{ $d->f6->f6 ?? '0' }}</td>
                <td>{{ $d->f5->f501 ?? '0' }}</td>
                <td>{{ $d->f5->f502 ?? '0' }}</td>
                <td>{{ $d->f5->f503 ?? '0' }}</td>
                <td>{{ $d->f7->f7 ?? '0' }}</td>
                <td>{{ $d->f7a->f7a ?? '0' }}</td>
                <td>{{ $d->f8->f8 ?? '0' }}</td>
                <td>{{ $d->f9->f91 ?? '0' }}</td>
                <td>{{ $d->f9->f92 ?? '0' }}</td>
                <td>{{ $d->f9->f93 ?? '0' }}</td>
                <td>{{ $d->f9->f94 ?? '0' }}</td>
                <td>{{ $d->f9->f95 ?? '0' }}</td>
                <td>{{ $d->f9->f96 ?? '0' }}</td>
                <td>{{ $d->f10->f101 ?? '0' }}</td>
                <td>{{ $d->f10->f102 ?? '0' }}</td>
                <td>{{ $d->f11->f111 ?? '0' }}</td>
                <td>{{ $d->f11->f112 ?? '0' }}</td>
                <td>{{ $d->f12->f121 ?? '0' }}</td>
                <td>{{ $d->f12->f122 ?? '0' }}</td>
                <td>{{ $d->f13->f131 ?? '0' }}</td>
                <td>{{ $d->f13->f132 ?? '0' }}</td>
                <td>{{ $d->f13->f133 ?? '0' }}</td>
                <td>{{ $d->f14->f14 ?? '0' }}</td>
                <td>{{ $d->f15->f15 ?? '0' }}</td>
                <td>{{ $d->f16->f161 ?? '0' }}</td>
                <td>{{ $d->f16->f162 ?? '0' }}</td>
                <td>{{ $d->f16->f163 ?? '0' }}</td>
                <td>{{ $d->f16->f164 ?? '0' }}</td>
                <td>{{ $d->f16->f165 ?? '0' }}</td>
                <td>{{ $d->f16->f166 ?? '0' }}</td>
                <td>{{ $d->f16->f167 ?? '0' }}</td>
                <td>{{ $d->f16->f168 ?? '0' }}</td>
                <td>{{ $d->f16->f169 ?? '0' }}</td>
                <td>{{ $d->f16->f1610 ?? '0' }}</td>
                <td>{{ $d->f16->f1611 ?? '0' }}</td>
                <td>{{ $d->f16->f1612 ?? '0' }}</td>
                <td>{{ $d->f16->f1613 ?? '0' }}</td>
                <td>{{ $d->f16->f1614 ?? '0' }}</td>
                <td>{{ $d->f17a->f171 ?? '0' }}</td>
                <td>{{ $d->f17b->f172b ?? '0' }}</td>
                <td>{{ $d->f17a->f173 ?? '0' }}</td>
                <td>{{ $d->f17b->f174b ?? '0' }}</td>
                <td>{{ $d->f17a->f175 ?? '0' }}</td>
                <td>{{ $d->f17a->f175a ?? '0' }}</td>
                <td>{{ $d->f17b->f176b ?? '0' }}</td>
                <td>{{ $d->f17b->f176ba ?? '0' }}</td>
                <td>{{ $d->f17a->f177 ?? '0' }}</td>
                <td>{{ $d->f17b->f178b ?? '0' }}</td>
                <td>{{ $d->f17a->f179 ?? '0' }}</td>
                <td>{{ $d->f17b->f1710b ?? '0' }}</td>
                <td>{{ $d->f17a->f1711 ?? '0' }}</td>
                <td>{{ $d->f17a->f1711a ?? '0' }}</td>
                <td>{{ $d->f17b->f1712b ?? '0' }}</td>
                <td>{{ $d->f17a->f1712a ?? '0' }}</td>
                <td>{{ $d->f17a->f1713 ?? '0' }}</td>
                <td>{{ $d->f17b->f1714b ?? '0' }}</td>
                <td>{{ $d->f17a->f1715 ?? '0' }}</td>
                <td>{{ $d->f17b->f1716b ?? '0' }}</td>
                <td>{{ $d->f17a->f1717 ?? '0' }}</td>
                <td>{{ $d->f17b->f1718b ?? '0' }}</td>
                <td>{{ $d->f17a->f1719 ?? '0' }}</td>
                <td>{{ $d->f17b->f1720b ?? '0' }}</td>
                <td>{{ $d->f17a->f1721 ?? '0' }}</td>
                <td>{{ $d->f17b->f1722b ?? '0' }}</td>
                <td>{{ $d->f17a->f1723 ?? '0' }}</td>
                <td>{{ $d->f17b->f1724b ?? '0' }}</td>
                <td>{{ $d->f17a->f1725 ?? '0' }}</td>
                <td>{{ $d->f17b->f1726b ?? '0' }}</td>
                <td>{{ $d->f17a->f1727 ?? '0' }}</td>
                <td>{{ $d->f17b->f1728b ?? '0' }}</td>
                <td>{{ $d->f17a->f1729 ?? '0' }}</td>
                <td>{{ $d->f17b->f1730b ?? '0' }}</td>
                <td>{{ $d->f17a->f1731 ?? '0' }}</td>
                <td>{{ $d->f17b->f1732b ?? '0' }}</td>
                <td>{{ $d->f17a->f1733 ?? '0' }}</td>
                <td>{{ $d->f17b->f1734b ?? '0' }}</td>
                <td>{{ $d->f17a->f1735 ?? '0' }}</td>
                <td>{{ $d->f17b->f1736b ?? '0' }}</td>
                <td>{{ $d->f17a->f1737 ?? '0' }}</td>
                <td>{{ $d->f17a->f1737a ?? '0' }}</td>
                <td>{{ $d->f17b->f1738b ?? '0' }}</td>
                <td>{{ $d->f17b->f1738ba ?? '0' }}</td>
                <td>{{ $d->f17a->f1739 ?? '0' }}</td>
                <td>{{ $d->f17b->f1740b ?? '0' }}</td>
                <td>{{ $d->f17a->f1741 ?? '0' }}</td>
                <td>{{ $d->f17b->f1742b ?? '0' }}</td>
                <td>{{ $d->f17a->f1743 ?? '0' }}</td>
                <td>{{ $d->f17b->f1744b ?? '0' }}</td>
                <td>{{ $d->f17a->f1745 ?? '0' }}</td>
                <td>{{ $d->f17b->f1746b ?? '0' }}</td>
                <td>{{ $d->f17a->f1747 ?? '0' }}</td>
                <td>{{ $d->f17b->f1748b ?? '0' }}</td>
                <td>{{ $d->f17a->f1749 ?? '0' }}</td>
                <td>{{ $d->f17b->f1750b ?? '0' }}</td>
                <td>{{ $d->f17a->f1751 ?? '0' }}</td>
                <td>{{ $d->f17b->f1752b ?? '0' }}</td>
                <td>{{ $d->f17a->f1753 ?? '0' }}</td>
                <td>{{ $d->f17b->f1754b ?? '0' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
