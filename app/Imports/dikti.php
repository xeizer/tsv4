<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use App\User;
use App\Mahasiswam;
use App\F2m;
use App\F3m;
use App\F4m;
use App\F5m;
use App\F6m;
use App\F7m;
use App\F7am;
use App\F8m;
use App\F9m;
use App\F10m;
use App\F11m;
use App\F12m;
use App\F13m;
use App\F14m;
use App\F15m;
use App\F16m;
use App\F17am;
use App\F17bm;
use App\Listf17m;
use App\Stakeholderm;

class dikti implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row['nimhsmsmh']) {
                if ($user = User::where('nim', $row['nimhsmsmh'])->first()) {
                    //dd($row);
                    F2m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f21' => $row['f21'],
                            'f22' => $row['f22'],
                            'f23' => $row['f23'],
                            'f24' => $row['f24'],
                            'f25' => $row['f25'],
                            'f26' => $row['f26'],
                            'f27' => $row['f27'],
                        ]
                    );
                    F3m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f301' => $row['f301'],
                            'f302' => $row['f302'],
                            'f303' => $row['f303'],
                        ]
                    );
                    F4m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f41' => $row['f401'],
                            'f42' => $row['f402'],
                            'f43' => $row['f403'],
                            'f44' => $row['f404'],
                            'f45' => $row['f405'],
                            'f46' => $row['f406'],
                            'f47' => $row['f407'],
                            'f48' => $row['f408'],
                            'f49' => $row['f409'],
                            'f410' => $row['f410'],
                            'f411' => $row['f411'],
                            'f412' => $row['f412'],
                            'f413' => $row['f413'],
                            'f414' => $row['f414'],
                            'f415' => $row['f415'],
                            'f416' => $row['f416'],
                        ]
                    );
                    F4m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f41' => $row['f401'],
                            'f42' => $row['f402'],
                            'f43' => $row['f403'],
                            'f44' => $row['f404'],
                            'f45' => $row['f405'],
                            'f46' => $row['f406'],
                            'f47' => $row['f407'],
                            'f48' => $row['f408'],
                            'f49' => $row['f409'],
                            'f410' => $row['f410'],
                            'f411' => $row['f411'],
                            'f412' => $row['f412'],
                            'f413' => $row['f413'],
                            'f414' => $row['f414'],
                            'f415' => $row['f415'],
                            'f416' => $row['f416'],
                        ]
                    );
                    F5m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f501' => $row['f501'],
                            'f502' => $row['f502'],
                            'f503' => $row['f503'],
                        ]
                    );
                    F6m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f6' => $row['f6'],
                        ]
                    );
                    F7m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f7' => $row['f7'],
                        ]
                    );
                    F7am::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f7a' => $row['f7a'],
                        ]
                    );
                    F8m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f8' => $row['f8'],
                        ]
                    );
                    F9m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f91' => $row['f901'],
                            'f92' => $row['f902'],
                            'f93' => $row['f903'],
                            'f94' => $row['f904'],
                            'f95' => $row['f905'],
                            'f96' => $row['f906'],
                        ]
                    );
                    F10m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f101' => $row['f1001'],
                            'f102' => $row['f1002'],
                        ]
                    );
                    F11m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f111' => $row['f1101'],
                            'f112' => $row['f1102'],
                        ]
                    );
                    F12m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f121' => $row['f1201'],
                            'f122' => $row['f1202'],
                        ]
                    );
                    F13m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f131' => $row['f1301'],
                            'f132' => $row['f1302'],
                            'f133' => $row['f1303'],
                        ]
                    );
                    F14m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f14' => $row['f14'],
                        ]
                    );
                    F15m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f15' => $row['f15'],
                        ]
                    );
                    F16m::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f161' => $row['f1601'],
                            'f162' => $row['f1602'],
                            'f163' => $row['f1603'],
                            'f164' => $row['f1604'],
                            'f165' => $row['f1605'],
                            'f166' => $row['f1606'],
                            'f167' => $row['f1607'],
                            'f168' => $row['f1608'],
                            'f169' => $row['f1609'],
                            'f1610' => $row['f1610'],
                            'f1611' => $row['f1611'],
                            'f1612' => $row['f1612'],
                            'f1613' => $row['f1613'],
                            'f1614' => $row['f1614'],
                        ]
                    );
                    F17am::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f171' => $row['f1701'],
                            'f173' => $row['f1703'],
                            'f175' => $row['f1705'],
                            'f175a' => $row['f1705a'],
                            'f177' => $row['f1707'],
                            'f179' => $row['f1709'],
                            'f1711' => $row['f1711'],
                            'f1713' => $row['f1713'],
                            'f1715' => $row['f1715'],
                            'f1717' => $row['f1717'],
                            'f1719' => $row['f1719'],
                            'f1721' => $row['f1721'],
                            'f1723' => $row['f1723'],
                            'f1725' => $row['f1725'],
                            'f1727' => $row['f1727'],
                            'f1729' => $row['f1729'],
                            'f1731' => $row['f1731'],
                            'f1733' => $row['f1733'],
                            'f1735' => $row['f1735'],
                            'f1737' => $row['f1737'],
                            'f1737a' => $row['f1737a'],
                            'f1739' => $row['f1739'],
                            'f1741' => $row['f1741'],
                            'f1743' => $row['f1743'],
                            'f1745' => $row['f1745'],
                            'f1747' => $row['f1747'],
                            'f1749' => $row['f1749'],
                            'f1751' => $row['f1751'],
                            'f1753' => $row['f1753'],
                        ]
                    );
                    F17bm::updateOrCreate(
                        ['mahasiswam_id' => $user->mahasiswa->id],
                        [
                            'f172b' => $row['f1702b'],
                            'f174b' => $row['f1704b'],
                            'f176b' => $row['f1706'],
                            'f176ba' => $row['f1706ba'],
                            'f178b' => $row['f1708b'],
                            'f1710b' => $row['f1710b'],
                            'f1712b' => $row['f1712b'],
                            'f1714b' => $row['f1714b'],
                            'f1716b' => $row['f1716b'],
                            'f1718b' => $row['f1718b'],
                            'f1720b' => $row['f1720b'],
                            'f1722b' => $row['f1722b'],
                            'f1724b' => $row['f1724b'],
                            'f1726b' => $row['f1726b'],
                            'f1728b' => $row['f1728b'],
                            'f1730b' => $row['f1730b'],
                            'f1732b' => $row['f1732b'],
                            'f1734b' => $row['f1734b'],
                            'f1736b' => $row['f1736b'],
                            'f1738b' => $row['f1738'],
                            'f1738ba' => $row['f1738ba'],
                            'f1740b' => $row['f1740b'],
                            'f1742b' => $row['f1742b'],
                            'f1744b' => $row['f1744b'],
                            'f1746b' => $row['f1746b'],
                            'f1748b' => $row['f1748b'],
                            'f1750b' => $row['f1750b'],
                            'f1752b' => $row['f1752b'],
                            'f1754b' => $row['f1754b'],

                        ]
                    );
                    Mahasiswam::find($user->mahasiswa->id)->update(['status' => '99']);
                }
            }
        }
    }
}
