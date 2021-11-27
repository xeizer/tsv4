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
                <td>{{ $d->prodi->kd_prodi ?? '' }}</td>
                <td>{{ $d->user->nim ?? '' }}</td>
                <td>{{ $d->user->name ?? '' }}</td>
                <td>{{ $d->user->tlp ?? '' }}</td>
                <td>{{ $d->user->email ?? '' }}</td>
                <td>{{ $d->tahun_lulus ?? '' }}</td>
                <td>{{ $d->f2->f21 ?? '' }}</td>
                <td>{{ $d->f2->f22 ?? '' }}</td>
                <td>{{ $d->f2->f23 ?? '' }}</td>
                <td>{{ $d->f2->f24 ?? '' }}</td>
                <td>{{ $d->f2->f25 ?? '' }}</td>
                <td>{{ $d->f2->f26 ?? '' }}</td>
                <td>{{ $d->f2->f27 ?? '' }}</td>
                <td>{{ $d->f3->f301 ?? '' }}</td>
                <td>{{ $d->f3->f302 ?? '' }}</td>
                <td>{{ $d->f3->f303 ?? '' }}</td>
                <td>{{ $d->f4->f41 ?? '' }}</td>
                <td>{{ $d->f4->f42 ?? '' }}</td>
                <td>{{ $d->f4->f43 ?? '' }}</td>
                <td>{{ $d->f4->f44 ?? '' }}</td>
                <td>{{ $d->f4->f45 ?? '' }}</td>
                <td>{{ $d->f4->f46 ?? '' }}</td>
                <td>{{ $d->f4->f47 ?? '' }}</td>
                <td>{{ $d->f4->f48 ?? '' }}</td>
                <td>{{ $d->f4->f49 ?? '' }}</td>
                <td>{{ $d->f4->f410 ?? '' }}</td>
                <td>{{ $d->f4->f411 ?? '' }}</td>
                <td>{{ $d->f4->f412 ?? '' }}</td>
                <td>{{ $d->f4->f413 ?? '' }}</td>
                <td>{{ $d->f4->f414 ?? '' }}</td>
                <td>{{ $d->f4->f415 ?? '' }}</td>
                <td>{{ $d->f4->f416 ?? '' }}</td>
                <td>{{ $d->f6->f6 ?? '' }}</td>
                <td>{{ $d->f5->f501 ?? '' }}</td>
                <td>{{ $d->f5->f502 ?? '' }}</td>
                <td>{{ $d->f5->f503 ?? '' }}</td>
                <td>{{ $d->f7->f7 ?? '' }}</td>
                <td>{{ $d->f7a->f7a ?? '' }}</td>
                <td>{{ $d->f8->f8 ?? '' }}</td>
                <td>{{ $d->f9->f91 ?? '' }}</td>
                <td>{{ $d->f9->f92 ?? '' }}</td>
                <td>{{ $d->f9->f93 ?? '' }}</td>
                <td>{{ $d->f9->f94 ?? '' }}</td>
                <td>{{ $d->f9->f95 ?? '' }}</td>
                <td>{{ $d->f9->f96 ?? '' }}</td>
                <td>{{ $d->f10->f101 ?? '' }}</td>
                <td>{{ $d->f10->f102 ?? '' }}</td>
                <td>{{ $d->f11->f111 ?? '' }}</td>
                <td>{{ $d->f11->f112 ?? '' }}</td>
                <td>{{ $d->f12->f121 ?? '' }}</td>
                <td>{{ $d->f12->f122 ?? '' }}</td>
                <td>{{ $d->f13->f131 ?? '' }}</td>
                <td>{{ $d->f13->f132 ?? '' }}</td>
                <td>{{ $d->f13->f133 ?? '' }}</td>
                <td>{{ $d->f14->f14 ?? '' }}</td>
                <td>{{ $d->f15->f15 ?? '' }}</td>
                <td>{{ $d->f16->f161 ?? '' }}</td>
                <td>{{ $d->f16->f162 ?? '' }}</td>
                <td>{{ $d->f16->f163 ?? '' }}</td>
                <td>{{ $d->f16->f164 ?? '' }}</td>
                <td>{{ $d->f16->f165 ?? '' }}</td>
                <td>{{ $d->f16->f166 ?? '' }}</td>
                <td>{{ $d->f16->f167 ?? '' }}</td>
                <td>{{ $d->f16->f168 ?? '' }}</td>
                <td>{{ $d->f16->f169 ?? '' }}</td>
                <td>{{ $d->f16->f1610 ?? '' }}</td>
                <td>{{ $d->f16->f1611 ?? '' }}</td>
                <td>{{ $d->f16->f1612 ?? '' }}</td>
                <td>{{ $d->f16->f1613 ?? '' }}</td>
                <td>{{ $d->f16->f1614 ?? '' }}</td>
                <td>{{ $d->f17a->f171 ?? '' }}</td>
                <td>{{ $d->f17b->f172b ?? '' }}</td>
                <td>{{ $d->f17a->f173 ?? '' }}</td>
                <td>{{ $d->f17b->f174b ?? '' }}</td>
                <td>{{ $d->f17a->f175 ?? '' }}</td>
                <td>{{ $d->f17a->f175a ?? '' }}</td>
                <td>{{ $d->f17b->f176b ?? '' }}</td>
                <td>{{ $d->f17b->f176ba ?? '' }}</td>
                <td>{{ $d->f17a->f177 ?? '' }}</td>
                <td>{{ $d->f17b->f178b ?? '' }}</td>
                <td>{{ $d->f17a->f179 ?? '' }}</td>
                <td>{{ $d->f17b->f1710b ?? '' }}</td>
                <td>{{ $d->f17a->f1711 ?? '' }}</td>
                <td>{{ $d->f17a->f1711a ?? '' }}</td>
                <td>{{ $d->f17b->f1712b ?? '' }}</td>
                <td>{{ $d->f17a->f1712a ?? '' }}</td>
                <td>{{ $d->f17a->f1713 ?? '' }}</td>
                <td>{{ $d->f17b->f1714b ?? '' }}</td>
                <td>{{ $d->f17a->f1715 ?? '' }}</td>
                <td>{{ $d->f17b->f1716b ?? '' }}</td>
                <td>{{ $d->f17a->f1717 ?? '' }}</td>
                <td>{{ $d->f17b->f1718b ?? '' }}</td>
                <td>{{ $d->f17a->f1719 ?? '' }}</td>
                <td>{{ $d->f17b->f1720b ?? '' }}</td>
                <td>{{ $d->f17a->f1721 ?? '' }}</td>
                <td>{{ $d->f17b->f1722b ?? '' }}</td>
                <td>{{ $d->f17a->f1723 ?? '' }}</td>
                <td>{{ $d->f17b->f1724b ?? '' }}</td>
                <td>{{ $d->f17a->f1725 ?? '' }}</td>
                <td>{{ $d->f17b->f1726b ?? '' }}</td>
                <td>{{ $d->f17a->f1727 ?? '' }}</td>
                <td>{{ $d->f17b->f1728b ?? '' }}</td>
                <td>{{ $d->f17a->f1729 ?? '' }}</td>
                <td>{{ $d->f17b->f1730b ?? '' }}</td>
                <td>{{ $d->f17a->f1731 ?? '' }}</td>
                <td>{{ $d->f17b->f1732b ?? '' }}</td>
                <td>{{ $d->f17a->f1733 ?? '' }}</td>
                <td>{{ $d->f17b->f1734b ?? '' }}</td>
                <td>{{ $d->f17a->f1735 ?? '' }}</td>
                <td>{{ $d->f17b->f1736b ?? '' }}</td>
                <td>{{ $d->f17a->f1737 ?? '' }}</td>
                <td>{{ $d->f17a->f1737a ?? '' }}</td>
                <td>{{ $d->f17b->f1738b ?? '' }}</td>
                <td>{{ $d->f17b->f1738ba ?? '' }}</td>
                <td>{{ $d->f17a->f1739 ?? '' }}</td>
                <td>{{ $d->f17b->f1740b ?? '' }}</td>
                <td>{{ $d->f17a->f1741 ?? '' }}</td>
                <td>{{ $d->f17b->f1742b ?? '' }}</td>
                <td>{{ $d->f17a->f1743 ?? '' }}</td>
                <td>{{ $d->f17b->f1744b ?? '' }}</td>
                <td>{{ $d->f17a->f1745 ?? '' }}</td>
                <td>{{ $d->f17b->f1746b ?? '' }}</td>
                <td>{{ $d->f17a->f1747 ?? '' }}</td>
                <td>{{ $d->f17b->f1748b ?? '' }}</td>
                <td>{{ $d->f17a->f1749 ?? '' }}</td>
                <td>{{ $d->f17b->f1750b ?? '' }}</td>
                <td>{{ $d->f17a->f1751 ?? '' }}</td>
                <td>{{ $d->f17b->f1752b ?? '' }}</td>
                <td>{{ $d->f17a->f1753 ?? '' }}</td>
                <td>{{ $d->f17b->f1754b ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
